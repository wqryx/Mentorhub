<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactFormSubmitted;
use Illuminate\Support\Facades\Session;

class ContactController extends Controller
{
    /**
     * Muestra el formulario de contacto.
     *
     * @return \Illuminate\View\View
     */
    public function show()
    {
        return view('welcome');
    }

    /**
     * Maneja el envío del formulario de contacto.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        // Guardar el mensaje en la base de datos
        $contact = Contact::create($validated);

        // Aquí podrías enviar una notificación al administrador
        // Mail::to('admin@mentorhub.com')->send(new ContactFormSubmitted($contact));

        // Redirigir de vuelta con un mensaje de éxito
        return redirect()->back()->with('success', '¡Gracias por tu mensaje! Nos pondremos en contacto contigo pronto.');
    }
}
