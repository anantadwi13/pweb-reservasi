<?php

namespace App\Http\Controllers;

use App\Report;
use Illuminate\Http\Request;
use App\User;

class ReportController extends Controller
{
    public function __construct()
    {
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
        // return $username;
        $user = User::whereUsername($username)->first();
        if($user->id == \Auth::user()->id)
            return redirect()->back()->withErrors(['Unauthorized page!']);
        return view('report.create')->with(compact('user'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($username, Request $request)
    {
        $this->validate($request, [
            'subject' => ['required','string', 'max:30'],
            'isi' => ['required','string'],
        ]);
        $user = User::whereUsername($username)->first();
        $report = new Report();
        $report->id_pelapor = \Auth::user()->id;
        $report->id_dilapor = $user->id;
        $report->subject = $request->subject;
        $report->isi = $request->isi;
        $report->status = Report::STATUS_UNREAD;
        $report->save();
        return redirect(route('reservasi.index'));
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
