<?php

namespace Database\Seeders;


use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       DB::table('installment')->insert([
        ['id'=>1, 'brand_id'=>1, 'cars'=>'Toyota FT 86', 'description'=>'Toyota FT 86 car is the best','price'=>900000000],
        ['id'=>2, 'brand_id'=>3, 'cars'=>'BMW X5', 'description'=>'SUV Premium', 'price'=>1800000000],
       ]);
       DB::table('available_month')->insert([
        ['installment_id'=>1, 'month'=>12, 'description'=>'12 months','nominal'=> 900000000 / 12],
        ['installment_id'=>1, 'month'=>24, 'description'=>'24 months', 'nominal'=> 900000000 / 24],
        ['installment_id'=>2, 'month'=>12, 'description'=>'12 months', 'nominal'=> 1800000000 / 12],
        ['installment_id'=>2, 'month'=>36, 'description'=>'36 months', 'nominal'=> 1800000000 / 36],
       ]);
    }
}
