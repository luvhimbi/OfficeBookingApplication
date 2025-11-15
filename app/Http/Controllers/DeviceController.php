<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\SessionService;
use Illuminate\Support\Facades\Auth;

class DeviceController extends Controller
{
    protected $sessionService;

    public function __construct(SessionService $sessionService)
    {
        $this->sessionService = $sessionService;
    }

    public function index()
    {
        $sessions = $this->sessionService->getUserSessions(Auth::id());
        return view('auth.devices', compact('sessions'));
    }

    public function logout($id)
    {
        $this->sessionService->logoutSession($id);
        return redirect()->route('profile.devices')->with('success', 'Device/session logged out successfully.');
    }
}
