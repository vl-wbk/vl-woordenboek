<?php

declare(strict_types=1);

namespace App\Console\Commands\DataMigration;

use App\Models\Article;
use App\Services\DataMigration\ArticleImporterFactory;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use RuntimeException;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'data:migration', description: 'Migrate the dictionary articles to the new database structure')]
final class MigrateArticleCommand extends Command
{
    private static string $sourceFile = 'imports/data-dump.json';

    public function __construct(
        private ArticleImporterFactory $articleImporterFactory
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        ini_set('memory_limit', '2G');

        $this->info('Starting dictionary article migration');

        try {
            $articleImporter = $this->articleImporterFactory->createImporter();

            $processedCount = $articleImporter->importArticles(
                self::$sourceFile,
                function (int $count): void {
                    $this->output->writeln("Processed: {$count} articles");
                },
                fn (string $message) => $this->warn($message)
            );

            $this->newLine();
            $this->info("Migration complete. Successfully processed {$processedCount} articles.");
            Log::info("Article migration finished. Total processed: {$processedCount}.");

            return Command::SUCCESS;
        } catch (RuntimeException $runtimeException) {
            $this->newLine();
            $this->error("A critical error occurred during article migration: {$runtimeException->getMessage()}");

            Log::critical("Article migration: Critical error.", ['error' => $runtimeException->getMessage(), 'path' => self::$sourceFile]);

            return Command::FAILURE;
        }
    }
}
