<?php

namespace Database\Factories;

use App\Models\DocumentType;
use Illuminate\Database\Eloquent\Factories\Factory;

class DocumentTypeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = DocumentType::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $name = $this->faker->words(rand(2, 5), true);

        return [
            'name' => $name,
            'code' => snake_case($name),
            'description' => $this->faker->boolean() ? $this->faker->sentences(rand(3, 6), true) : null,
            'fields' => $this->faker->words()
        ];
    }
}
