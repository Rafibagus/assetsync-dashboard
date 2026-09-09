<?php

namespace App\Http\Controllers;

use App\Models\MaintenanceTicket;
use Illuminate\Http\Request;

class MaintenanceTicketController extends Controller
{
    public function index()
    {
        // Ambil semua tiket beserta data aset dan lokasinya
        $tickets = MaintenanceTicket::with('asset.location')->latest()->get();
        return view('tickets.index', compact('tickets'));
    }
    public function create(\App\Models\Asset $asset)
    {
        return view('tickets.create', compact('asset'));
    }

    public function store(Request $request, \App\Models\Asset $asset)
    {
        $request->validate([
            'issue_title' => 'required|string|max:255',
            'issue_description' => 'required|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048' // Validasi gambar
        ]);

        // Proses unggah foto jika ada
        $photoPath = null;
        if ($request->hasFile('photo')) {
            // Simpan foto di folder 'storage/app/public/tickets'
            $photoPath = $request->file('photo')->store('tickets', 'public');
        }

        // Buat tiket baru
        \App\Models\MaintenanceTicket::create([
            'asset_id' => $asset->id,
            'issue_title' => $request->issue_title,
            'issue_description' => $request->issue_description,
            'photo_path' => $photoPath, // Simpan path-nya ke database
            'status' => 'Pending'
        ]);

        $asset->update(['status' => 'Maintenance']);

        return redirect()->route('dashboard')->with('success', 'Laporan kerusakan & foto berhasil dikirim!');
    }

    public function update(Request $request, MaintenanceTicket $ticket)
    {
        $request->validate(['status' => 'required|in:Pending,In Progress,Resolved']);

        // Update status tiket
        $ticket->update(['status' => $request->status]);

        // MAGIC: Jika tiket selesai, otomatis kembalikan aset jadi Available
        if ($request->status == 'Resolved') {
            $ticket->asset->update(['status' => 'Available']);
        } elseif ($request->status == 'In Progress' || $request->status == 'Pending') {
            $ticket->asset->update(['status' => 'Maintenance']);
        }

        return back()->with('success', 'Status tiket berhasil diperbarui!');
    }
}