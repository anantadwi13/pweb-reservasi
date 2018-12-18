<?php

namespace App\Http\Controllers;

use App\Kecamatan;
use App\KotaKab;
use App\Reservasi;
use Illuminate\Http\Request;

class API extends Controller
{
    public function getDistrict(Request $request){
        $data['success'] = false;

        try {
            $kota = [];
            $kec = [];
            if(isset($request->a) && !empty($request->a))
                $kota = KotaKab::whereIdProvinsi($request->a)->get(['id', 'nama', 'kode_kota as kode']);
            $data['kota'] = $kota;

            if(isset($request->b) && !empty($request->b))
                $kec = Kecamatan::whereIdKota($request->b)->get(['id', 'nama', 'kode_kecamatan as kode']);
            $data['kec'] = $kec;
            $data['success'] = true;
        }
        catch (\Exception $exception)
        {
            return $exception->getMessage();
        }

        return $data;
    }
    public function getReservasi(Request $request){
        $ruangan = $request->input('ruangan');
        $date_start = $request->input('start');
        $date_end = $request->input('end');

        $reservasi = Reservasi::whereIdRuangan($ruangan)->whereBetween('time_start',[$date_start, $date_end])->get();

        if (count($reservasi)>0) {
            $data['success'] = true;

            $dataReservasi = [];
            foreach ($reservasi as $item) {
                $dataReservasi[] = [
                    'url' => route('reservasi.show',$item),
                    'title' => $item->nama_acara,
                    'start' => $item->time_start,
                    'end' => $item->time_end
                ];
            }
            return $dataReservasi;
        }

        return [];

        //$reservasi = Reservasi::where
    }
}
