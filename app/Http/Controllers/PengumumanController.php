<?php

namespace App\Http\Controllers;

use App\Models\Pengumuman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PengumumanController extends Controller
{
    // Halaman depan untuk warga (lihat semua pengumuman)
    public function index()
    {
        $pengumuman = Pengumuman::where('status', 'published')
            ->latest('published_at')
            ->paginate(10);
        return view('pengumuman.index', compact('pengumuman'));
    }

    // Detail pengumuman
    public function show($id)
    {
        $pengumuman = Pengumuman::where('status', 'published')->findOrFail($id);
        return view('pengumuman.show', compact('pengumuman'));
    }

    // ========== ADMIN AREA ==========

    // Admin: daftar semua pengumuman
    public function adminIndex()
    {
        $pengumuman = Pengumuman::with('user')->latest()->paginate(15);
        return view('pengumuman.admin.index', compact('pengumuman'));
    }

    // Admin: form tambah
    public function create()
    {
        return view('pengumuman.admin.create');
    }

    // Admin: simpan
    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
            'jenis' => 'required|in:penting,biasa',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status' => 'required|in:published,draft'
        ]);

        $gambarPath = null;
        if ($request->hasFile('gambar')) {
            $gambarPath = $request->file('gambar')->store('pengumuman', 'public');
        }

        Pengumuman::create([
            'judul' => $request->judul,
            'isi' => $request->isi,
            'jenis' => $request->jenis,
            'gambar' => $gambarPath,
            'created_by' => Auth::id(),
            'status' => $request->status,
            'published_at' => $request->status == 'published' ? now() : null
        ]);

        return redirect()->route('pengumuman.admin')
            ->with('success', 'Pengumuman berhasil ditambahkan.');
    }

    // Admin: form edit
    public function edit($id)
    {
        $pengumuman = Pengumuman::findOrFail($id);
        return view('pengumuman.admin.edit', compact('pengumuman'));
    }

    // Admin: update
    public function update(Request $request, $id)
    {
        $pengumuman = Pengumuman::findOrFail($id);

        $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
            'jenis' => 'required|in:penting,biasa',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status' => 'required|in:published,draft'
        ]);

        $data = [
            'judul' => $request->judul,
            'isi' => $request->isi,
            'jenis' => $request->jenis,
            'status' => $request->status,
            'published_at' => $request->status == 'published' ? now() : null
        ];

        if ($request->hasFile('gambar')) {
            if ($pengumuman->gambar) {
                Storage::disk('public')->delete($pengumuman->gambar);
            }
            $data['gambar'] = $request->file('gambar')->store('pengumuman', 'public');
        }

        $pengumuman->update($data);

        return redirect()->route('pengumuman.admin')
            ->with('success', 'Pengumuman berhasil diupdate.');
    }

    // Admin: hapus
    public function destroy($id)
    {
        $pengumuman = Pengumuman::findOrFail($id);

        if ($pengumuman->gambar) {
            Storage::disk('public')->delete($pengumuman->gambar);
        }

        $pengumuman->delete();

        return redirect()->route('pengumuman.admin')
            ->with('success', 'Pengumuman berhasil dihapus.');
    }
}
