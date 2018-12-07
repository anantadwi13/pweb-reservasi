<?php

use Illuminate\Database\Seeder;

class KecamatanSeeder extends Seeder
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
                    if($item['type']=='kecamatan') {
                        $item['id_provinsi'] = explode('.',$item['code'])[0];
                        $item['id_kota'] = explode('.',$item['code'])[1];
                        $item['code'] = explode('.',$item['code'])[2];
                        $idkota = \App\KotaKab::where([["kode_kota",'=',$item["id_kota"]],["id_provinsi",'=',$item["id_provinsi"]]])->first()->id;
                        \App\Kecamatan::create(['kode_kecamatan'=>$item['code'],'id_kota'=>$idkota,'nama'=>$item['name']]);
                    }
            }

            \Illuminate\Support\Facades\DB::commit();
        } catch (Exception $e) {
            echo "ERROR\n" . $e->getMessage();
        }
    }
}
