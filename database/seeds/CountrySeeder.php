<?php

use Illuminate\Database\Seeder;
use App\Models\Country ; 
class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $egypt = Country::create([
            'en_name' => 'Egypt' ,
            'ar_name' => 'مصر'  , 
            'key' => '+20' , 
        ]);  
        $soudan = Country::create([
            'en_name' => 'Soudan' ,
            'ar_name' => 'السودان'  , 
            'key' => '+249' , 
        ]); 
    }
}
