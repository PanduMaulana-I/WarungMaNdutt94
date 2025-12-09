<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MenuController extends Controller
{
    /**
     * 📋 PENJUAL — Lihat daftar menu sendiri
     */
    public function indexSeller()
    {
        $sellerId = Auth::id() ?? 1; // fallback sementara
        $menus = Menu::where('user_id', $sellerId)->get();

        return view('menus.index', compact('menus'));
    }

    /**
     * 🍽️ PEMBELI — Lihat semua menu publik
     */
    public function indexBuyer()
    {
        $menus = Menu::all();
        return view('buyer.menus', compact('menus'));
    }

    /**
     * ➕ Form Tambah Menu
     */
    public function create()
    {
        return view('menus.create');
    }

    /**
     * 💾 Simpan Menu Baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $path = $request->hasFile('image')
            ? $request->file('image')->store('menus', 'public')
            : null;

        Menu::create([
            'user_id' => Auth::id() ?? 1,
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock,
            'image' => $path,
        ]);

        return redirect()->route('seller.menus.index')->with('success', 'Menu berhasil ditambahkan!');
    }

    /**
     * ✏️ Form Edit Menu
     */
    public function edit(Menu $menu)
    {
        return view('menus.edit', compact('menu'));
    }

    /**
     * 🔄 Update Menu
     */
    public function update(Request $request, Menu $menu)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Update field teks dulu
        $menu->update([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock,
        ]);

        // Kalau ada gambar baru
        if ($request->hasFile('image')) {
            // Hapus gambar lama kalau ada
            if ($menu->image && Storage::disk('public')->exists($menu->image)) {
                Storage::disk('public')->delete($menu->image);
            }

            // Simpan gambar baru
            $menu->update([
                'image' => $request->file('image')->store('menus', 'public'),
            ]);
        }

        return redirect()->route('seller.menus.index')->with('success', 'Menu berhasil diperbarui!');
    }

    /**
     * 🗑️ Hapus Menu
     */
    public function destroy(Menu $menu)
    {
        if ($menu->image && Storage::disk('public')->exists($menu->image)) {
            Storage::disk('public')->delete($menu->image);
        }

        $menu->delete();

        return redirect()->route('seller.menus.index')->with('success', 'Menu berhasil dihapus!');
    }

    /**
     * 🔍 Detail menu untuk pembeli
     */
    public function show(Menu $menu)
    {
        return view('buyer.menu-detail', compact('menu'));
    }
}
