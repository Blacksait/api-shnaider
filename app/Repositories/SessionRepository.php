<?php
namespace App\Repositories;

use App\Models\Session;
use Illuminate\Support\Facades\DB;

class SessionRepository
{
    protected $session;

    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    public function getSessions()
    {
        return Session::orderBy('sort')->get();
    }

    public function getSessionsPersonal()
    {
        return auth()->user()->sessions;
    }
}
