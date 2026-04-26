<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Attributes\Todo;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use ReflectionClass;
use ReflectionMethod;

class ListTodos extends Command
{
    protected $signature = 'todo:list
                            {--path=app           : Directory to scan}
                            {--priority=          : Filter by priority (low, normal, high)}
                            {--author=            : Filter by author}
                            {--tag=               : Filter by tag}
                            {--methods            : Show only method-level TODOs}
                            {--overdue            : Show only overdue TODOs}
                            {--format=table       : Output format (table, json, csv, md)}
                            {--fail-on-found      : Exit with code 1 if any TODOs are found}
                            {--fail-on-overdue    : Exit with code 1 if any overdue TODOs are found}';

    protected $description = 'List all #[Todo] attributes in the codebase';

    private array $todos = [];

    private array $priorityOrder = ['high' => 0, 'normal' => 1, 'low' => 2];

    public function handle(): int
    {
        $this->clearConsole();
        $path = base_path($this->option('path'));

        if (! is_dir($path)) {
            $this->error("Path does not exist: {$path}");

            return self::FAILURE;
        }

        $this->info("🔍 Scanning: {$path}");
        $this->newLine();

        $this->scanDirectory($path);
        $this->applyFilters();

        if (empty($this->todos)) {
            $this->warn('No TODOs found.');

            return self::SUCCESS;
        }

        $this->renderOutput();

        // ── CI hooks ──────────────────────────────────────────────────────────
        if ($this->option('fail-on-overdue') && $this->hasOverdue()) {
            $this->error('❌ Overdue TODOs found — failing build.');

            return self::FAILURE;
        }

        if ($this->option('fail-on-found')) {
            $this->error('❌ TODOs found — failing build.');

            return self::FAILURE;
        }

        return self::SUCCESS;
    }

    protected function clearConsole(): void
{
    // ANSI escape code to clear the screen
    $this->output->write("\033[2J\033[;H");
}

    // ─── Scanning ────────────────────────────────────────────────────────────

    private function scanDirectory(string $path): void
    {
        foreach (File::allFiles($path) as $file) {
            if ($file->getExtension() !== 'php') {
                continue;
            }

            $className = $this->resolveClassName($file->getPathname());
            if (! $className) {
                continue;
            }

            if (! class_exists($className) && ! interface_exists($className) && ! trait_exists($className)) {
                try {
                    require_once $file->getPathname();
                } catch (\Throwable $e) {
                    $this->warn("  Could not load: {$className} — {$e->getMessage()}");
                    continue;
                }
            }

            try {
                $this->extractTodosFromClass(new ReflectionClass($className));
            } catch (\Throwable $e) {
                $this->warn("  Skipped: {$className} — {$e->getMessage()}");
                continue;
            }
        }
    }

    private function extractTodosFromClass(ReflectionClass $reflection): void
    {
        try {
            $this->extractFromTarget($reflection, 'class', $reflection->getShortName(), $reflection);
        } catch (\Throwable) {
        }

        foreach ($reflection->getMethods(ReflectionMethod::IS_PUBLIC | ReflectionMethod::IS_PROTECTED | ReflectionMethod::IS_PRIVATE) as $method) {
            if ($method->getDeclaringClass()->getName() !== $reflection->getName()) {
                continue;
            }

            try {
                $this->extractFromMethod($method, $reflection);
            } catch (\Throwable) {
            }
        }

        foreach ($reflection->getProperties() as $property) {
            if ($property->getDeclaringClass()->getName() !== $reflection->getName()) {
                continue;
            }

            try {
                $this->extractFromTarget($property, 'property', '$'.$property->getName(), $reflection);
            } catch (\Throwable) {
            }
        }
    }

    // ─── Extraction ──────────────────────────────────────────────────────────

    private function extractFromMethod(ReflectionMethod $method, ReflectionClass $class): void
    {
        foreach ($method->getAttributes(Todo::class) as $attr) {
            /** @var Todo $todo */
            $todo = $attr->newInstance();

            $this->todos[] = $this->buildEntry($todo, $class, [
                'type'       => 'method',
                'target'     => $this->formatMethodSignature($method),
                'file'       => $this->getRelativePath($method->getFileName()),
                'line'       => $method->getStartLine(),
                'visibility' => $this->getVisibility($method),
                'static'     => $method->isStatic(),
            ]);
        }
    }

    private function extractFromTarget(\Reflector $target, string $type, string $label, ReflectionClass $class): void
    {
        foreach ($target->getAttributes(Todo::class) as $attr) {
            /** @var Todo $todo */
            $todo = $attr->newInstance();

            $this->todos[] = $this->buildEntry($todo, $class, [
                'type'       => $type,
                'target'     => $label,
                'file'       => $this->getRelativePath($class->getFileName()),
                'line'       => method_exists($target, 'getStartLine') ? $target->getStartLine() : '—',
                'visibility' => '—',
                'static'     => false,
            ]);
        }
    }

    private function buildEntry(Todo $todo, ReflectionClass $class, array $extra): array
    {
        return array_merge([
            'message'  => $todo->message,
            'author'   => $todo->author ?: '—',
            'priority' => $todo->priority,
            'issue'    => $todo->issue ?: '—',
            'due'      => $todo->due,
            'tags'     => $todo->tags,
            'overdue'  => $todo->due !== null && now()->gt(\Carbon\Carbon::parse($todo->due)),
            'class'    => $class->getShortName(),
        ], $extra);
    }

    // ─── Filters & Sorting ───────────────────────────────────────────────────

    private function applyFilters(): void
    {
        if ($priority = $this->option('priority')) {
            $this->todos = array_values(array_filter($this->todos, fn ($t) => $t['priority'] === $priority));
        }

        if ($author = $this->option('author')) {
            $this->todos = array_values(array_filter($this->todos, fn ($t) => $t['author'] === $author));
        }

        if ($tag = $this->option('tag')) {
            $this->todos = array_values(array_filter($this->todos, fn ($t) => in_array($tag, $t['tags'], true)));
        }

        if ($this->option('methods')) {
            $this->todos = array_values(array_filter($this->todos, fn ($t) => $t['type'] === 'method'));
        }

        if ($this->option('overdue')) {
            $this->todos = array_values(array_filter($this->todos, fn ($t) => $t['overdue'] === true));
        }

        usort($this->todos, fn ($a, $b) => $this->priorityOrder[$a['priority']] <=> $this->priorityOrder[$b['priority']]);
    }

    // ─── Output ──────────────────────────────────────────────────────────────

    private function renderOutput(): void
    {
        match ($this->option('format')) {
            'json' => $this->outputJson(),
            'csv'  => $this->outputCsv(),
            'md'   => $this->outputMarkdown(),
            default => $this->outputTable(),
        };
    }

    private function outputTable(): void
    {
        $grouped = collect($this->todos)->groupBy('class');

        foreach ($grouped as $className => $todos) {
            $this->line("<fg=cyan;options=bold>  {$className}</>");
            $this->line(str_repeat('─', 100));

            $this->table(
                ['Priority', 'Type', 'Target / Signature', 'Message', 'Author', 'Issue', 'Due', 'Tags', 'Line'],
                $todos->map(fn ($t) => [
                    $this->formatPriority($t['priority']),
                    $this->formatType($t['type']),
                    $t['target'],
                    $t['message'],
                    $t['author'],
                    $t['issue'],
                    $this->formatDue($t['due']),
                    implode(', ', $t['tags']) ?: '—',
                    $t['file'].':'.$t['line'],
                ])->toArray()
            );

            $this->newLine();
        }

        $this->renderSummary();
    }

private function renderSummary(): void
{
    $total = count($this->todos);
    $overdue = count(array_filter($this->todos, fn ($t) => $t['overdue']));
    
    // Aggregate priority counts
    $priorities = collect($this->todos)->countBy('priority');

    $this->newLine();
    $this->line(' <fg=white;bg=blue;options=bold> SUMMARY </>');
    $this->newLine();

    // Priority breakdown row
    $high   = $priorities->get('high', 0);
    $normal = $priorities->get('normal', 0) + $priorities->get('default', 0);
    $low    = $priorities->get('low', 0);

    $this->line(sprintf(
        ' <fg=red;options=bold>%d High</>  <fg=gray>│</>  <fg=yellow;options=bold>%d Normal</>  <fg=gray>│</>  <fg=green;options=bold>%d Low</>',
        $high,
        $normal,
        $low
    ));

    $this->line(' <fg=gray>' . str_repeat('─', 40) . '</>');

    // Totals and Overdue
    $this->line(sprintf(
        ' <fg=white>Total Tasks</>  <fg=blue;options=bold>%d</>', 
        $total
    ));

    if ($overdue > 0) {
        $this->line(sprintf(
            ' <fg=white>Overdue    </>  <fg=red;options=bold>%d</> <fg=red;options=blink>!!</>', 
            $overdue
        ));
    }

    $this->newLine();
}

    private function outputJson(): void
    {
        $this->line(json_encode(
            array_map(fn ($t) => array_merge($t, ['tags' => $t['tags']]), $this->todos),
            JSON_PRETTY_PRINT
        ));
    }

    private function outputCsv(): void
    {
        $headers = ['priority', 'type', 'class', 'target', 'message', 'author', 'issue', 'due', 'tags', 'file', 'line'];
        $this->line(implode(',', $headers));

        foreach ($this->todos as $t) {
            $this->line(implode(',', [
                $t['priority'],
                $t['type'],
                $t['class'],
                '"'.str_replace('"', '""', $t['target']).'"',
                '"'.str_replace('"', '""', $t['message']).'"',
                $t['author'],
                $t['issue'],
                $t['due'] ?? '',
                implode('|', $t['tags']),
                $t['file'],
                $t['line'],
            ]));
        }
    }

    private function outputMarkdown(): void
    {
        $this->line('# TODO List');
        $this->line('');
        $this->line('| Priority | Type | Class | Target | Message | Author | Issue | Due | Tags |');
        $this->line('|---|---|---|---|---|---|---|---|---|');

        foreach ($this->todos as $t) {
            $due  = $t['due'] ?? '—';
            $tags = implode(', ', $t['tags']) ?: '—';
            $this->line("| {$t['priority']} | {$t['type']} | {$t['class']} | {$t['target']} | {$t['message']} | {$t['author']} | {$t['issue']} | {$due} | {$tags} |");
        }

        $this->line('');
        $this->line('> Generated by `php artisan todo:list --format=md`');
    }

    // ─── Formatters ──────────────────────────────────────────────────────────

    private function formatPriority(string $priority): string
{
    return match ($priority) {
        'high'  => '<fg=white;bg=red> ● HIGH </>',
        'low'   => '<fg=black;bg=green> ● LOW </>',
        default => '<fg=black;bg=yellow> ● NORMAL </>',
    };
}

    private function formatType(string $type): string
    {
        return match ($type) {
            'method'   => '<fg=blue>method</>',
            'property' => '<fg=magenta>property</>',
            'class'    => '<fg=cyan>class</>',
            default    => $type,
        };
    }

    private function formatDue(?string $due): string
    {
        if (! $due) {
            return '—';
        }

        $date = \Carbon\Carbon::parse($due);

        return $date->isPast()
            ? "<fg=red>⚠ {$due}</>"
            : "<fg=green>{$due}</>";
    }

    private function formatMethodSignature(ReflectionMethod $method): string
    {
        $params = array_map(function ($param) {
            $type = $param->getType()?->getName() ?? '';
            $name = '$'.$param->getName();

            return $type ? "{$type} {$name}" : $name;
        }, $method->getParameters());

        $visibility = $this->getVisibility($method);
        $static     = $method->isStatic() ? 'static ' : '';
        $signature  = implode(', ', $params);

        return "{$visibility} {$static}{$method->getName()}({$signature})";
    }

    private function getVisibility(ReflectionMethod $method): string
    {
        return match (true) {
            $method->isPublic()    => 'public',
            $method->isProtected() => 'protected',
            $method->isPrivate()   => 'private',
            default                => 'public',
        };
    }

    // ─── Helpers ─────────────────────────────────────────────────────────────

    private function hasOverdue(): bool
    {
        return collect($this->todos)->contains(fn ($t) => $t['overdue'] === true);
    }

   private function resolveClassName(string $filePath): ?string
{
    $tokens = token_get_all(file_get_contents($filePath));
    $namespace = '';
    $class = '';

    for ($i = 0; $i < count($tokens); $i++) {
        // Find the namespace
        if ($tokens[$i][0] === T_NAMESPACE) {
            for ($j = $i + 1; $j < count($tokens); $j++) {
                if ($tokens[$j] === ';') {
                    $i = $j;
                    break;
                }
                if (is_array($tokens[$j])) {
                    $namespace .= $tokens[$j][1];
                }
            }
        }

        // Find the class, interface, or trait
        if (in_array($tokens[$i][0], [T_CLASS, T_INTERFACE, T_TRAIT, T_ENUM], true)) {
            // Ensure this isn't "::class" or a "use" statement
            if ($i > 0 && $tokens[$i - 1][0] === T_DOUBLE_COLON) {
                continue;
            }

            for ($j = $i + 1; $j < count($tokens); $j++) {
                if ($tokens[$j][0] === T_STRING) {
                    $class = $tokens[$j][1];
                    break 2; // Found it, stop scanning the file
                }
            }
        }
    }

    if ($class === '') {
        return null;
    }

    $fqcn = trim($namespace) ? trim($namespace) . '\\' . $class : $class;
    
    // Final check to see if it's a real class in the current environment
    return (class_exists($fqcn) || interface_exists($fqcn) || trait_exists($fqcn) || enum_exists($fqcn)) 
        ? $fqcn 
        : null;
}

    private function getRelativePath(?string $absolutePath): string
    {
        if (! $absolutePath) {
            return '—';
        }

        return str_replace(base_path().DIRECTORY_SEPARATOR, '', $absolutePath);
    }
}