<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use JsonException;

/**
 * The ArticleCategorySeeder class is a database seeder responsible for populating the 'categories' table.
 * It achieves this by reading category data from a predefined JSON file and subsequently creating corresponding `Category` model instances in the database.
 *
 * This seeder is typically executed during the initial setup of a new application environment or as part of a database refresh operation in development.
 * Its primary goal is to ensure that all necessary article categories are present and readily available within the application's database.
 * This automated seeding process helps maintain data integrity and consistency across different environments, reducing the need for manual data entry for categories.
 *
 * @see Category    - The Eloquent model representing an article category in the database.
 * @see Seeder      - The base class for all database seeders in Laravel.
 *
 * @package Database\Seeders
 */
final class ArticleCategorySeeder extends Seeder
{
    /**
     * Executes the seeding operation for the article categories table.
     *
     * This method orchestrates the complete process of populating the `categories` table.
     * It begins by fetching the entire content of the `categories.json` file, which is expected to reside within the `database/data` directory of the application.
     * Following the retrieval, the JSON content is then decoded into a standard PHP object, making the structured category data easily accessible for processing.
     * The method proceeds to iterate over each individual category object found within this decoded dataset.
     * For every category object encountered during this iteration, a new instance of the `Category` Eloquent model is meticulously created.
     * The attributes for this new model, including `description` and `name`, are directly populated using the corresponding properties from the current JSON object.
     * This comprehensive and automated procedure guarantees that all category data is accurately and efficiently inserted into the database, providing a solid foundation for organizing and managing articles within the application.
     *
     * @return void     This method does not return any value. Its primary effect is the creation of records within the 'categories' database table.
     *
     * @throws FileNotFoundException
     * @throws JsonException
     */
    public function run(): void
    {
        $dataPath = database_path('data/categories.json');

        /** @var array<int, object{name: string, description: string}> $categories */
        $categories = json_decode(File::get($dataPath), false, 512, JSON_THROW_ON_ERROR);

        foreach ($categories as $category) {
            Category::query()->create(attributes: [
                'description' => $category->description,
                'name' => $category->name,
            ]);
        }
    }
}
