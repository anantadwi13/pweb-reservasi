<?php

namespace App\Http\Controllers;

use App\Kategori;
use App\User;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin.only')->except(['index','show']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (\Auth::check() && \Auth::user()->tipe_akun == User::TYPE_PENYEDIA)
            return redirect(route('dashboard.index'))->withErrors(['Unauthorized page!']);
        $dataKategori = Kategori::all();
        return view('kategori.index')->with(compact('dataKategori'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('kategori.create');
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
            'kategori' => 'required'
        ],[
            'kategori.required' => 'Kategori harus diisi'
        ]);

        $kategori = new Kategori();
        $kategori->nama = $request->input('kategori');


        try{
            if($kategori->save())
                return redirect(route("kategori.index"))->with('success', 'Kategori berhasil dimasukkan!');
            else
                return redirect()->back()->withErrors(['Gagal menyimpan!']);
        }catch (\Exception $e){
            return redirect()->back()->withErrors(['Gagal menyimpan!']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Kategori  $kategori
     * @return \Illuminate\Http\Response
     */
    public function show(Kategori $kategori)
    {
        if (\Auth::check() && \Auth::user()->tipe_akun == User::TYPE_PENYEDIA)
            return redirect(route('dashboard.index'))->withErrors(['Unauthorized page!']);
        return view('kategori.show')->with(compact('kategori'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Kategori  $kategori
     * @return \Illuminate\Http\Response
     */
    public function edit(Kategori $kategori)
    {
        if (empty($kategori) || !$kategori->exists)
            return redirect()->back()->withErrors(['Kategori tidak ditemukan!']);

        return view('kategori.edit')->with(compact('kategori'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Kategori $kategori
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, Kategori $kategori)
    {
        if (empty($kategori) || !$kategori->exists)
            return redirect()->back()->withErrors(['Kategori tidak ditemukan!']);

        $this->validate($request,[
            'kategori' => 'required'
        ],[
            'kategori.required' => 'Kategori harus diisi'
        ]);

        $kategori->nama = $request->input('kategori');
        try{
            if($kategori->save())
                return redirect(route("kategori.index"))->with('success', 'Kategori berhasil diupdate!');
            else
                return redirect()->back()->withErrors(['Gagal menyimpan!']);
        }catch (\Exception $e){
            return redirect()->back()->withErrors(['Gagal menyimpan!']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Kategori  $kategori
     * @return \Illuminate\Http\Response
     */
    public function destroy(Kategori $kategori)
    {
        if (empty($kategori) || !$kategori->exists)
            return redirect()->back()->withErrors(['Kategori tidak ditemukan!']);

        if ($kategori->ruangan()->count()>0)
            return redirect()->back()->withErrors(['Gagal menghapus! Kategori masih tercantum di data beasiswa']);

        try {
            if($kategori->delete())
                return redirect()->back()->with('success',"Kategori berhasil dihapus!");
            else
                return redirect()->back()->withErrors(['Gagal menghapus!']);
        }
        catch (\Exception $e){
            return redirect()->back()->withErrors(['Gagal menghapus!']);
        }
    }
}
