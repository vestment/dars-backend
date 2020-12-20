<?php

use Illuminate\Database\Seeder;
use App\Models\Package ; 
class PackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Package::create([
            'id' => 4 , 
            'time' => 12 , 
            'value' => "free" , 
            'description' =>"سنوية" , 
            'name' =>"سنوية" , 
            'features' => '["\u0627\u0644\u0627\u062e\u062a\u0628\u0627\u0631\u0627\u062a","\u0627\u0644\u0645\u0648\u0627\u062f"]' , 
            'enabled' => 1 , 

        ]);
    }
}
