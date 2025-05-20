<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ActivityLog;

class ActivityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $activities = ActivityLog::with('user')->latest()->paginate(20);
        return view('admin.activities.index', compact('activities'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'subject' => $request->subject,
            'description' => $request->description,
        ]);

        return redirect()->back()->with('success', 'Actividad registrada exitosamente');
    }
}
