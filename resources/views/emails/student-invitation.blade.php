@component('mail::layout')
{{-- Header --}}
@slot('header')
    @component('mail::header', ['url' => config('app.url')])
        MentorHub
    @endcomponent
@endslot

# ¡Hola!

{{ $mentor }} te ha invitado a unirte a MentorHub como estudiante.

@component('mail::button', ['url' => $url])
    Aceptar invitación
@endcomponent

El enlace de invitación expirará en 7 días.

{{-- Footer --}}
@slot('footer')
    @component('mail::footer')
        © {{ date('Y') }} MentorHub. Todos los derechos reservados.
    @endcomponent
@endslot
@endcomponent
