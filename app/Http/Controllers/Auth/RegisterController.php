<?php

namespace App\Http\Controllers\Auth;

use App\Kecamatan;
use App\KotaKab;
use App\Provinsi;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showRegistrationFormPenyedia()
    {
        $provinsi = Provinsi::all();
        $kota = old('provinsi')? KotaKab::whereIdProvinsi(old('provinsi'))->get() : [];
        $kecamatan = old('kota')? Kecamatan::whereIdKota(old('kota'))->get() : [];
        return view('auth.register_penyedia')->with(compact('provinsi','kota','kecamatan'));
    }

    public function showRegistrationFormPeminjam()
    {
        $provinsi = Provinsi::all();
        $kota = old('provinsi')? KotaKab::whereIdProvinsi(old('provinsi'))->get() : [];
        $kecamatan = old('kota')? Kecamatan::whereIdKota(old('kota'))->get() : [];
        return view('auth.register_peminjam')->with(compact('provinsi','kota','kecamatan'));
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        event(new Registered($user = $this->create($request->all())));

        if ($user->status == User::STATUS_ACTIVE)
            $this->guard()->login($user);

        return $this->registered($request, $user)
            ?: redirect($this->redirectPath());
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'tipe_akun' => ['bail','required'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'username' => ['required', 'string', 'min:4', 'max:255', 'unique:users,username'],
            'no_identitas' => ['required_if:tipe_akun,'.User::TYPE_PEMINJAM, 'string', 'min:16', 'max:16'],
            'no_hp' => ['required', 'numeric', 'digits_between:10,16'],
            'alamat' => ['required', 'string', 'min:4', 'max:255'],
            'provinsi' => ['required', 'exists:provinsi,id'],
            'kota' => ['required', 'exists:kota_kab,id'],
            'kecamatan' => ['required', 'exists:kecamatan,id'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $status = User::STATUS_NONACTIVE;
        if ($data['tipe_akun']!= User::TYPE_PENYEDIA) {
            $status = User::STATUS_ACTIVE;
            $data['tipe_akun'] = User::TYPE_PEMINJAM;
        }
        else
            $data['no_identitas'] = null;

        return User::create([
            'nama' => $data['name'],
            'email' => $data['email'],
            'username' => $data['username'],
            'no_identitas' => $data['no_identitas'],
            'tipe_akun' => $data['tipe_akun'],
            'nohp' => $data['no_hp'],
            'alamat_jalan' => $data['alamat'],
            'alamat_kecamatan' => $data['kecamatan'],
            'status' => $status,
            'password' => Hash::make($data['password']),
        ]);
    }
}
