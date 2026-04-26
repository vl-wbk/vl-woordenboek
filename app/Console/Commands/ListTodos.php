<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Attributes\Todo;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Collection;
use ReflectionClass;
use ReflectionMethod;
use ReflectionProperty;
use Carbon\Carbon;
use Throwable;

class ListTodos extends Command
{
    /**
     * The name and signature of the console command.
     */
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

    /**
     * The console command description.
     */
    protected $description = 'List all #[Todo] attributes in the codebase';

    private Collection $todos;

    private array $priorityOrder = [
    'critical' => 0,
    'urgent'   => 1,
    'high'     => 2,
    'normal'   => 3,
    'low'      => 4,
    'info'     => 5,
];

    public function __construct()
    {
        parent::__construct();
        $this->todos = collect();
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->clearConsole();
        $path = base_path($this->option('path'));

        if (!is_dir($path)) {
            $this->error("Path does not exist: {$path}");
            return self::FAILURE;
        }

        $validPriorities = array_keys($this->priorityOrder);
$selected = $this->option('priority');

if ($selected && !in_array($selected, $validPriorities)) {
    $this->error("Invalid priority. Choose from: " . implode(', ', $validPriorities));
    return self::FAILURE;
}

        $this->info("🔍 Scanning: {$path}");
        $this->newLine();

        $this->scanDirectory($path);
        $this->applyFilters();

        if ($this->todos->isEmpty()) {
            $this->warn('No TODOs found.');
            return self::SUCCESS;
        }

        $this->renderOutput();

        return $this->determineExitCode();
    }

    // ─── Scanning Logic ──────────────────────────────────────────────────────

    private function scanDirectory(string $path): void
    {
        foreach (File::allFiles($path) as $file) {
            if ($file->getExtension() !== 'php') {
                continue;
            }

            $className = $this->resolveClassName($file->getPathname());
            if (!$className) {
                continue;
            }

            $this->safeAction(function () use ($className) {
                $reflection = new ReflectionClass($className);
                $this->extractFromClass($reflection);
            });
        }
    }

    private function extractFromClass(ReflectionClass $reflection): void
    {
        $className = $reflection->getName();

        // 1. Class level
        $this->capture($reflection, 'class', $reflection->getShortName(), $reflection);

        // 2. Methods
        foreach ($reflection->getMethods() as $method) {
            if ($method->getDeclaringClass()->getName() === $className) {
                $this->capture($method, 'method', $this->formatMethodSignature($method), $reflection);
            }
        }

        // 3. Properties
        foreach ($reflection->getProperties() as $property) {
            if ($property->getDeclaringClass()->getName() === $className) {
                $this->capture($property, 'property', '$' . $property->getName(), $reflection);
            }
        }
    }

    private function capture(object $target, string $type, string $label, ReflectionClass $class): void
    {
        $attributes = method_exists($target, 'getAttributes') 
            ? $target->getAttributes(Todo::class) 
            : [];

        foreach ($attributes as $attr) {
            $this->todos->push(
                $this->transformToEntry($attr->newInstance(), $target, $type, $label, $class)
            );
        }
    }

    private function transformToEntry(Todo $todo, object $target, string $type, string $label, ReflectionClass $class): array
    {
        $file = method_exists($target, 'getFileName') ? $target->getFileName() : $class->getFileName();
        $line = method_exists($target, 'getStartLine') ? $target->getStartLine() : '—';

        return [
            'message'  => $todo->message,
            'author'   => $todo->author ?: '—',
            'priority' => $todo->priority,
            'issue'    => $todo->issue ?: '—',
            'due'      => $todo->due,
            'tags'     => $todo->tags,
            'overdue'  => $todo->due && now()->gt(Carbon::parse($todo->due)),
            'class'    => $class->getShortName(),
            'type'     => $type,
            'target'   => $label,
            'file'     => $this->getRelativePath($file),
            'line'     => $line,
        ];
    }

    // ─── Filters & Exit ──────────────────────────────────────────────────────

    private function applyFilters(): void
    {
        $this->todos = $this->todos
            ->when($this->option('priority'), fn($c, $p) => $c->where('priority', $p))
            ->when($this->option('author'),   fn($c, $a) => $c->where('author', $a))
            ->when($this->option('methods'),  fn($c)    => $c->where('type', 'method'))
            ->when($this->option('overdue'),  fn($c)    => $c->where('overdue', true))
            ->when($this->option('tag'),      fn($c, $t) => $c->filter(fn($i) => in_array($t, $i['tags'])))
            ->sortBy(fn($t) => $this->priorityOrder[$t['priority']] ?? 1)
            ->values();
    }

    private function determineExitCode(): int
    {
        if ($this->option('fail-on-overdue') && $this->todos->contains('overdue', true)) {
            $this->error('❌ Overdue TODOs found — failing build.');
            return self::FAILURE;
        }

        if ($this->option('fail-on-found') && $this->todos->isNotEmpty()) {
            $this->error('❌ TODOs found — failing build.');
            return self::FAILURE;
        }

        return self::SUCCESS;
    }

    // ─── Output Formats ──────────────────────────────────────────────────────

    private function renderOutput(): void
    {
        match ($this->option('format')) {
            'json'  => $this->outputJson(),
            'csv'   => $this->outputCsv(),
            'md'    => $this->outputMarkdown(),
            default => $this->outputTable(),
        };
    }

    private function outputTable(): void
    {
        $this->todos->groupBy('class')->each(function ($classTodos, $className) {
            $this->line("<fg=cyan;options=bold>  {$className}</>");
            $this->line(str_repeat('─', 100));

            $this->table(
                ['Priority', 'Type', 'Target / Signature', 'Message', 'Author', 'Issue', 'Due', 'Tags', 'Line'],
                $classTodos->map(fn($t) => [
                    $this->formatPriority($t['priority']),
                    $this->formatType($t['type']),
                    $t['target'],
                    $t['message'],
                    $t['author'],
                    $t['issue'],
                    $this->formatDue($t['due']),
                    implode(', ', $t['tags']) ?: '—',
                    "{$t['file']}:{$t['line']}",
                ])->toArray()
            );
            $this->newLine();
        });

        $this->renderSummary();
    }

    private function renderSummary(): void
{
    $total = $this->todos->count();
    $overdue = $this->todos->where('overdue', true)->count();
    $hasDueLine = $this->todos->whereNotNull('due')->count();
    $priorities = $this->todos->countBy('priority');

    $this->line(' <fg=white;bg=blue;options=bold> SUMMARY </>');
    $this->newLine();

    // 1. Dynamic Priority Breakdown
    // We map through our defined priority order to ensure they appear in the correct sequence
    $breakdown = collect($this->priorityOrder)
        ->map(function ($order, $name) use ($priorities) {
            $count = $priorities->get($name, 0);
            $color = $this->getPriorityColor($name);
            return "<fg={$color};options=bold>{$count} " . ucfirst($name) . "</>";
        })
        ->implode('  <fg=gray>│</>  ');

    $this->line(" {$breakdown}");
    $this->line(' <fg=gray>' . str_repeat('─', 80) . '</>');

    // 2. Task Totals
    $this->line(sprintf(
        " <fg=white>Total Tasks</>  <fg=blue;options=bold>%d</>", 
        $total
    ));

    // 3. Deadline Stats
    if ($hasDueLine > 0) {
        $this->line(sprintf(
            " <fg=white>Scheduled  </>  <fg=green>%d</>", 
            $hasDueLine
        ));
    }

    if ($overdue > 0) {
        $this->line(sprintf(
            " <fg=white>Overdue    </>  <fg=red;options=bold,blink>%d !!</>", 
            $overdue
        ));
    }

    $this->newLine();
}

/**
 * Helper to get just the color name for the summary line.
 */
private function getPriorityColor(string $priority): string
{
    return match ($priority) {
        'critical' => 'red',
        'urgent'   => 'magenta',
        'high'     => 'red',
        'low'      => 'green',
        'info'     => 'blue',
        default    => 'yellow',
    };
}

    private function outputJson(): void
    {
        $this->line($this->todos->toJson(JSON_PRETTY_PRINT));
    }

    private function outputCsv(): void
    {
        $headers = ['priority', 'type', 'class', 'target', 'message', 'author', 'issue', 'due', 'tags', 'file', 'line'];
        $this->line(implode(',', $headers));

        $this->todos->each(function ($t) {
            $this->line(implode(',', [
                $t['priority'],
                $t['type'],
                $t['class'],
                '"' . str_replace('"', '""', $t['target']) . '"',
                '"' . str_replace('"', '""', $t['message']) . '"',
                $t['author'],
                $t['issue'],
                $t['due'] ?? '',
                implode('|', $t['tags']),
                $t['file'],
                $t['line'],
            ]));
        });
    }

    private function outputMarkdown(): void
    {
        $this->line('# TODO List');
        $this->newLine();
        $this->line('| Priority | Type | Class | Target | Message | Author | Issue | Due | Tags |');
        $this->line('|---|---|---|---|---|---|---|---|---|');

        $this->todos->each(function ($t) {
            $due = $t['due'] ?? '—';
            $tags = implode(', ', $t['tags']) ?: '—';
            $this->line("| {$t['priority']} | {$t['type']} | {$t['class']} | {$t['target']} | {$t['message']} | {$t['author']} | {$t['issue']} | {$due} | {$tags} |");
        });

        $this->newLine();
        $this->line('> Generated by `php artisan todo:list`');
    }

    // ─── Formatters & Helpers ────────────────────────────────────────────────

    private function formatPriority(string $priority): string
{
    return match ($priority) {
        'critical' => '<fg=white;bg=red;options=bold,blink> ✘ CRITICAL </>',
        'urgent'   => '<fg=white;bg=magenta> ! URGENT   </>',
        'high'     => '<fg=white;bg=red> ● HIGH     </>',
        'low'      => '<fg=black;bg=green> ● LOW      </>',
        'info'     => '<fg=white;bg=blue> ℹ INFO     </>',
        default    => '<fg=black;bg=yellow> ● NORMAL   </>',
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
        if (!$due) return '—';

        $date = Carbon::parse($due);
        return $date->isPast()
            ? "<fg=red>⚠ {$due}</>"
            : "<fg=green>{$due}</>";
    }

    private function formatMethodSignature(ReflectionMethod $method): string
    {
        $params = array_map(function ($param) {
            $type = $param->getType()?->getName() ?? '';
            $name = '$' . $param->getName();
            return $type ? "{$type} {$name}" : $name;
        }, $method->getParameters());

        $visibility = $this->getVisibility($method);
        $static = $method->isStatic() ? 'static ' : '';
        $signature = implode(', ', $params);

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

    private function resolveClassName(string $filePath): ?string
    {
        $tokens = token_get_all(file_get_contents($filePath));
        $namespace = '';
        $class = '';

        for ($i = 0; $i < count($tokens); $i++) {
            if ($tokens[$i][0] === T_NAMESPACE) {
                for ($j = $i + 1; $j < count($tokens); $j++) {
                    if ($tokens[$j] === ';') { $i = $j; break; }
                    if (is_array($tokens[$j])) $namespace .= $tokens[$j][1];
                }
            }

            if (in_array($tokens[$i][0], [T_CLASS, T_INTERFACE, T_TRAIT, T_ENUM], true)) {
                if ($i > 0 && $tokens[$i - 1][0] === T_DOUBLE_COLON) continue;
                for ($j = $i + 1; $j < count($tokens); $j++) {
                    if ($tokens[$j][0] === T_STRING) {
                        $class = $tokens[$j][1];
                        break 2;
                    }
                }
            }
        }

        if ($class === '') return null;
        $fqcn = trim($namespace) ? trim($namespace) . '\\' . $class : $class;

        return (class_exists($fqcn) || interface_exists($fqcn) || trait_exists($fqcn) || enum_exists($fqcn)) 
            ? $fqcn 
            : null;
    }

    private function getRelativePath(?string $absolutePath): string
    {
        if (!$absolutePath) return '—';
        return str_replace(base_path() . DIRECTORY_SEPARATOR, '', $absolutePath);
    }

    private function clearConsole(): void
    {
        $this->output->write("\033[2J\033[;H");
    }

    private function safeAction(callable $callback): void
    {
        try {
            $callback();
        } catch (Throwable) {
            // Silently skip corrupted classes/files
        }
    }
}