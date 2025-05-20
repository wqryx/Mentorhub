<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CertificateController extends Controller
{
    /**
     * Display a listing of the user's certificates.
     */
    public function index()
    {
        $user = Auth::user();
        $certificates = $user->certificates()->latest()->get();
        
        return view('certificates.index', compact('certificates'));
    }

    /**
     * Display the specified certificate.
     */
    public function show(Certificate $certificate)
    {
        // Verificar que el usuario tiene acceso a este certificado
        if ($certificate->user_id !== Auth::id()) {
            abort(403, 'No tienes acceso a este certificado');
        }

        return view('certificates.show', compact('certificate'));
    }
}
