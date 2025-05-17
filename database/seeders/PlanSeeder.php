<?php

namespace Database\Seeders;

use App;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if(App::environment(['local', 'staging'])){
            $isTest = true;
        } else {
            $isTest = false;
        }

        $now = Carbon::now()->format('Y-m-d H:i:s');

        DB::table('plans')->insert([
            [
                'id' => 1,
                'type' => 'RECURRING',
                'name' => 'Monthly Plan',
                'price' => 5.00,
                'interval' => 'EVERY_30_DAYS',
                'capped_amount' => 5.00,
                'terms' => 'Monthly subscription at $5.',
                'trial_days' => 7,
                'test' => $isTest,
                'on_install' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 2,
                'type' => 'RECURRING',
                'name' => 'Quarterly Plan',
                'price' => 15.00,
                'interval' => 'EVERY_90_DAYS', // Shopify supports EVERY_30_DAYS, 60, 90
                'capped_amount' => 15.00,
                'terms' => 'Quarterly subscription at $15.',
                'trial_days' => 7,
                'test' => $isTest,
                'on_install' => 0,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 3,
                'type' => 'RECURRING',
                'name' => 'Semi-Annual Plan',
                'price' => 25.00,
                'interval' => 'EVERY_180_DAYS',
                'capped_amount' => 25.00,
                'terms' => '6-month subscription at $25.',
                'trial_days' => 7,
                'test' => $isTest,
                'on_install' => 0,
                'created_at' => $now,
                'updated_at' => $now,
            ]
        ]);
    }
}
