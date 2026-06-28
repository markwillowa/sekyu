<?php

namespace Database\Seeders;

use App\Models\MasterJobOfferStatus;
use Illuminate\Database\Seeder;

class MasterJobOfferStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = [
            ['code' => 'draft', 'name' => 'Draft', 'sort_order' => 1],
            ['code' => 'sent', 'name' => 'Sent', 'sort_order' => 2],
            ['code' => 'accepted', 'name' => 'Accepted', 'sort_order' => 3],
            ['code' => 'declined', 'name' => 'Declined', 'sort_order' => 4],
            ['code' => 'expired', 'name' => 'Expired', 'sort_order' => 5],
        ];

        foreach ($statuses as $status) {
            MasterJobOfferStatus::updateOrCreate(['code' => $status['code']], $status);
        }
    }
}
