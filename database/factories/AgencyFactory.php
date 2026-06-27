<?php

namespace Database\Factories;

use App\Models\Agency;
use App\Models\MasterLocation;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Agency>
 */
class AgencyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->company() . ' Security Agency';
        return [
            'owner_id' => User::factory(),
            'location_id' => MasterLocation::inRandomOrder()->first()?->id,
            'name' => $name,
            'slug' => Str::slug($name),
            'license_number' => 'PSA-' . fake()->year() . '-' . fake()->unique()->numerify('####'),
            'email' => fake()->unique()->safeEmail(),
            'phone' => '09' . fake('en_PH')->numerify('#########'),
            'city' => fake('en_PH')->city(),
            'province' => fake('en_PH')->province(),
            'country' => 'Philippines',
            'is_verified' => fake()->boolean(80),
            'is_active' => true,
        ];
    }
}
