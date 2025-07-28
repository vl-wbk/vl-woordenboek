<?php

namespace App\Console\Commands;

use App\Attributes\Todo;
use Carbon\Carbon; // Make sure to use Carbon for date handling
use Illuminate\Console\Command;
use ReflectionClass;
use ReflectionMethod;
use ReflectionProperty;
use ReflectionParameter; // Also include ReflectionParameter
use Symfony\Component\Finder\Finder;

class ListTodos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:list-todos
                            {--P|priority=* : Filter by one or more priorities (e.g., High, Medium)}
                            {--A|assignee=* : Filter by one or more assignees (e.g., John Doe, Team Alpha)}
                            {--exclude=* : Exclude directories or files (relative paths, glob patterns supported)}
                            {--due-soon= : Filter by TODOs due within X days (e.g., 7, 30)}
                            {--format= : Output format (table, json) (default: table)}
                            {--S|sort= : Sort output by column (type, target, message, priority, assignee, dueDate)}
                            {--count : Only display the total count of TODOs found}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Lists all #[Todo] attributes in the application with advanced filtering and output options.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info("Scanning application for #[Todo] attributes...");

        $finder = (new Finder())->in(app_path())->name('*.php')->files();

        // Exclude paths
        $excludePaths = $this->option('exclude');
        if (!empty($excludePaths)) {
            foreach ($excludePaths as $excludePath) {
                // Symfony Finder's exclude method works for directories.
                // For file patterns, notPath is more appropriate.
                if (str_contains($excludePath, '*') || str_contains($excludePath, '.') || str_contains($excludePath, '/')) {
                     // If it looks like a file pattern or path, use notPath
                    $finder->notPath(str_replace(base_path() . '/', '', $excludePath));
                    $finder->notName(basename($excludePath)); // Also exclude by filename
                } else {
                    // Otherwise, assume it's a directory name
                    $finder->exclude($excludePath);
                }
            }
        }

        $todosFound = [];
        $scannedFilesCount = 0;
        $reflectionErrors = [];

        foreach ($finder as $file) {
            $scannedFilesCount++;
            $path = $file->getRealPath();
            $relativePath = str_replace(base_path() . '/', '', $path);

            if ($this->option('verbose')) {
                $this->line("Scanning: <comment>{$relativePath}</comment>");
            }

            $content = file_get_contents($path);

            // Basic check to see if the file might contain attributes before reflection
            if (str_contains($content, '#[Todo(')) {
                $namespace = $this->extractNamespace($content);
                $className = $this->extractClassName($content);

                if ($namespace && $className) {
                    $fullClassName = $namespace . '\\' . $className;

                    try {
                        if (class_exists($fullClassName)) {
                            $reflectionClass = new ReflectionClass($fullClassName);

                            // Check class attributes
                            $todosFound = array_merge($todosFound, $this->getAttributesFromReflector($reflectionClass, $fullClassName, 'Class', $relativePath));

                            // Check method attributes
                            foreach ($reflectionClass->getMethods() as $method) {
                                $todosFound = array_merge($todosFound, $this->getAttributesFromReflector($method, $fullClassName . '::' . $method->getName(), 'Method', $relativePath));

                                // Check parameter attributes
                                foreach ($method->getParameters() as $parameter) {
                                    $todosFound = array_merge($todosFound, $this->getAttributesFromReflector($parameter, $fullClassName . '::' . $method->getName() . '($' . $parameter->getName() . ')', 'Parameter', $relativePath));
                                }
                            }

                            // Check property attributes
                            foreach ($reflectionClass->getProperties() as $property) {
                                $todosFound = array_merge($todosFound, $this->getAttributesFromReflector($property, $fullClassName . '::$' . $property->getName(), 'Property', $relativePath));
                            }
                        }
                    } catch (\ReflectionException $e) {
                        $reflectionErrors[] = "Could not reflect on '{$fullClassName}' in '{$relativePath}': " . $e->getMessage();
                        if ($this->option('verbose')) {
                            $this->warn("Skipping '{$fullClassName}' due to reflection error.");
                        }
                    }
                }
            }
        }

        // --- Apply Filters ---

        // Filter by multiple priorities
        $filteredPriorities = array_map('strtolower', (array) $this->option('priority'));
        if (!empty($filteredPriorities)) {
            $todosFound = array_filter($todosFound, fn($todo) => in_array(strtolower($todo['priority'] ?? ''), $filteredPriorities));
        }

        // Filter by multiple assignees
        $filteredAssignees = array_map('strtolower', (array) $this->option('assignee'));
        if (!empty($filteredAssignees)) {
            $todosFound = array_filter($todosFound, fn($todo) => in_array(strtolower($todo['assignee'] ?? ''), $filteredAssignees));
        }

        // Filter by due soon
        $dueSoonDays = (int) $this->option('due-soon');
        if ($dueSoonDays > 0) {
            $today = Carbon::today();
            $dueDateThreshold = $today->copy()->addDays($dueSoonDays);

            $todosFound = array_filter($todosFound, function($todo) use ($today, $dueDateThreshold) {
                if (empty($todo['dueDate'])) {
                    return false;
                }
                try {
                    $dueDate = Carbon::parse($todo['dueDate']);
                    return $dueDate->greaterThanOrEqualTo($today) && $dueDate->lessThanOrEqualTo($dueDateThreshold);
                } catch (\Exception $e) {
                    // Ignore invalid date formats for filtering
                    return false;
                }
            });
        }

        // --- Output Results ---

        if ($this->option('count')) {
            $this->info("Total TODOs found (after filters): " . count($todosFound));
            if ($this->option('verbose')) {
                $this->line("Files scanned: {$scannedFilesCount}");
                if (!empty($reflectionErrors)) {
                    $this->error("Reflection errors encountered: " . count($reflectionErrors));
                }
            }
            return Command::SUCCESS;
        }

        if (empty($todosFound)) {
            $this->info("No #[Todo] attributes found with the given filters.");
            if ($this->option('verbose')) {
                $this->line("Files scanned: {$scannedFilesCount}");
                if (!empty($reflectionErrors)) {
                    $this->error("Reflection errors encountered: " . count($reflectionErrors));
                    foreach ($reflectionErrors as $error) {
                        $this->line("- " . $error);
                    }
                }
            }
            return Command::SUCCESS;
        }

        // --- Sorting ---
        $sortBy = $this->option('sort');
        if ($sortBy) {
            $validSortColumns = ['type', 'target', 'message', 'priority', 'assignee', 'dueDate'];
            if (!in_array($sortBy, $validSortColumns)) {
                $this->error("Invalid sort column: '{$sortBy}'. Valid columns are: " . implode(', ', $validSortColumns));
                return Command::FAILURE;
            }
            usort($todosFound, function($a, $b) use ($sortBy) {
                $valA = $a[$sortBy] ?? '';
                $valB = $b[$sortBy] ?? '';
                // Handle null/empty values for sorting consistently
                if (empty($valA) && !empty($valB)) return 1;
                if (!empty($valA) && empty($valB)) return -1;
                if (empty($valA) && empty($valB)) return 0;

                if ($sortBy === 'dueDate') {
                    try {
                        $dateA = Carbon::parse($valA);
                        $dateB = Carbon::parse($valB);
                        return $dateA <=> $dateB;
                    } catch (\Exception $e) {
                        return strcmp($valA, $valB); // Fallback to string compare
                    }
                }
                return strcasecmp($valA, $valB);
            });
        }

        $outputFormat = $this->option('format') ?? 'table';

        switch (strtolower($outputFormat)) {
            case 'json':
                $this->output->writeln(json_encode($todosFound, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
                break;
            case 'table':
            default:
                $headers = ['Type', 'Target', 'Message', 'Priority', 'Assignee', 'Due Date', 'File:Line'];
                $data = [];

                foreach ($todosFound as $todo) {
                    $data[] = [
                        $todo['type'],
                        $todo['target'],
                        $todo['message'],
                        $todo['priority'] ?? 'N/A',
                        $todo['assignee'] ?? 'N/A',
                        $todo['dueDate'] ?? 'N/A',
                        "{$todo['file']}:{$todo['line']}",
                    ];
                }
                $this->table($headers, $data);
                break;
        }

        if ($this->option('verbose')) {
            $this->line("\n--- Scan Summary ---");
            $this->line("Files scanned: <info>{$scannedFilesCount}</info>");
            $this->line("TODOs found (before filters): <info>" . count($todosFound) . "</info>");
            $this->line("TODOs displayed (after filters): <info>" . count($todosFound) . "</info>"); // This count is already filtered
            if (!empty($reflectionErrors)) {
                $this->error("Reflection errors encountered: " . count($reflectionErrors));
                foreach ($reflectionErrors as $error) {
                    $this->line("- " . $error);
                }
            }
            $this->line("--------------------");
        }

        return Command::SUCCESS;
    }

    /**
     * Extracts Todo attributes from a Reflection object.
     *
     * @param ReflectionClass|ReflectionMethod|ReflectionProperty|ReflectionParameter $reflector
     * @param string $targetName
     * @param string $type
     * @param string $filePath
     * @return array
     */
    protected function getAttributesFromReflector($reflector, string $targetName, string $type, string $filePath): array
    {
        $todos = [];
        foreach ($reflector->getAttributes(Todo::class) as $attribute) {
            $todo = $attribute->newInstance();
            $line = $reflector instanceof ReflectionClass ? $reflector->getStartLine() : $reflector->getStartLine();
            if ($reflector instanceof ReflectionParameter) {
                // ReflectionParameter doesn't have getStartLine(), so we get it from its declaring function/method
                $line = $reflector->getDeclaringFunction()->getStartLine();
            }

            $todos[] = [
                'type' => $type,
                'target' => $targetName,
                'message' => $todo->message,
                'priority' => $todo->priority,
                'assignee' => $todo->assignee,
                'dueDate' => $todo->dueDate,
                'file' => $filePath,
                'line' => $line,
            ];
        }
        return $todos;
    }

    /**
     * Helper to extract namespace from file content.
     */
    private function extractNamespace(string $content): ?string
    {
        preg_match('/namespace\s+([^;]+);/', $content, $matches);
        return $matches[1] ?? null;
    }

    /**
     * Helper to extract class name from file content.
     */
    private function extractClassName(string $content): ?string
    {
        preg_match('/(?:class|interface|trait)\s+(\w+)/', $content, $matches);
        return $matches[1] ?? null;
    }
}
