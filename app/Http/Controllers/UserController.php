<?php

namespace App\Http\Controllers;

use App\Kecamatan;
use App\KotaKab;
use App\Provinsi;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except('show');
        $this->middleware('admin.only')->except('show','gantiPassword','gantiPasswordAct');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $dataUser = User::all();
        return view('user.index')->with(compact('dataUser'));
    }

    public function activate(User $user){
        $user->status = User::STATUS_ACTIVE;

        try{
            if($user->save())
                return redirect()->back()->with('success','Berhasil aktivasi akun!');
            else
                return redirect()->back()->withErrors('Gagal aktivasi akun!');
        }
        catch (\Exception $exception){
            return redirect()->back()->withErrors('Gagal aktivasi akun!');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show($username)
    {
        $user = User::whereUsername($username)->first();

        if (empty($user) || !$user->exists)
            return redirect()->back()->withErrors(['User tidak ditemukan!']);
        if ($user->tipe_akun == User::TYPE_PEMINJAM && (\Auth::guest() || !(\Auth::user()->tipe_akun == User::TYPE_PENYEDIA || \Auth::user()->tipe_akun == User::TYPE_ADMIN || \Auth::user()->id == $user->id)))
            return redirect()->back()->withErrors(['Unauthorized page!']);
        if ($user->tipe_akun==User::TYPE_ADMIN && (\Auth::guest() || \Auth::user()->tipe_akun != User::TYPE_ADMIN))
            return redirect()->back()->withErrors(['Unauthorized page!']);

        return view('user.show')->with(compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $provinsi = Provinsi::all();
        $idkota = $user->kecamatan->kotakab->id;
        $idprovinsi = $user->kecamatan->provinsi->id;

        $kota = KotaKab::whereIdProvinsi($idprovinsi)->get();
        $kecamatan = Kecamatan::whereIdKota($idkota)->get();

        $provinsi = Provinsi::all();
        return view('user.edit')->with(compact('user','provinsi','kota','kecamatan','idkota','idprovinsi'));
    }

    public function gantiPassword(){
        $user = \Auth::user();
        return view('user.change_password')->with(compact('user'));
    }

    public function gantiPasswordAct(Request $request){
        $user = \Auth::user();

        $this->validate($request, [
            'oldpassword' => ['required','string','min:6'],
            'password' => ['required','string', 'min:6', 'confirmed'],
        ]);

        if (!Hash::check($request->input('oldpassword'),$user->password))
            return redirect()->back()->withErrors(['Password lama tidak cocok!']);

        $user->password = bcrypt($request->input('password'));

        try{
            if ($user->save())
                return redirect(route('dashboard.index'))->with('success','Password berhasil diganti!');
            return redirect()->back()->withErrors(['Gagal mengganti password']);
        }catch (\Exception $exception){
            return redirect()->back()->withErrors(['Gagal mengganti password']);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\User $user
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, User $user)
    {
        $this->validate($request, [
            'tipe_akun' => ['bail','required',Rule::in([User::TYPE_PEMINJAM,User::TYPE_PENYEDIA,User::TYPE_ADMIN])],
            'status' => ['bail','required', Rule::in([User::STATUS_BANNED,User::STATUS_NONACTIVE,User::STATUS_ACTIVE])],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$user->id.',id'],
            'password' => ['nullable', 'string', 'min:6', 'confirmed'],
            'username' => ['required', 'string', 'min:4', 'max:255', 'unique:users,username,'.$user->id.',id'],
            'no_identitas' => ['required_if:tipe_akun,'.User::TYPE_PEMINJAM, 'nullable', 'string', 'min:16', 'max:16'],
            'no_hp' => ['required', 'numeric', 'digits_between:10,16'],
            'alamat' => ['required', 'string', 'min:4', 'max:255'],
            'provinsi' => ['required', 'exists:provinsi,id'],
            'kota' => ['required', 'exists:kota_kab,id'],
            'kecamatan' => ['required', 'exists:kecamatan,id'],
        ]);

        $user->tipe_akun = $request->input('tipe_akun');
        $user->status = $request->input('status');
        $user->nama = $request->input('name');
        $user->email = $request->input('email');
        $user->username = $request->input('username');
        $user->no_identitas = $request->input('no_identitas');
        $user->nohp = $request->input('no_hp');
        $user->alamat_jalan = $request->input('alamat');
        $user->alamat_kecamatan = $request->input('kecamatan');
        if (!empty($request->input('password')))
            $user->password = bcrypt($request->input('password'));

        try{
            if($user->save())
                return redirect(route('user.index'))->with('success','User berhasil diperbarui!');
            return redirect()->back()->withErrors(['Gagal memperbarui user!']);
        }catch (\Exception $exception){
            return redirect()->back()->withErrors(['Gagal memperbarui user!'.$exception]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        if ($user->tipe_akun == User::TYPE_ADMIN)
            return redirect(route('user.index'))->with('User tidak bisa dihapus/banned');

        $user->status = User::STATUS_BANNED;

        try{
            if ($user->save())
                return redirect(route('user.index'))->with('success','User berhasil dihapus/banned!');
            return redirect()->back()->withErrors(['Gagal menghapus user!']);
        } catch (\Exception $exception){
            return redirect()->back()->withErrors(['Gagal menghapus user!']);
        }
    }
}
