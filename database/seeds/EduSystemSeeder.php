<?php

use Illuminate\Database\Seeder;
use App\Models\EduSystem ; 
class EduSystemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        EduSystem::create([
            'ar_name' =>"نظام تعليمي" , 
            'en_name' => 'education system' , 
            'country_id' => 1 , 
            
        ]);
    }
}
