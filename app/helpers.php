<?php

use Illuminate\Support\Facades\Auth;
use App\Models\User;

if (!function_exists('isAdmin')) {
    /**
     * Verifica si el usuario actual es administrador
     *
     * @return bool
     */
    function isAdmin()
    {
        return Auth::check() && Auth::user()->hasRole('admin');
    }
}

if (!function_exists('isMentor')) {
    /**
     * Verifica si el usuario actual es un mentor
     *
     * @return bool
     */
    function isMentor()
    {
        return Auth::check() && Auth::user()->hasRole('mentor');
    }
}

if (!function_exists('isStudent')) {
    /**
     * Verifica si el usuario actual es un estudiante
     *
     * @return bool
     */
    function isStudent()
    {
        return Auth::check() && Auth::user()->hasRole('student');
    }
}

if (!function_exists('getUserRole')) {
    /**
     * Obtiene el rol principal del usuario actual
     *
     * @return string|null
     */
    function getUserRole()
    {
        if (!Auth::check()) {
            return null;
        }
        
        $user = Auth::user();
        return $user->getRoleNames()->first();
    }
}

if (!function_exists('formatDate')) {
    /**
     * Formatea una fecha al formato local
     *
     * @param  mixed  $date
     * @param  string  $format
     * @return string
     */
    function formatDate($date, $format = 'd/m/Y H:i')
    {
        if (!$date) {
            return '';
        }
        
        if (!$date instanceof \DateTime) {
            $date = new \DateTime($date);
        }
        
        return $date->format($format);
    }
}
