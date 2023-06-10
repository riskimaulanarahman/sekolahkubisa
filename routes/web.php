<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/', 'HomeController@index')->name('dashboard');
// Route::auth();
// Auth::routes();

// Route::group( ['prefix' => 'admin','as' => 'admin.','middleware' => ['auth']], function() {
// Route::group( ['as' => 'pekerja.','middleware' => ['auth']], function() {

    // Route::get('/', 'HomeController@index')->name('index');

    // //referensi
    // Route::get('/ref-agama','referensi\AgamaController@show')->name('refagama');
    // Route::apiResource('/api/ref-agama','referensi\AgamaController');


    // //master user
    Route::get('/master-user', 'masteruser\LoginUserController@show')->name('masteruser');

    // //vendor page
    // Route::get('/sqacdoc','vendors\SqacdocController@show')->name('masteruser');

    // //API

    Route::apiResource('/api/master-user', 'masteruser\LoginUserController');
    Route::resource('materi', 'ModuleController');
    Route::resource('/api/jawaban', 'JawabanController');
    // Route::apiResource('/api/sqacdoc','vendors\SqacdocController');
    // // Route::apiResource('/api/monitoring','kap\MonitoringController');
    Route::get('/soales', 'SoalController@show');
    Route::apiResource('/api/soales', 'SoalController');
    
    Route::get('soalevaluasi/{id}', 'SoalController@soalevaluasi');
    Route::post('jawabsiswa', 'SoalController@jawabsiswa');
    Route::get('checknilai/{id}', 'SoalController@checknilai');
    Route::get('countmodule', 'ModuleController@countmodule');
    Route::get('shownilai/{id}', 'SoalController@shownilai');

    Route::get('biodata',function(){
        return view('biodata');
    })->name('biodata.index');

    Route::get('videopuisi',function(){
        return view('videopuisi');
    })->name('videopuisi.index');
    // //import excel
    // Route::get('/siswa', 'SiswaController@index');
    // Route::get('/siswa/export_excel', 'SiswaController@export_excel');
    // Route::post('/siswa/import_excel', 'SiswaController@import_excel');
    
// });

require __DIR__.'/auth.php';
