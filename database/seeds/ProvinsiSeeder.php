<?php

use Illuminate\Database\Seeder;

class ProvinsiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try {
            \Illuminate\Support\Facades\DB::beginTransaction();

            $files = \Illuminate\Support\Facades\Storage::allFiles('administratif');
            foreach ($files as $file){
                if (!preg_match('/administratif-/i',$file))
                    continue;
                $json = \Illuminate\Support\Facades\Storage::get($file);
                $json = json_decode($json,true);
                foreach ($json as $item)
                    if($item['type']=='provinsi')
                        \App\Provinsi::create(['id'=>$item['code'],'nama'=>$item['name']]);
            }
            \Illuminate\Support\Facades\DB::commit();
        } catch (Exception $e) {
            echo "ERROR\n".$e->getMessage();
        }
    }
}
