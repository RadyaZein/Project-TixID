    <?php

    use App\Http\Controllers\UserController;
    use App\Http\Controllers\CinemaController;
    use App\Http\Controllers\MovieController;
    use App\Http\Controllers\PromoController;
    use App\Http\Controllers\ScheduleController;
    use App\Http\Controllers\TicketController;
    use Illuminate\Support\Facades\Route;



    //beranda
    Route::get('/', [MovieController::class, 'home'])->name('home');


    // semua data film
    Route::get('/home/movies', [MovieController::class, 'homeAllMovies'])->name('home.movies');

    // name : memberi identitas pada route yang di panggil

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




    // //coba detail
    Route::get('/schedule/{id}', [MovieController::class, 'detail'])->name('schedules.detail');

    // middleware isUser
    Route::middleware('isUser')->group(function () {
    // halaman pilihan kursi
    Route::get('/schedule/{scheduleId}/hours/{hourId}/show-seats', [TicketController::class, 'showSeats'])->name('schedules.seats');
});

    // m3nu bioskop pada navbar us3r (p3ngguna ummum)
    Route::get('/cinemas/list', [CinemaController::class, 'cinemaList'])->name('cinemas.list');
    Route::get('/cinemas/{ciname_id}/schedules', [CinemaController::class, 'cinemaSchedules'])->name('cinemas.schedules');

    // logout
    Route::get('logout', [UserController::class, 'logout'])->name('logout');

    //beranda
    Route::get('/',[MovieController::class,'home'])->name('home');

    //prefix() : awalan,menulis /admmin sau kali untuk 16 route CRUD
    // (beberapa route)
    //name('admin') : pake titik karna nanti akan digabungkan yang akan digunkan pada route
    // middleware is admin
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
            Route::get('/export' , [CinemaController::class, 'export'])->name('export');
            Route::get('/trash', [cinemaController::class, 'trash'])->name('trash');
            // restore
            Route::patch('/restore/{id}', [cinemaController::class, 'restore'])->name('restore');
            // delete permanen
            Route::delete('/delete-permanent/{id}', [cinemaController::class, 'deletePermanent'])->name('delete_permanent');
            Route::get('/datatables', [CinemaController::class, 'datatables'])->name('datatables');
            });

        // middleware isStaff
        Route::prefix('/staffs')->name('staffs.')->group(function() {
            Route::get('/', [UserController::class,'index'])->name('index');
            Route::get('create', function(){
                return view ('admin.staff.create');
            })->name('create');
            Route::post('/store', [UserController::class, 'store'])->name('store');
            Route::get('/edit/{id}', [UserController::class, 'edit'])->name('edit');
            Route::put('/update/{id}', [UserController::class, 'update'])->name('update');
            Route::delete('/destroy/{id}', [UserController::class, 'destroy'])->name('delete');
            Route::put('/nonaktif/{id}', [UserController::class, 'nonAktif'])->name('nonaktif');
            Route::get('/export', [UserController::class, 'export'])->name('export' );
            Route::get('/trash', [UserController::class, 'trash'])->name('trash');
            Route::patch('/restore/{id}', [UserController::class, 'restore'])->name('restore');
            Route::delete('/delete-permanent/{id}', [UserController::class, 'deletePermanent'])->name('delete_permanent');
            Route::delete('/delete-all-permanent', [UserController::class, 'deleteAllPermanent'])->name('delete_all_permanent');
            Route::get('/datatables', [UserController::class, 'datatables'])->name('datatables');
        });

                Route::prefix('/movies')->name('movies.')->group(function() {
                        Route::get('/', [MovieController::class,'index'])->name('index');
                        Route::get('create', [MovieController::class, 'create'])->name('create');
                        Route::post('/store', [MovieController::class, 'store'])->name('store');
                        Route::put('/update/{id}', [MovieController::class, 'update'])->name('update');
                        Route::get('/edit/{id}', [MovieController::class, 'edit'])->name('edit');
                        Route::delete('/destroy/{id}', [MovieController::class, 'destroy'])->name('delete');
                        Route::put('/nonaktif/{id}', [MovieController::class, 'nonAktif'])->name('nonaktif');
                        Route::get('/export', [MovieController::class, 'export'])->name('export');
                        Route::get('/trash', [MovieController::class, 'trash'])->name('trash');
                        // restore
                        Route::patch('/restore/{id}', [MovieController::class, 'restore'])->name('restore');
                        // delete permanen
                        Route::delete('/delete-permanent/{id}', [MovieController::class, 'deletePermanent'])->name('delete_permanent');
                        Route::get('/datatables', [MovieController::class, 'datatables'])->name('datatables');

            });
    });
    Route::middleware('isStaff')->prefix('/staff')->name('staff.')->group(function () {
        Route::get('/dashboard', function () {
            return view('staff.dashboard');
        })->name('dashboard');

        // promos
        Route::prefix('/promos')->name('promos.')->group(function () {
            Route::get('/', [PromoController::class, 'index'])->name('index');
            Route::get('/create', [PromoController::class, 'create'])->name('create');
            Route::post('/store', [PromoController::class, 'store'])->name('store');
            Route::get('/edit/{id}', [PromoController::class, 'edit'])->name('edit');
            Route::put('/update/{id}', [PromoController::class, 'update'])->name('update');
            Route::delete('/destroy/{id}', [PromoController::class, 'destroy'])->name('destroy');
            Route::patch('/{id}/toggle', [PromoController::class, 'toggle'])->name('toggle');
            Route::get('/export', [PromoController::class, 'export'])->name('export');
            Route::get('/trash', [PromoController::class, 'trash'])->name('trash');
            // restore
            Route::patch('/restore/{id}', [PromoController::class, 'restore'])->name('restore');
            // delete permanen
            Route::delete('/delete-permanent/{id}', [PromoController::class, 'deletePermanent'])->name('delete_permanent');
            // tabless
            Route::get('/datatables', [PromoController::class, 'datatables'])->name('datatables');

        });

        // schedules
        Route::prefix('/schedules')->name('schedules.')->group(function(){
            Route::get('/', [ScheduleController::class, 'index'])->name('index');
            Route::post('/store', [ScheduleController::class, 'store'])->name('store');
            Route::get('/edit/{id}', [ScheduleController::class, 'edit'])->name('edit');
            Route::patch('/update/{id}', [ScheduleController::class, 'update'])->name('update');
            Route::delete('/destroy/{id}', [ScheduleController::class, 'destroy'])->name('delete');
            Route::get('/trash', [ScheduleController::class, 'trash'])->name('trash');
            // restore
            Route::patch('/restore/{id}', [ScheduleController::class, 'restore'])->name('restore');
            // delete permanen
            Route::delete('/delete-permanent/{id}', [ScheduleController::class, 'deletePermanent'])->name('delete_permanent');
            Route::get('/datatables', [ScheduleController::class, 'datatables'])->name('datatables');
            });

    });



