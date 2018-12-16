<?php

namespace App\Http\Controllers;

use App\Reservasi;
use App\Ruangan;
use App\User;
use Illuminate\Http\Request;

class ReservasiController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['show']);
        $this->middleware('penyedia.ruangan.only')->only(['action','edit','update']);
        $this->middleware('peminjam.ruangan.only')->only(['create','store']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (\Auth::user()->tipe_akun == User::TYPE_ADMIN)
            $dataReservasi = Reservasi::all();
        else if (\Auth::user()->tipe_akun == User::TYPE_PENYEDIA) {
            $dataReservasi = [];
            foreach (Ruangan::whereIdUser(\Auth::user()->id)->get() as $item)
                foreach ($item->reservasi as $itemreservasi)
                    $dataReservasi[] = $itemreservasi;
        }
        else
            $dataReservasi = Reservasi::whereIdUser(\Auth::user()->id)->get();
        return view('reservasi.index')->with(compact('dataReservasi'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $id_ruangan = $request->get('ruangan');

        $ruangan = Ruangan::whereStatus(Ruangan::STATUS_AVAILABLE)->get();
        return view('reservasi.create')->with(compact('ruangan','id_ruangan'));
    }

    public function action(Reservasi $reservasi, Request $request){
        $act = $request->post('action');

        if ($act==Reservasi::STATUS_ACCEPTED)
            $reservasi->status = Reservasi::STATUS_ACCEPTED;
        else if ($act == Reservasi::STATUS_REJECTED)
            $reservasi->status = Reservasi::STATUS_REJECTED;

        if ($reservasi->status == Reservasi::STATUS_ACCEPTED && Reservasi::where('id','<>',$reservasi->id)->whereIdRuangan($reservasi->id_ruangan)->whereStatus(Reservasi::STATUS_ACCEPTED)->where(function($query) use ($reservasi) {$query->whereBetween('time_start',[$reservasi->time_start,$reservasi->time_end])->orWhereBetween('time_end',[$reservasi->time_start,$reservasi->time_end]);})->count()>0)
            return redirect()->back()->withErrors(['Ruangan pada jam tersebut telah dipinjam']);

        try{
            if($reservasi->save())
                return redirect()->back()->with('success','Berhasil memperbarui status!');
            else
                return redirect()->back()->withErrors('Gagal memperbarui status!');
        }
        catch (\Exception $exception){
            return redirect()->back()->withErrors('Gagal memperbarui status!');
        }
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
            'ruangan' => 'required|exists:ruangan,id',
            'nama' => 'required|string',
            'deskripsi' => 'nullable',
            'time_start' => 'required|date',
            'time_stop' => 'required|date|after:time_start',
        ],[
            'ruangan.required' => 'Ruangan harus diisi!',
            'ruangan.exists' => 'Ruangan tidak valid!',
            'nama.required' => 'Nama harus diisi!',
            'time_start.required' => 'Waktu acara mulai harus diisi!',
            'time_start.date' => 'Waktu acara mulai tidak valid!',
            'time_stop.required' => 'Waktu acara selesai harus diisi!',
            'time_stop.date' => 'Waktu acara selesai tidak valid!',
            'time_stop.after' => 'Waktu acara tidak valid!',
        ]);
        if (Ruangan::find($request->input('ruangan'))->status != Ruangan::STATUS_AVAILABLE)
            return redirect()->back()->withErrors(['Gagal membuat reservasi! Ruangan sedang dalam perbaikan!']);

        $reservasi = new Reservasi();
        $reservasi->id_ruangan = $request->input('ruangan');
        $reservasi->nama_acara = $request->input('nama');
        $reservasi->deskripsi_acara = $request->input('deskripsi');
        $reservasi->time_start = $request->input('time_start');
        $reservasi->time_end = $request->input('time_stop');
        $reservasi->id_user = \Auth::user()->id;
        $reservasi->status = Reservasi::STATUS_WAITING;

        try{
            if ($reservasi->save())
                return redirect(route('reservasi.index'))->with('success','Reservasi berhasil dibuat! Silakan tunggu konfirmasi penyedia tempat!');
            return redirect()->back()->withErrors(['Gagal membuat reservasi!']);
        }
        catch (\Exception $exception) {
            return redirect()->back()->withErrors(['Gagal membuat reservasi!']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Reservasi  $reservasi
     * @return \Illuminate\Http\Response
     */
    public function show(Reservasi $reservasi)
    {
        return view('reservasi.show')->with(compact('reservasi'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Reservasi  $reservasi
     * @return \Illuminate\Http\Response
     */
    public function edit(Reservasi $reservasi)
    {
        if ($reservasi->ruangan->user->id == \Auth::user()->id || \Auth::user()->tipe_akun == User::TYPE_ADMIN){
            $ruangan = Ruangan::all();

            return view('reservasi.edit')->with(compact('reservasi','ruangan'));
        }

        return redirect()->back()->withErrors(['Unauthorized page!']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Reservasi $reservasi
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, Reservasi $reservasi)
    {
        if ($reservasi->ruangan->user->id == \Auth::user()->id || \Auth::user()->tipe_akun == User::TYPE_ADMIN) {
            $this->validate($request, [
                'ruangan' => 'required|exists:ruangan,id',
                'nama' => 'required|string',
                'deskripsi' => 'nullable',
                'status' => 'required|numeric|min:1|max:2',
                'time_start' => 'required|date',
                'time_stop' => 'required|date|after:time_start',
            ], [
                'ruangan.required' => 'Ruangan harus diisi!',
                'ruangan.exists' => 'Ruangan tidak valid!',
                'nama.required' => 'Nama harus diisi!',
                'status.required' => 'Status harus diisi!',
                'status.numeric' => 'Status tidak valid!',
                'status.min' => 'Status tidak valid!',
                'status.max' => 'Status tidak valid!',
                'time_start.required' => 'Waktu acara mulai harus diisi!',
                'time_start.date' => 'Waktu acara mulai tidak valid!',
                'time_stop.required' => 'Waktu acara selesai harus diisi!',
                'time_stop.date' => 'Waktu acara selesai tidak valid!',
                'time_stop.after' => 'Waktu acara tidak valid!',
            ]);

            $reservasi->id_ruangan = $request->input('ruangan');
            $reservasi->nama_acara = $request->input('nama');
            $reservasi->deskripsi_acara = $request->input('deskripsi');
            $reservasi->time_start = $request->input('time_start');
            $reservasi->time_end = $request->input('time_stop');
            $reservasi->status = $request->input('status');

            if ($reservasi->status == Reservasi::STATUS_ACCEPTED && Reservasi::where('id', '<>', $reservasi->id)->whereIdRuangan($reservasi->id_ruangan)->whereStatus(Reservasi::STATUS_ACCEPTED)->where(function ($query) use ($reservasi) {
                    $query->whereBetween('time_start', [$reservasi->time_start, $reservasi->time_end])->orWhereBetween('time_end', [$reservasi->time_start, $reservasi->time_end]);
                })->count() > 0)
                return redirect()->back()->withErrors(['Ruangan pada jam tersebut telah dipinjam']);

            try {
                if ($reservasi->save())
                    return redirect(route('reservasi.index'))->with('success', 'Reservasi berhasil diperbarui!');
                return redirect()->back()->withErrors(['Gagal membuat reservasi!']);
            } catch (\Exception $exception) {
                return redirect()->back()->withErrors(['Gagal membuat reservasi!']);
            }
        }

        return redirect()->back()->withErrors(['Unauthorized page!']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Reservasi  $reservasi
     * @return \Illuminate\Http\Response
     */
    public function destroy(Reservasi $reservasi)
    {
        if (!$reservasi->exists)
            return redirect()->back()->withErrors(['Reservasi tidak ditemukan!']);

        if ($reservasi->user->id == \Auth::user()->id || \Auth::user()->tipe_akun == User::TYPE_ADMIN) {
            try {
                if ($reservasi->delete())
                    return redirect(route('reservasi.index'))->with('succes', 'Reservasi dibatalkan!');
                return redirect()->back()->withErrors(['Gagal membatalkan reservasi!']);
            } catch (\Exception $exception) {
                return redirect()->back()->withErrors(['Gagal membatalkan reservasi!']);
            }
        }

        return redirect()->back()->withErrors(['Unauthorized page!']);
    }
}
