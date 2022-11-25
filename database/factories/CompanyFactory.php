<?php

namespace Database\Factories;

use App\Enums\CompanyEmployeeCount;
use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Company>
 */
class CompanyFactory extends Factory
{
    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure(): static
    {
        return $this->afterCreating(function (Company $company) {
            $company->companyUsers()->create([
                'user_id' => $company->owner_id,
            ]);
        });
    }

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $employeeCountCases = CompanyEmployeeCount::cases();

        return [
            'name' => $this->faker->company(),
            'slug' => $this->faker->unique()->slug,
            'description' => Str::limit($this->faker->paragraph, 255),
            'founded_in' => $this->faker->year(),
            'country' => $this->faker->countryCode(),
            'website' => "https://{$this->faker->domainName()}",
            'employee_count' => $employeeCountCases[array_rand($employeeCountCases)]->value,
            'owner_id' => User::all()->random()->getKey(),
        ];
    }
}
