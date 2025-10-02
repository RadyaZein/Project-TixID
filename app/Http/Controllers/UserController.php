<?php
    namespace App\Http\Controllers;

    use Illuminate\Http\Request;
    use App\Models\User;
    use Illuminate\Support\Facades\Hash;
    use Illuminate\Support\Facades\Auth;
    use App\Exports\StaffExport;
    use Maatwebsite\Excel\Facades\Excel;


    class UserController extends Controller
    {
        /**
         * Display a listing of the resource.
         */
        public function index()
        {
            $staffs = User::all();
            return view('admin.staff.index', compact('staffs'));
        }

        /**
         * Show the form for creating a new resource.
         */
        public function create()
        {
            //
        }

        /**
         * Store a newly created resource in storage.
         */
        public function store(Request $request)
        {
            //validasi
            $request->validate([
                'name' => 'required|min:3',
                'email' => 'required|email:dns|unique:users,email',
                'password' => 'required|min:8',
            ],[
                'name.required' => 'Nama Lengkap Wajib Diisi',
                'name.min' => 'Nama Lengkap Wajib diisi minimal 3 HUruf',
                'email.required' => 'Email Wajib Diisi',
                'email.email' => 'Email Wajib diisi dengan data yang valid',
                'email.unique' => 'Email sudah terdaftar, silahkan gunakan email lain',
                'password.required' => 'Password Wajib Diisi',
                'password.min' => 'Password Wajib diisi minimal 8 HUruf',
          ]);

                //kirim data
                $createStaff = User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => bcrypt($request->password), // hashing password
                    'role' => 'staff',

            ]);
                //redirect / perpindahan halaman
                if($createStaff){
                return redirect()->route('admin.staffs.index')->with('success', 'Berhasil Mebuat Data Petugas');
                } else {
                return redirect()->back()->with('failed', 'Data Gagal Ditambahkan');
                }
        }

        /**
         * Display the specified resource.
         */
        public function show(string $id)
        {
            //
        }

        /**
         * Show the form for editing the specified resource.
         */
        public function edit(string $id)
        {
            $staff = User::find($id);
            return view('admin.staff.edit', compact('staff'));
        }

        /**
         * Update the specified resource in storage.
         */
        public function update(Request $request, string $id)
{
    $staff = User::findOrFail($id);

    // validasi
    $request->validate([
        'name' => 'required|min:3',
        'email' => 'required|email|unique:users,email,'.$staff->id,
        'password' => 'nullable|min:8',
    ],[
        'name.required' => 'Nama wajib diisi',
        'name.min' => 'Nama minimal 3 huruf',
        'email.required' => 'Email wajib diisi',
        'email.email' => 'Format email salah',
        'email.unique' => 'Email sudah digunakan',
        'password.min' => 'Password minimal 8 karakter',
    ]);

    // update data
    $staff->name = $request->name;
    $staff->email = $request->email;

    // hanya update password kalau user isi field password
    if ($request->filled('password')) {
        $staff->password = bcrypt($request->password);
    }

    $staff->save();

    return redirect()->route('admin.staffs.index')->with('success', 'Data staff berhasil diperbarui');
}


        /**
         * Remove the specified resource from storage.
         */
        public function destroy($id)
        {
            // dd($id);
             $deleteData = user::where('id', $id)->delete();
            if($deleteData) {
                return redirect()->route('admin.staffs.index')->with('success', 'Berhasil Menghapus Data Bioskop');
            } else {
                return redirect()->back()->with('failed', 'Gagal mengahapus data bioskop');
        }
        }

        public function signUp(Request $request)
        {
            //(Request $request : class untuk mengambil value dari formulir)
            //validasi
            $request->validate([
                //'name_input' => 'tipe validasi'
                //reqred : wajib diisi. min :minimal karakter (teks)
                'first_name' => 'required|min:3',
                'last_name' => 'required|min:3',
                // email:dns => emailnya valid. @gmail @company.co dsb
                'email' => 'required|email:dns',
                'password' => 'required|min:8',
            ],[
                'first_name.required' => 'Nama depan wajib diisi',
                'first_name.min' => 'Nama depan wajib diisi minimal 3 huruf',
                'last_name.required' => 'Nama belakang wajib diisi',
                'last_name.min' => 'Nama belakang wajib diisi minimal 3 huruf',
                'email.required' => 'Email wajib diisi',
                'email.email' => 'Email wajib diisi dengan data yang valid',
                'password.required' => 'Password wajib diisi',
                'password.min' => 'Password wajib diisi minimal 8 huruf',
            ]);

            //create() :membuat data baru
            $createUser = user::create([
                //'nama_column' => $request->nama_input
                'name' => $request->first_name . ' ' . $request->last_name,
                'email' => $request->email,
                //Hash : enskripsi data (mengubah menjadi karakter acak) agar tidak ada yang bisa menebak isinya
                'password' => Hash::make($request->password),
                //pengguna tidaj bisa memilih role (akses), jadi manual ditambahkan 'user'
                'role' => 'staff',
            ]);

            if ($createUser) {
                //redict() : memindahkan halaman, route() : mame routing yang dituju
                //with() : mengirimkan session, biasanya untuk notifikasi
                return redirect()->route('login')->with('login_success', 'Silahkan login!');
            } else {
                //back() : kembali ke halaman sebelumnya
                return redirect()->back()->with('error', 'Akun gagal dibuat, silahkan coba lagi');
            }
        }

        public function loginAuth(Request $request)
        {
            $request->validate([
                'email' => 'required',
                'password' => 'required'
            ], [
                'email.required' => 'Email wajib diisi',
                'password.required' => 'Password harus diisi',

            ]);
            //mengambil data yang akan diverifikasi
            $data = $request->only(['email', 'password']);
            //Auth class laravel untuk penangaann autentikasi
            //attempt() -> method class Auth untuk mencocokan email-pw atau username-pw
            // kalau cocok akan disimpan datanya ke session Auth
            if (Auth::attempt($data)) {
                //jika berhasil login (attempt),dicek lagi role nya
                if (Auth::user()->role == 'admin') {
                    return redirect()->route('admin.dashboard')->with('sucsess', 'Berhasil Login!');
                } elseif (Auth::user()->role == 'staff') {
                    return redirect()->route('staff.dashboard')->with('success', 'Berhasil Login!');
                } else {
                return redirect()->route('home')->with('success', 'Berhasil Login!');
                }
            } else {
                return redirect()->back()->with('error', 'Gagal Login! Pastikan Email dan Password Benar');
    }
}
    public function logout()
    {
        Auth::logout();
        return redirect()->route('home')->with('logout', 'Berhasil Logout!
        silahkan kogin kembali untuk akses lengkap');
    }

    public function nonAktif($id)
    {
        $staff = User::findOrFail($id);
        $staff->actived = 0; // set nonaktif
        $staff->save();

        return redirect()->route('admin.staffs.index')->with('success', 'Staff berhasil dinonaktifkan.');
    }

    public function export()
    {
        // file yang akan di unduh
        $fileName = 'data-Staff.xlsx';
        //proses unduh
        return Excel::download(new StaffExport, $fileName);
    }
 }
