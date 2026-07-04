<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeaveController extends Controller
{
    public function index()
    {
        return view('employee.leave');
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'type' => 'required|in:izin,sakit',
            'reason' => 'required|string',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $path = null;

        if ($request->hasFile('attachment')) {
            $path = $request->file('attachment')
                ->store('leave-attachments', 'public');
        }

        LeaveRequest::create([
            'user_id' => Auth::id(),
            'date' => $request->date,
            'type' => $request->type,
            'reason' => $request->reason,
            'attachment' => $path,
            'status' => 'pending',
        ]);

        return redirect()->route('leave.index')
            ->with('success', 'Pengajuan berhasil dikirim ✅ Menunggu persetujuan admin.');
    }
}