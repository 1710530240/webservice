<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class wisataController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return DB::table('ulasan')->select('ulasan.ulasan', 'users.nama_lengkap', 'users.email', 'wisata.*')
            ->join('users', 'users.username', '=', 'ulasan.user')->join('wisata', 'wisata.id', '=', 'ulasan.wisata')->get();
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
        if (!isset($_SESSION['userdata']))
            return response('Anda tidak diperbolehkan mengakses ini, silahkan login dulu', 401);

        $wisata = ['id' => Str::random(5)];
        $wisata['nama'] = $request->nama;
        if (isset($request->lokasi))
            $wisata['lokasi'] = $request->lokasi;
        if (isset($request->kategori))
            $wisata['kategori'] = $request->kategori;
        if (isset($request->koordinat))
            $wisata['koordinat'] = $request->koordinat;

        try {
            DB::table('wisata')->insert($wisata);
            return response(['message' => 'berhasil menambahkan data', 'data' => $wisata]);
        } catch (\Throwable $th) {
            return response(['message' => 'Error, Ada kesalahan', 'err' => $th]);
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
        $w = DB::table('wisata')->where('penulis', $id)->get();
        if(count($w) == 0)
            return response(['message' => 'Wisata dengan id ' . $id . 'tidak ditemukan']);
        if ($_SESSION['userdata']['username'] != $w[0]['penulis'])
            return response('Error, anda bukan orang yang menulis pariwisata ini', 401);
        $wisata = [];
        if (isset($request->lokasi))
            $wisata['lokasi'] = $request->lokasi;
        if (isset($request->kategori))
            $wisata['kategori'] = $request->kategori;
        if (isset($request->koordinat))
            $wisata['koordinat'] = $request->koordinat;
        if (isset($request->nama))
            $wisata['nama'] = $request->nama;
        if (count($wisata) == 0)
            return response(['message' => 'Tidak ada perubahan']);
        try {
            DB::table('wisata')->where('id', $id)->update($wisata);
            return response(['message' => 'berhasil mengupdate data', 'data' => $wisata]);
        } catch (\Throwable $th) {
            return response(['message' => 'Error, Ada kesalahan', 'err' => $th]);
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
        $w = DB::table('wisata')->where('id', $id)->get();
        if(count($w) == 0)
            return response(['message' => 'Wisata dengan id ' . $id . 'tidak ditemukan']);
        if ($_SESSION['userdata']['username'] != $w[0]['penulis'])
            return response('Error, anda bukan orang yang menulis pariwisata ini', 401);
        try {
            DB::table('wisata')->where('id', $id)->delete();
            return response(['message' => 'berhasil menghapus data']);
        } catch (\Throwable $th) {
            return response(['message' => 'Error, Ada kesalahan', 'err' => $th]);
        }
    }
}
