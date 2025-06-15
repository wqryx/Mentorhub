<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$mentorSessions = DB::table('mentorship_sessions')
    ->where('mentor_id', 3)
    ->orWhere('mentee_id', 3)
    ->get();

echo "Sesiones de mentoría para el usuario con ID 3:\n";
echo json_encode($mentorSessions, JSON_PRETTY_PRINT) . "\n";

// Verificar también en la tabla course_user
echo "\nCursos del usuario con ID 3:\n";
$enrollments = DB::table('course_user')
    ->where('user_id', 3)
    ->join('courses', 'course_user.course_id', '=', 'courses.id')
    ->select('courses.id', 'courses.name', 'course_user.*')
    ->get();

echo json_encode($enrollments, JSON_PRETTY_PRINT) . "\n";
