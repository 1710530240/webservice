<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ulasanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
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

        $ulasan = [
            'id' => Str::random(5),
            'wisata' => $request->wisata,
            'user' => $_SESSION['userdata']['username'],
            'ulasan' => $request->ulassan
        ];

        try {
            DB::table('ulasan')->insert($ulasan);
            return response(['message' => 'berhasil menambahkan data', 'data' => $ulasan]);
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
        //
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
        $w = DB::table('wisata')->where('ulasan', $id)->get();
        if (count($w) == 0)
            return response(['message' => 'Wisata dengan id ' . $id . 'tidak ditemukan']);
        if ($_SESSION['userdata']['username'] != $w[0]['penulis'])
            return response('Error, anda bukan orang yang menulis ulsan ini', 401);
        $ulasan = [
            'id' => Str::random(5),
            'wisata' => $request->wisata,
            'user' => $_SESSION['userdata']['username'],
        ];

        if (isset($request->ulasan))
            $ulasan['ulasan'] = $request->ulasan;
        if (isset($request->wisata))
            $ulasan['wisata'] = $request->wisata;

        if (count($ulasan) == 3)
            return response(['message' => 'Tidak ada perubahan']);

        try {
            DB::table('ulasan')->where('id', $id)->update($ulasan);
            return response(['message' => 'berhasil merubah data', 'data' => $ulasan]);
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
        $w = DB::table('wisata')->where('ulasan', $id)->get();
        if (count($w) == 0)
            return response(['message' => 'Wisata dengan id ' . $id . 'tidak ditemukan']);
        if ($_SESSION['userdata']['username'] != $w[0]['penulis'])
            return response('Error, anda bukan orang yang menulis ulsan ini', 401);

        try {
            DB::table('wisata')->where('id', $id)->delete();
            return response(['message' => 'berhasil menghapus data']);
        } catch (\Throwable $th) {
            return response(['message' => 'Error, Ada kesalahan', 'err' => $th]);
        }
    }
}
