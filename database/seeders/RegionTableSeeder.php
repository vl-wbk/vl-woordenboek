<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Region;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use JsonException;

/**
 * The RegionTableSeeder class is responsible for populating the 'regions' table in the database.
 * It reads region data from a JSON file and creates corresponding `Region` model instances.
 *
 * This seeder is typically run during the initial setup of the application's database or when refreshing the development environment.
 * Its primary purpose is to ensure that essential geographical region data is readily available for other components of the application that rely on such information,
 * such as address management, localized content display, or geographical filtering functionalities.
 * This automated approach helps maintain data consistency and reduces manual data entry errors.
 *
 * @see Region  - The Eloquent model representing a region in the database.
 * @see Seeder  - The base class for all seeders in Laravel.
 */
final class RegionTableSeeder extends Seeder
{
    /**
     * Executes the seeding operation for the regions table.
     *
     * This method orchestrates the entire process of populating the `regions` table.
     * It begins by retrieving the complete content of the `regions.json` file, which is expected to be located within the `database/data` directory of the application.
     * Once the JSON content is retrieved, it is then decoded into a standard PHP object, making the structured region data accessible programmatically.
     * The method then iterates over each individual region object found within this decoded data.
     * For every region object encountered during this iteration, a new instance of the `Region` Eloquent model is created.
     * The `id` and `name` properties from the current JSON object are directly used to populate the respective columns in the 'regions' database table.
     * This comprehensive process guarantees that the region data is accurately and efficiently inserted into the database, providing  a robust foundation for location-based features within the application.
     *
     * @return void This method does not return any value. Its side effect is the creation of records in the 'regions' database table.
     *
     * @throws FileNotFoundException
     * @throws JsonException
     */
    public function run(): void
    {
        $datafile = database_path('data/regions.json');

        /** @var array<int, object{id: int, name: string}> $regions */
        $regions = json_decode(File::get($datafile), false, 512, JSON_THROW_ON_ERROR);

        foreach ($regions as $value) {
            Region::create(attributes: [
                'id' => $value->id, 'name' => $value->name,
            ]);
        }
    }
}
