<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\OrderStatus;

class OrderStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $status = [
            [
               'status' => 'CANCELED',
            ],
            [
               'status' => 'CONFIRMED',
            ],
            [
               'status' => 'DELIVERED',
            ],
            [
               'status' => 'PENDING',
            ],
        ];

        OrderStatus::insert($status);
    }
}
