<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Disclaimer;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

/**
 * The DisclaimerTableSeeder class is a database seeder responsible for populating the 'disclaimers' table.
 * It achieves this by reading disclaimer data from a predefined JSON file and subsequently creating corresponding `Disclaimer` model instances in the database.
 *
 * This seeder is typically executed during the initial setup of a new application environment or as part of a database refresh operation in development.
 * Its primary goal is to ensure that all necessary disclaimer texts and configurations are present and readily available within the application's database.
 * This automated seeding process helps maintain data integrity and consistency across different environments, reducing the need for manual data entry for disclaimers.
 *
 * @see Disclaimer  - The Eloquent model representing a disclaimer in the database.
 * @see Seeder      - The base class for all database seeders in Laravel.
 *
 * @package Database\Seeders
 */
final class DisclaimerTableSeeder extends Seeder
{
    /**
     * Executes the seeding operation for the disclaimers table.
     *
     * This method orchestrates the complete process of populating the `disclaimers` table.
     * It begins by fetching the entire content of the `disclaimers.json` file, which is expected to reside within the `database/data` directory of the application.
     * Following the retrieval, the JSON content is then decoded into a standard PHP object, making the structured disclaimer data easily accessible for processing.
     * The method proceeds to iterate over each individual disclaimer object found within this decoded dataset.
     *
     * For every disclaimer object encountered during this iteration, a new instance of the `Disclaimer` Eloquent model is meticulously created.
     * The attributes for this new model, including `id`, `name`, `type`, `message`, `usage`, and `description`, are directly populated using the corresponding properties from the current JSON object.
     * This comprehensive and automated procedure guarantees that all disclaimer data is accurately and efficiently inserted into the database, providing a solid foundation for managing and displaying various disclaimers throughout the application.
     *
     * @return void     This method does not return any value. Its primary effect is the creation of records within the 'disclaimers' database table.
     *
     * @throws FileNotFoundException when the data file for the database seeder couldn't be found
     */
    public function run(): void
    {
        $jsonDataFile = File::get(database_path('data/disclaimers.json'));
        $regions = json_decode($jsonDataFile);

        foreach ($regions as $value) {
            Disclaimer::create(['id' => $value->id, 'name' => $value->name, 'type' => $value->type, 'message' => $value->message, 'usage' => $value->usage, 'description' => $value->description]);
        }
    }
}
