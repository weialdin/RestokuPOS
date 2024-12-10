<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;

class MenuController extends Controller
{
    /**
     * Display a listing of the menus.
     */
    public function index()
    {
        $menus = Menu::with('category')->paginate(3); // Load category for each menu
        $categories = Category::all(); // Load categories for dropdown in modal

        return view('management.menu', compact('menus', 'categories'));
    }

    /**
     * Store a newly created menu in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'price' => 'required|numeric', // Validasi untuk harga
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10000',
            'category_id' => 'required|exists:categories,id',
        ]);

        try {
            $imagePath = $request->file('image') ? $request->file('image')->store('menu_images', 'public') : null;

            Menu::create([
                'name' => $request->name,
                'description' => $request->description,
                'price' => $request->price, // Menyimpan harga
                'image' => $imagePath,
                'category_id' => $request->category_id,
            ]);

            return redirect()->route('menu.index')->with('status', 'Menu created successfully!');
        } catch (\Exception $e) {
            return redirect()->route('menu.index')->withErrors(['error' => 'Failed to create menu. Please try again.']);
        }
    }


    /**edit  ------------------------------------- */

    public function edit($id)
    {
        $menu = Menu::findOrFail($id); // Mencari menu berdasarkan ID
        $categories = Category::all(); // Mengambil semua kategori untuk dropdown
        return view('management.edit_menu', compact('menu', 'categories'));
    }


    /**
     * Update the specified menu in storage.
     */
  public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'price' => 'required|numeric', // Validasi untuk harga
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10000',
            'category_id' => 'required|exists:categories,id',
        ]);

        $menu = Menu::findOrFail($id);

        try {
            if ($request->hasFile('image')) {
                if ($menu->image) {
                    Storage::disk('public')->delete($menu->image);
                }
                $imagePath = $request->file('image')->store('menu_images', 'public');
                $menu->image = $imagePath;
            }

            $menu->name = $request->name;
            $menu->description = $request->description;
            $menu->price = $request->price; // Update harga
            $menu->category_id = $request->category_id;
            $menu->save();

        return redirect()->route('menu.index')->with('status', 'Menu updated successfully!');
        } catch (\Exception $e) {
            return redirect()->route('menu.index')->withErrors(['error' => 'Failed to update menu. Please try again.']);
        }
    }

    /** DESTROY METHOD */
    public function destroy($id)
    {
        $menu = Menu::findOrFail($id); // Mencari menu berdasarkan ID

        try {
            // Hapus gambar jika ada
            if ($menu->image) {
                Storage::disk('public')->delete($menu->image);
            }

            $menu->delete(); // Menghapus menu dari database

            return redirect()->route('menu.index')->with('status', 'Menu deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->route('menu.index')->withErrors(['error' => 'Failed to delete menu. Please try again.']);
        }
    }

};