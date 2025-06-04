<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Mentor\MentorshipSessionController as BaseMentorshipSessionController;

class SessionController extends BaseMentorshipSessionController
{
    /**
     * Get the view name for the sessions
     *
     * @param string $view
     * @return string
     */
    protected function getViewName($view)
    {
        return "mentor.sessions.{$view}";
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $response = parent::index();
        
        // Si la respuesta es una vista, devolvemos la vista de sessions
        if (method_exists($response, 'getData')) {
            $data = $response->getData();
            return view($this->getViewName('index'), (array) $data);
        }
        
        return $response;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // Get the data from the parent's create method
        $mentorId = \Illuminate\Support\Facades\Auth::id();

        // 1. Obtener IDs de los cursos activos del mentor
        $mentorCourseIds = \App\Models\Course::where('teacher_id', $mentorId)
            ->where('is_active', true)
            ->pluck('id');

        // 2. Obtener estudiantes que ya han tenido sesiones con el mentor
        $studentsFromSessions = \App\Models\User::whereHas('roles', function($query) {
                $query->where('name', 'student');
            })
            ->whereIn('id', function($query) use ($mentorId) {
                $query->select('student_id')
                      ->from('mentor_sessions')
                      ->where('mentor_id', $mentorId);
            })
            ->with('profile')
            ->get();

        // 3. Obtener estudiantes inscritos en los cursos activos del mentor
        $studentsFromEnrollments = collect(); // Inicializar como colección vacía
        if ($mentorCourseIds->isNotEmpty()) {
            $studentsFromEnrollments = \App\Models\User::whereHas('roles', function($query) {
                    $query->where('name', 'student');
                })
                ->whereHas('enrollments', function($enrollmentQuery) use ($mentorCourseIds) {
                    $enrollmentQuery->whereIn('course_id', $mentorCourseIds);
                })
                ->with('profile')
                ->get();
        }

        // 4. Combinar, eliminar duplicados y formatear para el desplegable
        $allPotentialMentees = $studentsFromSessions->merge($studentsFromEnrollments)->unique('id');

        $menteesListForDropdown = $allPotentialMentees->mapWithKeys(function($user) {
            // Intentar obtener el nombre de usuario del perfil, si no, el email, y como último recurso un texto genérico
            $identifier = $user->profile->username ?? $user->email ?? 'ID: ' . $user->id;
            return [$user->id => $user->name . ' (' . $identifier . ')'];
        });

        $coursesListForDropdown = \App\Models\Course::where('teacher_id', $mentorId)
            ->where('is_active', true)
            ->pluck('name', 'id')
            ->prepend('-- Selecciona un curso (opcional) --', '');

        $dataToPass = [
            'mentees' => $menteesListForDropdown,
            'courses' => $coursesListForDropdown
        ];
        
        return view($this->getViewName('create'), $dataToPass);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $session = \App\Models\MentorshipSession::findOrFail($id);
        return view($this->getViewName('show'), compact('session'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $session = \App\Models\MentorshipSession::findOrFail($id);
        return view($this->getViewName('edit'), compact('session'));
    }
}
