<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\MailController;

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/', function () {
    return view('welcome');
});
Auth::routes();
Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::group(['middleware' => ['auth']], function () {
    Route::resource('roles', RoleController::class);
    Route::resource('users', UserController::class);
    Route::resource('products', ProductController::class);


Route::get('/export', [UserController::class, 'export'])->name('export');

Route::get('/generatetcpdf', [UserController::class, 'generatetcpdf']);
Route::get('/certificate', [UserController::class, 'certificate']);

Route::get('/import', [ImportController::class, 'import'])->name('import');

Route::get('/importt', [ImportController::class, 'index']);
Route::post('/import-excel', [ImportController::class, 'uploadExcel'])->name('import.excel');

Route::get('send-mail', [MailController::class, 'index']);


});