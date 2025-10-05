<?php

namespace Database\Factories;

use App\Models\Disclaimer;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class DisclaimerFactory extends Factory{
    protected $model = Disclaimer::class;

    public function definition(): array
    {
        return [
            'type' => $this->faker->word(),//
'name' => $this->faker->name(),
'message' => $this->faker->word(),
'usage' => $this->faker->word(),
'description' => $this->faker->text(),
'created_at' => Carbon::now(),
'updated_at' => Carbon::now(),
        ];
    }
}
