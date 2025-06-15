<?php

namespace App\Http\Livewire\Mentor;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\MentorshipSession;
use Carbon\Carbon;

class SessionList extends Component
{
    use WithPagination;

    public $activeTab = 'upcoming';
    public $search = '';
    public $perPage = 10;
    public $sortField = 'start_time';
    public $sortDirection = 'asc';
    public $selectedSession = null;
    public $showSessionModal = false;

    protected $queryString = [
        'search' => ['except' => ''],
        'sortField' => ['except' => 'start_time'],
        'sortDirection' => ['except' => 'asc'],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }
        $this->sortField = $field;
    }

    public function viewSession($sessionId)
    {
        $this->selectedSession = MentorshipSession::with([
            'student', 
            'student.profile',
            'course',
            'review'
        ])->findOrFail($sessionId);
        
        $this->showSessionModal = true;
    }

    public function render()
    {
        $user = auth()->user();
        
        $query = $user->mentorSessions()
            ->with([
                'student', 
                'student.profile',
                'course',
                'review'
            ]);

        // Aplicar búsqueda
        if ($this->search) {
            $query->where(function($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%')
                  ->orWhereHas('student', function($q) {
                      $q->where('name', 'like', '%' . $this->search . '%');
                  });
            });
        }

        // Filtrar por pestaña activa
        switch ($this->activeTab) {
            case 'upcoming':
                $query->where('start_time', '>=', now())
                      ->where('status', 'scheduled')
                      ->orderBy($this->sortField, $this->sortDirection);
                break;
                
            case 'pending':
                $query->where('status', 'pending')
                      ->orderBy('created_at', 'desc');
                break;
                
            case 'past':
                $query->where('start_time', '<', now())
                      ->whereIn('status', ['completed', 'cancelled'])
                      ->orderBy($this->sortField, $this->sortDirection);
                break;
        }

        $sessions = $query->paginate($this->perPage);

        return view('livewire.mentor.session-list', [
            'sessions' => $sessions
        ]);
    }
}
