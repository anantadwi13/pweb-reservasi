<?php

use Illuminate\Database\Seeder;

class KotaKabSeeder extends Seeder
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
                    if($item['type']=='kabupaten') {
                        $item['id_provinsi'] = explode('.',$item['code'])[0];
                        $item['code'] = explode('.',$item['code'])[1];
                        //$item['name'] = preg_replace('/kab\.\ |adm\.\ |^(kota\ )/i','',$item['name']);
                        \App\KotaKab::create(['kode_kota'=>$item['code'],'id_provinsi'=>$item['id_provinsi'],'nama'=>$item['name']]);
                    }
            }

            \Illuminate\Support\Facades\DB::commit();
        } catch (Exception $e) {
            echo "ERROR\n".$e->getMessage();
        }
    }
}
