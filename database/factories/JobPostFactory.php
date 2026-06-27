<?php

namespace Database\Factories;

use App\Models\Agency;
use App\Models\JobPost;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * @extends Factory<JobPost>
 */
class JobPostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $titles = [
            'Security Guard',
            'Security Officer',
            'Lady Guard',
            'Executive Protection Specialist',
            'CCTV Operator',
            'Bank Guard',
            'VIP Security',
            'Security Supervisor',
            'Loss Prevention Officer',
            'Event Security',
        ];

        $title = fake()->randomElement($titles);
        $publishedAt = fake()->dateTimeBetween('-1 month', 'now');

        return [
            'agency_id' => Agency::factory(),
            'title' => $title,
            'slug' => Str::slug($title) . '-' . Str::random(5),
            'is_featured' => fake()->boolean(10),
            'employment_type_id' => DB::table('master_employment_types')->inRandomOrder()->first()?->id,
            'work_location_type_id' => DB::table('master_work_location_types')->inRandomOrder()->first()?->id,
            'city' => fake('en_PH')->city(),
            'province' => fake('en_PH')->province(),
            'country' => 'Philippines',
            'salary_min' => fake()->numberBetween(15000, 25000),
            'salary_max' => fake()->numberBetween(26000, 40000),
            'salary_type_id' => DB::table('master_salary_types')->inRandomOrder()->first()?->id,
            'description' => fake()->paragraphs(3, true),
            'requirements' => fake()->paragraphs(2, true),
            'benefits' => fake()->paragraphs(2, true),
            'vacancies' => fake()->numberBetween(1, 20),
            'job_status_id' => DB::table('master_job_statuses')->where('code', 'active')->first()?->id ?? DB::table('master_job_statuses')->inRandomOrder()->first()?->id,
            'published_at' => $publishedAt,
            'expires_at' => (clone $publishedAt)->modify('+1 month'),
        ];
    }
}
