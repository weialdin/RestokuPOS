<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\Models\Table;
use Illuminate\Http\Request;

class TableController extends Controller
{
    // Menampilkan halaman daftar meja
    public function index()
    {
        $tables = Table::paginate(3);
        // $tables = Table::all();
        return view('management.table', compact('tables'));
    }

    // Menyimpan meja baru
    public function store(Request $request)
    {
        // Validasi data input untuk memastikan nomor meja unik dan status diisi
        $request->validate([
            'table_number' => 'required|unique:tables,table_number',
            'status' => 'required'
        ]);

        // Membuat data meja baru
        Table::create([
            'table_number' => $request->table_number,
            'status' => $request->status
        ]);

        // Mengarahkan kembali ke halaman manajemen meja dengan pesan sukses
        return redirect()->route('table.index')->with('success', 'Meja berhasil ditambahkan.');
    }

    // Memperbarui data meja
    public function update(Request $request, $id)
    {
        // Validasi data input untuk memastikan nomor meja unik dan status diisi
        $request->validate([
            'table_number' => 'required|unique:tables,table_number,' . $id,
            'status' => 'required'
        ]);

        // Mengambil data meja berdasarkan ID, kemudian memperbarui data
        $table = Table::findOrFail($id);
        $table->update([
            'table_number' => $request->table_number,
            'status' => $request->status
        ]);

        // Mengarahkan kembali ke halaman manajemen meja dengan pesan sukses
        return redirect()->route('table.index')->with('success', 'Data meja berhasil diperbarui.');
    }

    // Menghapus meja
    public function destroy($id)
    {
        // Mengambil data meja berdasarkan ID dan menghapusnya
        $table = Table::findOrFail($id);
        $table->delete();

        // Mengarahkan kembali ke halaman manajemen meja dengan pesan sukses
        return redirect()->route('table.index')->with('success', 'Meja berhasil dihapus.');
    }
}
