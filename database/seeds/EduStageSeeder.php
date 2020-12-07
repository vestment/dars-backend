<?php

use Illuminate\Database\Seeder;

use App\Models\EduStage ; 
class EduStageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        EduStage::create([
            'ar_name' =>"مرحلة تعليمية" , 
            'en_name' => 'education stage' , 
            'edu_system_id' => 1 ,   
        ]);
    }
}
