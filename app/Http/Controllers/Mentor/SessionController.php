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
        return view($this->getViewName('create'));
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
