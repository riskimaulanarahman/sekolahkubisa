<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Soal;
use App\Module;
use App\Jawaban;
use App\Siswajawab;
use Auth;

class SoalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $data = Soal::select('tbl_soal.*', 'tbl_module.nama_module')->leftJoin('tbl_module','tbl_soal.module_id','tbl_module.id')->get();

            return response()->json(['status' => "show", "message" => "Menampilkan Data" , 'data' => $data]);

        } catch (\Exception $e){

            return response()->json(["status" => "error", "message" => $e->getMessage()]);
        }
        // return view('soales.index', compact('soales'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // $modules = Module::all();
        // return view('soales.create', compact('modules'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $requestData = $request->all();

        try {
            Soal::create($requestData);

            return response()->json(["status" => "success", "message" => "Berhasil Menambahkan Data"]);

        } catch (\Exception $e){

            return response()->json(["status" => "error", "message" => $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        return view('soales/index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // $module = Module::findOrFail($id);
        // return view('modules.edit', compact('module'));
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
        $requestData = $request->all();
        
        try {
            $data = Soal::findOrFail($id);
            $data->update($requestData);

            return response()->json(["status" => "success", "message" => "Berhasil Ubah Data"]);

        } catch (\Exception $e){

            return response()->json(["status" => "error", "message" => $e->getMessage()]);
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
        try {
            $data = Soal::where('id',$id)->delete();

            return response()->json(["status" => "success", "message" => "Berhasil Hapus Data"]);

        } catch (\Exception $e){

            return response()->json(["status" => "error", "message" => $e->getMessage()]);
        }
    }

    public function soalevaluasi($id) 
    {
        $soalJawaban = Soal::join('tbl_jawaban', 'tbl_soal.id', '=', 'tbl_jawaban.soal_id')
            ->orderBy('tbl_soal.no_soal')
            ->select('tbl_soal.no_soal', 'tbl_soal.soal', 'tbl_jawaban.jawaban', 'tbl_jawaban.benar')
            ->get();

        $groupedSoalJawaban = $soalJawaban->groupBy('no_soal')->map(function ($item) {
            return [
                'no_soal' => $item[0]->no_soal,
                'soal' => $item[0]->soal,
                'jawaban' => $item->map(function ($subItem) {
                    return [
                        'jawaban' => $subItem->jawaban,
                        'benar' => $subItem->benar,
                    ];
                })->toArray(),
            ];
        })->values();

        return response()->json($groupedSoalJawaban);
    }

    public function jawabsiswa(Request $request)
    {
        // Validasi request jika diperlukan
        // $this->validate($request, [
        //     'user_id' => 'required',
        //     'soal_id' => 'required',
        //     'jawaban_id' => 'required',
        // ]);

        // Mendapatkan data dari request
        $data = $request->all();

        // Hitung nilai total berdasarkan jawaban yang benar
        $totalBenar = 0;
        foreach ($data['jawaban'] as $jawaban) {
            if ($jawaban == 1) {
                $totalBenar++;
            }
        }

        // Hitung persentase nilai
        $totalSoal = count($data['jawaban']);
        $nilai = ($totalBenar / $totalSoal) * 100;

        // Simpan data ke tabel tbl_siswajawab
        $siswaJawab = new SiswaJawab;
        $siswaJawab->user_id = Auth::user()->id;
        // $siswaJawab->soal_id = $data['soal_id'];
        $siswaJawab->module_id = $data['module_id'];
        $siswaJawab->hasil = $nilai;
        $siswaJawab->save();

        return response()->json(['message' => 'Data berhasil disimpan'], 200);
    }

    public function checknilai($id)
    {
        $data = Siswajawab::where('module_id', $id)->where('user_id', Auth::user()->id)->count();

        return $data;
    }

    public function shownilai($id)
    {
        $data = Siswajawab::with('user')->where('module_id',$id)->get();

        return $data;
    }

}
