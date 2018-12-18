<?php

namespace App\Http\Controllers;

use App\Kategori;
use App\Kecamatan;
use App\KotaKab;
use App\Provinsi;
use App\Ruangan;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RuanganController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index','show']);
        $this->middleware('penyedia.ruangan.only')->except(['index','show']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (\Auth::check() && \Auth::user()->tipe_akun == User::TYPE_PENYEDIA)
            $dataRuangan = Ruangan::whereIdUser(\Auth::user()->id)->get();
        else
            $dataRuangan = Ruangan::all();

        return view('ruangan.index')->with(compact('dataRuangan'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $kategori = Kategori::all();

        $provinsi = Provinsi::all();
        $kota = old('provinsi')? KotaKab::whereIdProvinsi(old('provinsi'))->get() : [];
        $kecamatan = old('kota')? Kecamatan::whereIdKota(old('kota'))->get() : [];

        return view('ruangan.create')->with(compact('kategori','provinsi','kota','kecamatan'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'nama' => 'required|string',
            'kode' => 'nullable',
            'kategori' => 'required|exists:kategori,id',
            'alamat' => 'required_with:provinsi,kota,kecamatan|nullable|string',
            'provinsi' => 'required_with:alamat|nullable|exists:provinsi,id',
            'kota' => 'required_with:alamat|nullable|exists:kota_kab,id',
            'kecamatan' => 'required_with:alamat|nullable|exists:kecamatan,id',
        ],[
            'nama.required' => 'Nama harus diisi!',
            'kategori.required' => 'Kategori harus diisi!',
            'kategori.exists' => 'Kategori tidak sesuai!',
            'alamat.required_with' => 'Alamat harus diisi ketika provinsi/kota/kecamatan terisi!',
            'provinsi.required_with' => 'Provinsi harus diisi ketika alamat terisi!',
            'provinsi.exists' => 'Provinsi tidak sesuai!',
            'kota.required_with' => 'Kota harus diisi ketika alamat terisi!',
            'kota.exists' => 'Kota tidak sesuai!',
            'kecamatan.required_with' => 'Kecamatan harus diisi ketika alamat terisi!',
            'kecamatan.exists' => 'Kecamatan tidak sesuai!',
        ]);

        $ruangan = new Ruangan();
        $ruangan->nama = $request->input('nama');
        $ruangan->kode = $request->input('kode');
        $ruangan->id_kategori = $request->input('kategori');
        $ruangan->id_user = \Auth::user()->id;
        $ruangan->alamat_jalan = $request->input('alamat');
        $ruangan->alamat_kecamatan = $request->input('kecamatan');
        $ruangan->status = Ruangan::STATUS_AVAILABLE;

        try{
            if ($ruangan->save())
                return redirect(route("ruangan.index"))->with('success','Ruangan berhasil dimasukkan!');
            return redirect()->back()->withErrors(['Gagal menyimpan!']);
        }
        catch (\Exception $exception){
            return redirect()->back()->withErrors(['Gagal menyimpan!']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Ruangan  $ruangan
     * @return \Illuminate\Http\Response
     */
    public function show(Ruangan $ruangan)
    {
        if (empty($ruangan) || !$ruangan->exists)
            return redirect()->back()->withErrors(['Ruangan tidak ditemukan!']);

        return view('ruangan.show')->with(compact('ruangan'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Ruangan  $ruangan
     * @return \Illuminate\Http\Response
     */
    public function edit(Ruangan $ruangan)
    {
        if (empty($ruangan) || !$ruangan->exists)
            return redirect()->back()->withErrors(['Ruangan tidak ditemukan!']);

        if ($ruangan->user->id == \Auth::user()->id || \Auth::user()->tipe_akun == User::TYPE_ADMIN) {
            $kategori = Kategori::all();
            $provinsi = Provinsi::all();
            $idkota = $ruangan->kecamatan?$ruangan->kecamatan->kotakab->id:null;
            $idprovinsi = $ruangan->kecamatan?$ruangan->kecamatan->provinsi->id:null;
            $kota = $ruangan->kecamatan?KotaKab::whereIdProvinsi($idprovinsi)->get():[];
            $kecamatan = $ruangan->kecamatan?Kecamatan::whereIdKota($idkota)->get():[];

            return view('ruangan.edit')->with(compact('ruangan', 'kategori', 'provinsi', 'kota', 'kecamatan', 'idkota', 'idprovinsi'));
        }
        else
            return redirect()->back()->withErrors(['Unauthorized page!']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Ruangan $ruangan
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, Ruangan $ruangan)
    {
        if (empty($ruangan) || !$ruangan->exists)
            return redirect()->back()->withErrors(['Ruangan tidak ditemukan!']);

        if ($ruangan->user->id == \Auth::user()->id || \Auth::user()->tipe_akun == User::TYPE_ADMIN) {
            $this->validate($request, [
                'nama' => 'required|string',
                'kode' => 'nullable',
                'status' => 'required|boolean',
                'kategori' => 'required|exists:kategori,id',
                'alamat' => 'required_with:provinsi,kota,kecamatan|nullable|string',
                'provinsi' => 'required_with:alamat|nullable|exists:provinsi,id',
                'kota' => 'required_with:alamat|nullable|exists:kota_kab,id',
                'kecamatan' => 'required_with:alamat|nullable|exists:kecamatan,id',
            ], [
                'nama.required' => 'Nama harus diisi!',
                'status.required' => 'Status harus diisi!',
                'status.boolean' => 'Status tidak valid!',
                'kategori.required' => 'Kategori harus diisi!',
                'kategori.exists' => 'Kategori tidak sesuai!',
                'alamat.required_with' => 'Alamat harus diisi ketika provinsi/kota/kecamatan terisi!',
                'provinsi.required_with' => 'Provinsi harus diisi ketika alamat terisi!',
                'provinsi.exists' => 'Provinsi tidak sesuai!',
                'kota.required_with' => 'Kota harus diisi ketika alamat terisi!',
                'kota.exists' => 'Kota tidak sesuai!',
                'kecamatan.required_with' => 'Kecamatan harus diisi ketika alamat terisi!',
                'kecamatan.exists' => 'Kecamatan tidak sesuai!',
            ]);

            $ruangan->nama = $request->input('nama');
            $ruangan->kode = $request->input('kode');
            $ruangan->id_kategori = $request->input('kategori');
            $ruangan->alamat_jalan = $request->input('alamat');
            $ruangan->alamat_kecamatan = $request->input('kecamatan');
            $ruangan->status = $request->input('status');

            try {
                if ($ruangan->save())
                    return redirect(route("ruangan.index"))->with('success', 'Ruangan berhasil diupdate!');
                return redirect()->back()->withErrors(['Gagal menyimpan!']);
            } catch (\Exception $exception) {
                return redirect()->back()->withErrors(['Gagal menyimpan!']);
            }
        }
        return redirect()->back()->withErrors(['Unauthorized page!']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Ruangan  $ruangan
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ruangan $ruangan)
    {
        if (empty($ruangan) || !$ruangan->exists)
            return redirect()->back()->withErrors(['Ruangan tidak ditemukan!']);

        if ($ruangan->user->id == \Auth::user()->id || \Auth::user()->tipe_akun == User::TYPE_ADMIN) {
            try{
                DB::beginTransaction();
                if ($ruangan->reservasi()->count()>0)
                    $ruangan->reservasi()->delete();
                if ($ruangan->delete()) {
                    DB::commit();
                    return redirect(route('ruangan.index'))->with('success', 'Ruangan berhasil dihapus!');
                }
                return redirect()->back()->withErrors(['Gagal menghapus!']);
            } catch (\Exception $exception){
                return redirect()->back()->withErrors(['Gagal menghapus!']);
            }
        }
        return redirect()->back()->withErrors(['Unauthorized page!']);
    }
}
