<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Provinsi;
use Carbon\Carbon;
use DB; 

class ProvinsiController extends Controller
{


    public function provinsi()
    {
        $provinsi = DB::table('provinsis')
                    ->select('provinsis.nama_provinsi','provinsis.kode_provinsi',
                    DB::raw('SUM(kasuses.positif) as Positif'),
                    DB::raw('SUM(kasuses.sembuh) as Sembuh'),
                    DB::raw('SUM(kasuses.meninggal) as Meninggal'))
                        ->join('kotas','provinsis.id','=', 'kotas.id_provinsi')
                        ->join('kecamatans','kota.id','=', 'kecamatans.id_kota')
                        ->join('desas','kecamatans.id','=', 'desas.id_kecamatan')
                        ->join('rws','desas.id','=', 'rws.id_desa')
                        ->join('kasuses','rws.id','=', 'kasuses.id_rw')
                        ->whereDate('kasuses.tanggal', Carbon::today())
                        ->groupBy('provinsis.id')
                        ->get();
                        return response()->json([
                            'success' => true,
                            'data'    => [
                                'hari_ini' => $provinsi
                            ]
                            ], 200);
    }

    public function showProvinsi($id)
    {
        $provinsi = DB::table('provinsis')
                    ->select('provinsis.nama_provinsi','provinsis.kode_provinsi',
                    DB::raw('SUM(kasuses.positif) as Positif'),
                    DB::raw('SUM(kasuses.sembuh) as Sembuh'),
                    DB::raw('SUM(kasuses.meninggal) as Meninggal'))
                        ->join('kotas','provinsis.id','=', 'kotas.id_provinsi')
                        ->join('kecamatans','kota.id','=', 'kecamatans.id_kota')
                        ->join('desas','kecamatans.id','=', 'desas.id_kecamatan')
                        ->join('rws','desas.id','=', 'rws.id_desa')
                        ->join('kasuses','rws.id','=', 'kasuses.id_rw')
                        ->where('provinsis.id',$id)
                        ->groupBy('provinsis.id')
                        ->get();
                    return response()->json([
                        'success' => true,
                        'data'    => [
                            'hari_ini' => $today
                        ]
                        ], 200);
    }
    public function kota()
    {
        //Data Kota 
        $data = DB::table('kotas')
        ->join('kecamatans','kecamatans.id_kota', '=', 'kotas.id')
        ->join('desas','desas.id_kecamatan', '=', 'kecamatans.id')
        ->join('rws','rws.id_desa', '=', 'desas.id')
        ->join('kasuses','kasuses.id_rw', '=', 'rws.id')
        ->select('nama_kota',
        DB::raw('sum(kasuses.positif) as positif'),
        DB::raw('sum(kasuses.meninggal) as meninggal'),
        DB::raw('sum(kasuses.sembuh) as sembuh'))
        ->groupBy('nama_kota')
        ->get();
                $res = [
                    'succsess' => true,
                    'Data' => $data,
                    'message' => 'Data Kasus Di Tampilkan'
                ];
                return response()->json($res,200);
    }
    public function desa()
    {
        //Data Kota 
        $data = DB::table('desas')
        ->join('rws','rws.id_desa', '=', 'desas.id')
        ->join('kasuses','kasuses.id_rw', '=', 'rws.id')
        ->select('nama_desa',
        DB::raw('sum(kasuses.positif) as positif'),
        DB::raw('sum(kasuses.meninggal) as meninggal'),
        DB::raw('sum(kasuses.sembuh) as sembuh'))
        ->groupBy('nama_desa')
        ->get();
                $res = [
                    'succsess' => true,
                    'Data' => $data,
                    'message' => 'Data Kasus Di Tampilkan'
                ];
                return response()->json($res,200);
    }

    public function indonesia()
    {
      

        //Data SeIndonesia
        $positif = DB::table('rws')
        ->select('kasuses.positif','kasuses.meninggal','kasuses.sembuh')->join('kasuses',
                'rws.id', '=', 'kasuses.id_rw')->sum('kasuses.positif');
        $meninggal = DB::table('rws')
        ->select('kasuses.positif','kasuses.meninggal','kasuses.sembuh')->join('kasuses',
                'rws.id', '=', 'kasuses.id_rw')->sum('kasuses.meninggal');
        $sembuh = DB::table('rws')
        ->select('kasuses.positif','kasuses.meninggal','kasuses.sembuh')->join('kasuses',
                'rws.id', '=', 'kasuses.id_rw')->sum('kasuses.sembuh');

                $res = [
                    'succsess' => true,
                    'Data' => 'Data Kasus Indonesia',
                    'Jumlah Positif' => $positif,
                    'Jumlah Meninggal' => $meninggal,
                    'Jumlah Sembuh' => $sembuh,
                    'message' => 'Data Kasus Di Tampilkan'
                ];
                return response()->json($res,200);
    }
    
    public function index()
    {
        $provinsi = Provinsi::latest()->get();
        $prov = [
            'success' => true,
            'data'    => $provinsi,
            'message' => 'Data Provinsi Ditampilkan'
        ];
        return response()->json($prov, 200);
    }

   
    // public function create()
    // {
        
    // }

   
    public function store(Request $request)
    {
        $provinsi = new Provinsi();
        $provinsi->kode_provinsi = $request->kode_provinsi;
        $provinsi->nama_provinsi = $request->nama_provinsi;
        $provinsi->save();

        $prov = [
            'success' => true,
            'data'    => $provinsi,
            'message' => 'Data berhasil di tambah'
        ];
        return response()->json($prov, 200);
    }

    
    public function show($id)
    {
        $provinsi = Provinsi::whereId($id)->first();
        if ($provinsi) {
            return response()->json([
                'success' => true,
                'message' => 'Detail Provinsi!',
                'data'    => $provinsi
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Provinsi Tidak Ditemukan!',
                'data'    => ''
            ], 404);
        }
        return response()->json($provinsi, 200);
    }

    
    public function edit($id)
    {
        //
    }

    
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'kode_provinsi' => 'required',
            'nama_provinsi' => 'required',
        ],[
            'kode_provinsi.required' => "Mohon Masukan Kode Provinsi",
            'nama_provinsi.required' => "Mohon Masukan Nama Provinsi",
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'data' => $validator->errors(),
                'message' => 'silakan isi bidang yang kosong',
            ], 400);
        }else {
            $provinsi = Provinsi::whereId($id)->update([
                'kode_provinsi' => $request->kode_provinsi,
                'nama_provinsi' => $request->nama_provinsi,
            ]);

            if ($provinsi) {
                return response()->json([
                    'success' => true,
                    'message' => 'data berhasil diUpdate!',
                ], 200); 
            }else{
               return response()->json([
                    'success' => false,
                    'message' => 'data gagal diUpdate!',
               ], 500); 
            }
        }
    }

    
    public function destroy($id)
    {
        $provinsi = Provinsi::findOrFail($id);
        $provinsi->delete();

        if ($provinsi) {
            return response()->json([
                'success' => true,
                'message' => 'data berhasil dihapus!',
            ], 200);
        }else {
            return response()->json([
                'success' => false,
                'message' => 'data gagal dihapus',
            ], 500);
        }
    }
}