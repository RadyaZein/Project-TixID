<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\CinemaController;
use App\Http\Controllers\MovieController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/scedules', function () {
    return view('schedule/detail-film');
})->name('schedules.detail');
    //middleware : untuk mengelompokkan route yang memerlukan middleware tertentu
    //isGuest : nama middleware yang sudah didaftarkan pada bootstrap/app.php
Route::middleware('isGuest')->group(function(){
    Route::get('/login', function () {
    return view('login');
})->name('login');
Route::post('login', [UserController::class, 'loginAuth'])->name('login.auth');
Route::get('/sign-up', function () {
    return view('signup');
})->name('sign_up');
Route::post ('sign-up', [UserController::class, 'signUp'])->name('sign_up.add');

});

//name : meberi



//httpmethod route
//1 .get -> menampilkan halaman
//2 .post -> mengambil data
//3 .put -> mengubah data
//4 .delete -> menghapus data


Route::get('logout', [UserController::class, 'logout'])->name('logout');

//prefix() : awalan,menulis /admmin sau kali untuk 16 route CRUD
// (beberapa route)
//name('admin') : pake titik karna nanti akan digabungkan yang akan digunkan pada route
Route::middleware('isadmin')->prefix('/admin')->name('admin.')->group(function(){
    Route::get('dashboard', function(){
        return view('admin.dashboard');
    })->name('dashboard');

    // cinemas
    Route::prefix('/cinemas')->name('cinemas.')->group(function() {
        Route::get('/', [CinemaController::class,'index'])->name('index');
        Route::get('create', function(){
            return view('admin.cinema.create');
        })->name('create');
        Route::post('/store', [CinemaController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [CinemaController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [CinemaController::class, 'update'])->name('update');
        Route::delete('/destroy/{id}', [CinemaController::class, 'destroy'])->name('delete');
    });

    // staffs
    Route::prefix('/staffs')->name('staffs.')->group(function() {
        Route::get('/', [UserController::class,'index'])->name('index');
        Route::get('create', function(){
            return view ('admin.staff.create');
        })->name('create');
        Route::post('/store', [UserController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [UserController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [UserController::class, 'update'])->name('update');
        Route::delete('/destroy/{id}', [UserController::class, 'destroy'])->name('delete');
        Route::put('/admin/staffs/update/{id}', [StaffController::class, 'update'])->name('admin.staffs.update');

    });

        Route::prefix('/movies')->name('movies.')->group(function() {
                Route::get('/', [MovieController::class,'index'])->name('index');
                route::get('create',[MovieController::class,'create'])->name('create');
                Route::post('/store', [MovieController::class, 'store'])->name('store');
                Route::put('/update/{id}', [MovieController::class, 'update'])->name('update');
                Route::get('/edit{id}', [MovieController::class, 'edit'])->name('edit');
      });

});
