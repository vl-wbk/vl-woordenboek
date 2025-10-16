<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * The DatabaseSeeder class serves as the main seeder for the application's database.
 * Its primary function is to orchestrate the execution of all other individual seeders, ensuring that the database is populated with essential initial data in a structured and controlled manner.
 *
 * This central seeder is crucial for setting up a new application environment, refreshing a development database to a known state, or deploying the application with baseline data.
 * By calling other specific table seeders, it ensures that dependencies between different data sets are managed effectively, providing a consistent and ready-to-use database for the application to function correctly.
 *
 * @see Seeder - The base class for all seeders in Laravel.
 *
 * @package Database\Seeders
 */
final class DatabaseSeeder extends Seeder
{
    /**
     * Executes the application's database seeding operations.
     *
     * This method is the entry point for populating the entire database.
     * It systematically invokes each of the specific table seeders in a predefined order.
     * This ensures that any data dependencies are correctly handled; for example, if one table relies on data from another, the dependent table's seeder is called after its prerequisites have been met.
     * Specifically, it calls the `UserTableSeeder` to populate user data, the `RegionTableSeeder` for geographical regions, the `LabelTableSeeder` for
     * various labels, the `PartOfSpeechTableSeeder` for linguistic parts of speech,  and the `DisclaimerTableSeeder` for disclaimer texts.
     * This comprehensive execution sequence guarantees a fully initialized and functional database state, ready for application use.
     *
     * @return void     This method does not return any value. Its side effect is the population of various tables within the application's database.
     */
    public function run(): void
    {
        $this->call(UserTableSeeder::class);
        $this->call(RegionTableSeeder::class);
        $this->call(LabelTableSeeder::class);
        $this->call(PartOfSpeechTableSeeder::class);
        $this->call(DisclaimerTableSeeder::class);
        $this->call(ReferenceWorkSeeder::class);
    }
}
