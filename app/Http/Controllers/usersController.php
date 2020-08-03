<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class usersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $results = DB::table('users')->select('username', 'email', 'alamat', 'nohp', 'nama_lengkap', 'kelamin')->get();
        } catch (\Throwable $err) {
            $results = ['message' => 'Error, Ada kesalahan', 'err' => $err];
        }
        return response($results);
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
        if (isset($_SESSION['userdata']))
            return response('Anda tidak diperbolehkan mengakses ini, silahkan logout dulu', 401);

        $users = [
            'id' => Str::random(5),
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'kelamin' => $request->kelamin,
            'alamat' => $request->alamat
        ];
        if (isset($request->nohp))
            $users['nohp'] = $request->nohp;
        if (isset($request->nama))
            $users['nama_lengkap'] = $request->nama;

        try {
            DB::table('users')->insert($users);
            return response(['message' => 'Berhasil register']);
        } catch (\Throwable $th) {
            $results = ['message' => 'Error, Ada kesalahan', 'err' => $th];
            return response($results);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (!isset($_SESSION['userdata']))
            return response('Anda tidak diperbolehkan mengakses ini, silahkan login dulu', 401);
        if ($_SESSION['userdata']['username'] != $id)
            return response('Anda tidak diperbolehkan mengubah akun orang lain', 401);
        $users = [];
        if (isset($request->kelamin))
            $users['kelamin'] = $request->kelamin;
        if (isset($request->username))
            $users['username'] = $request->username;
        if (isset($request->email))
            $users['email'] = $request->email;
        if (isset($request->password))
            $users['password'] = Hash::make($request->password);
        if (isset($request->nohp))
            $users['nohp'] = $request->nohp;
        if (isset($request->nama))
            $users['nama_lengkap'] = $request->nama;
        if (isset($request->alamat))
            $users['alamat'] = $request->alamat;
        if (count($users) == 0)
            return response(['message' => 'Tidak ada perubahan']);
        try {
            DB::table('users')->where('id', $id)->update($users);
        } catch (\Throwable $th) {
            $results = ['message' => 'Error, Ada kesalahan', 'err' => $th];
            return response($results);
        }
    }

    function login(Request $request)
    {
        if (isset($_SESSION['userdata']))
            return response('Anda tidak diperbolehkan mengakses ini, silahkan logout dulu', 401);
        if (!isset($request->user))
            return response(['message' => 'Username atau Email tidak dimasukkan']);
        if (!isset($request->password))
            return response(['message' => 'Tidak ada password yang dimasukkan']);

        $res = DB::table('users')->where('username', $request->user)->orWhere('email', $request->user)->get();

        if (count($res) == 0)
            return response(['message' => 'User tidak ditemukan']);
        elseif (count($res) > 1)
            return response(['message' => 'Error, cek lagi email atau username yang terdaftar']);

        if (Hash::check($request->password, $res[0]['password'])) {
            $_SESSION['userdata'] = $res[0];
            return response(['message' => 'Login suksess']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!isset($_SESSION['userdata']))
            return response('Anda tidak diperbolehkan mengakses ini, silahkan login dulu', 401);
        if ($_SESSION['userdata']['username'] != $id)
            return response('Anda tidak boleh menghapus akun orang lain', 401);

        try {
            DB::table('users')->where('id', $id)->delete();
        } catch (\Throwable $th) {
            $results = ['message' => 'Error, Ada kesalahan', 'err' => $th];
            return response($results);
        }
    }
    function logout()
    {
        unset($_SESSION['userdata']);
        return response(['message' => 'Anda logout']);
    }
}
