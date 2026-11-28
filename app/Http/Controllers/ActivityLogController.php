<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Schema;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $hasTable = Schema::hasTable('activity_logs');

        if (! $hasTable) {
            $logs = new LengthAwarePaginator([], 0, 15);
            $users = collect();
            $moduls = collect();

            return view('admin.log-aktivitas.index', compact('logs', 'users', 'moduls', 'hasTable'));
        }

        $query = ActivityLog::with('user')->latest();

        if ($search = $request->input('cari')) {
            $query->where('deskripsi', 'like', "%{$search}%");
        }

        if ($modul = $request->input('modul')) {
            $query->where('modul', $modul);
        }

        if ($userId = $request->input('user_id')) {
            $query->where('user_id', $userId);
        }

        if ($dari = $request->input('dari_tanggal')) {
            $query->whereDate('created_at', '>=', $dari);
        }

        if ($sampai = $request->input('sampai_tanggal')) {
            $query->whereDate('created_at', '<=', $sampai);
        }

        $logs = $query->paginate(15)->withQueryString();
        $users = User::orderBy('name')->get(['id', 'name', 'role']);
        $moduls = ActivityLog::select('modul')->distinct()->orderBy('modul')->pluck('modul');

        return view('admin.log-aktivitas.index', compact('logs', 'users', 'moduls', 'hasTable'));
    }
}
