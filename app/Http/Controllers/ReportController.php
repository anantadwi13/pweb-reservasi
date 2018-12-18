<?php

namespace App\Http\Controllers;

use App\Report;
use App\User;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin.only')->except(['create','store']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $report = Report::all();
        return view('report.index')->with(compact('report'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($username)
    {
        $user = User::whereUsername($username)->first();

        if (empty($user) || !$user->exists)
            return redirect()->back()->withErrors(['User tidak ditemukan!']);
        if (\Auth::user()->tipe_akun == $user->tipe_akun)
            return redirect(route('dashboard.index'))->withErrors(['Unauthorized page!']);
        if ($user->id == \Auth::user()->id)
            return redirect(route('dashboard.index'))->withErrors(['Unauthorized page!']);
        if ($user->tipe_akun == User::TYPE_ADMIN)
            return redirect(route('dashboard.index'))->withErrors(['Unauthorized page!']);

        return view('report.create')->with(compact('user'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store($username, Request $request)
    {
        $this->validate($request, [
            'subject' => ['required','string', 'max:30'],
            'isi' => ['required','string'],
        ]);
        $user = User::whereUsername($username)->first();


        if (empty($user) || !$user->exists)
            return redirect()->back()->withErrors(['User tidak ditemukan!']);
        if (\Auth::user()->tipe_akun == $user->tipe_akun)
            return redirect(route('dashboard.index'))->withErrors(['Unauthorized page!']);
        if ($user->id == \Auth::user()->id)
            return redirect(route('dashboard.index'))->withErrors(['Unauthorized page!']);
        if ($user->tipe_akun == User::TYPE_ADMIN)
            return redirect(route('dashboard.index'))->withErrors(['Unauthorized page!']);


        $report = new Report();
        $report->id_pelapor = \Auth::user()->id;
        $report->id_dilapor = $user->id;
        $report->subject = $request->subject;
        $report->isi = $request->isi;
        $report->status = Report::STATUS_UNREAD;
        try {
            if($report->save())
                return redirect(route('dashboard.index'))->with('success','Berhasil melapor '. $user->nama);
            return redirect()->back()->withErrors(['Gagal mengirim report!']);
        }
        catch (\Exception $exception){
            return redirect()->back()->withErrors(['Gagal mengirim report!']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Report  $report
     * @return \Illuminate\Http\Response
     */
    public function show(Report $report)
    {
        $user = \Auth::user();
        $report->status = Report::STATUS_READ;
        $report->update();
        return view('report.show')->with(compact('report','user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Report  $report
     * @return \Illuminate\Http\Response
     */
    public function edit(Report $report)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Report  $report
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Report $report)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Report  $report
     * @return \Illuminate\Http\Response
     */
    public function destroy(Report $report)
    {
        //
    }
}
