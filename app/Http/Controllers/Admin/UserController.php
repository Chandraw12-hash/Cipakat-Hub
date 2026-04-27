<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * Display a listing of the users.
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Search
        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('email', 'like', '%' . $request->search . '%')
                ->orWhere('nik', 'like', '%' . $request->search . '%')
                ->orWhere('phone', 'like', '%' . $request->search . '%');
        }

        // Filter role
        if ($request->role) {
            $query->where('role', $request->role);
        }

        $users = $query->latest()->paginate(10);

        // Statistik
        $totalUsers = User::count();
        $totalAdmin = User::where('role', 'admin')->count();
        $totalPetugas = User::where('role', 'petugas')->count();
        $totalWarga = User::where('role', 'warga')->count();
        $totalBelumLengkap = User::whereNull('nik')->orWhereNull('phone')->orWhereNull('alamat')->count();

        return view('admin.users.index', compact('users', 'totalUsers', 'totalAdmin', 'totalPetugas', 'totalWarga', 'totalBelumLengkap'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        // Validasi lengkap
        $request->validate([
            // Informasi Akun
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|in:admin,petugas,warga',

            // Data Diri
            'nik' => 'nullable|string|max:20|unique:users,nik',
            'phone' => 'nullable|string|max:15',
            'tempat_lahir' => 'nullable|string|max:100',
            'tanggal_lahir' => 'nullable|date',
            'jenis_kelamin' => 'nullable|in:Laki-laki,Perempuan',
            'pekerjaan' => 'nullable|string|max:100',
            'status_pekerjaan' => 'nullable|in:bekerja,tidak_bekerja,mahasiswa,pensiun',
            'pendidikan_terakhir' => 'nullable|string|max:50',

            // Sosial Ekonomi
            'pendapatan_bulanan' => 'nullable|numeric',
            'kategori_sosial' => 'nullable|in:rentan,mampu',
            'status_rumah' => 'nullable|in:milik_sendiri,kontrak,keluarga',
            'is_penerima_bantuan' => 'nullable|boolean',
            'jumlah_tanggungan' => 'nullable|integer',

            // Alamat
            'alamat' => 'nullable|string',
            'rt_rw' => 'nullable|string|max:20',
            'kode_pos' => 'nullable|string|max:10',

            // Keluarga
            'kepala_keluarga_nik' => 'nullable|string|max:20',
            'status_keluarga' => 'nullable|in:kepala_keluarga,istri,anak,lainnya',

            // Foto Profil
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Data untuk disimpan
        $data = [
            // Informasi Akun
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,

            // Data Diri
            'nik' => $request->nik,
            'phone' => $request->phone,
            'tempat_lahir' => $request->tempat_lahir,
            'tanggal_lahir' => $request->tanggal_lahir,
            'jenis_kelamin' => $request->jenis_kelamin,
            'pekerjaan' => $request->pekerjaan,
            'status_pekerjaan' => $request->status_pekerjaan,
            'pendidikan_terakhir' => $request->pendidikan_terakhir,

            // Sosial Ekonomi
            'pendapatan_bulanan' => $request->pendapatan_bulanan,
            'kategori_sosial' => $request->kategori_sosial,
            'status_rumah' => $request->status_rumah,
            'is_penerima_bantuan' => $request->is_penerima_bantuan ?? 0,
            'jumlah_tanggungan' => $request->jumlah_tanggungan ?? 0,

            // Alamat
            'alamat' => $request->alamat,
            'rt_rw' => $request->rt_rw,
            'kode_pos' => $request->kode_pos,

            // Keluarga
            'kepala_keluarga_nik' => $request->kepala_keluarga_nik,
            'status_keluarga' => $request->status_keluarga ?? 'lainnya',
        ];

        // Upload foto
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('users', 'public');
            $data['photo'] = $photoPath;
        }

        User::create($data);

        return redirect()->route('users.index')
            ->with('success', 'User berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Display the specified user.
     */
    public function show($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.show', compact('user'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            // Informasi Akun
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'role' => 'required|in:admin,petugas,warga',

            // Data Diri
            'nik' => 'nullable|string|max:20|unique:users,nik,' . $id,
            'phone' => 'nullable|string|max:15',
            'tempat_lahir' => 'nullable|string|max:100',
            'tanggal_lahir' => 'nullable|date',
            'jenis_kelamin' => 'nullable|in:Laki-laki,Perempuan',
            'pekerjaan' => 'nullable|string|max:100',
            'status_pekerjaan' => 'nullable|in:bekerja,tidak_bekerja,mahasiswa,pensiun',
            'pendidikan_terakhir' => 'nullable|string|max:50',

            // Sosial Ekonomi
            'pendapatan_bulanan' => 'nullable|numeric',
            'kategori_sosial' => 'nullable|in:rentan,mampu',
            'status_rumah' => 'nullable|in:milik_sendiri,kontrak,keluarga',
            'is_penerima_bantuan' => 'nullable|boolean',
            'jumlah_tanggungan' => 'nullable|integer',

            // Alamat
            'alamat' => 'nullable|string',
            'rt_rw' => 'nullable|string|max:20',
            'kode_pos' => 'nullable|string|max:10',

            // Keluarga
            'kepala_keluarga_nik' => 'nullable|string|max:20',
            'status_keluarga' => 'nullable|in:kepala_keluarga,istri,anak,lainnya',

            // Foto Profil
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = [
            // Informasi Akun
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,

            // Data Diri
            'nik' => $request->nik,
            'phone' => $request->phone,
            'tempat_lahir' => $request->tempat_lahir,
            'tanggal_lahir' => $request->tanggal_lahir,
            'jenis_kelamin' => $request->jenis_kelamin,
            'pekerjaan' => $request->pekerjaan,
            'status_pekerjaan' => $request->status_pekerjaan,
            'pendidikan_terakhir' => $request->pendidikan_terakhir,

            // Sosial Ekonomi
            'pendapatan_bulanan' => $request->pendapatan_bulanan,
            'kategori_sosial' => $request->kategori_sosial,
            'status_rumah' => $request->status_rumah,
            'is_penerima_bantuan' => $request->is_penerima_bantuan ?? 0,
            'jumlah_tanggungan' => $request->jumlah_tanggungan ?? 0,

            // Alamat
            'alamat' => $request->alamat,
            'rt_rw' => $request->rt_rw,
            'kode_pos' => $request->kode_pos,

            // Keluarga
            'kepala_keluarga_nik' => $request->kepala_keluarga_nik,
            'status_keluarga' => $request->status_keluarga ?? 'lainnya',
        ];

        // Upload foto baru
        if ($request->hasFile('photo')) {
            // Hapus foto lama jika ada
            if ($user->photo) {
                Storage::disk('public')->delete($user->photo);
            }
            $photoPath = $request->file('photo')->store('users', 'public');
            $data['photo'] = $photoPath;
        }

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('users.index')
            ->with('success', 'User berhasil diupdate.');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // Prevent admin from deleting themselves
        if ($user->id === auth()->id()) {
            return redirect()->route('users.index')
                ->with('error', 'Anda tidak dapat menghapus akun sendiri.');
        }

        // Hapus foto jika ada
        if ($user->photo) {
            Storage::disk('public')->delete($user->photo);
        }

        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'User berhasil dihapus.');
    }
}
