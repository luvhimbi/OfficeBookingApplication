<?php

namespace App\Http\Controllers;

use App\Models\Invite;
use Illuminate\Http\Request;
use App\Services\InviteService;

class InviteController extends Controller
{
    protected $inviteService;

    public function __construct(InviteService $inviteService)
    {
        $this->inviteService = $inviteService;
    }

    public function create()
    {
        $invites = $this->inviteService->recentInvites();
        return view('admin.users.invite', compact('invites'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:invites,email',
            'firstname' => 'required',
            'lastname' => 'required',
            'role' => 'required'
        ]);

        $this->inviteService->createInvite($request->only(['email','firstname','lastname','role']));

        return redirect()->back()->with('success','Invite sent successfully.');
    }

    public function accept($token)
    {
        $invite = $this->inviteService->getInviteByToken($token);

        if (!$invite || $invite->used) {
            return redirect('/login')->with('error', 'This invitation has already been used or is invalid.');
        }

        return view('auth.invite-set-password', compact('invite'));
    }

    public function complete(Request $request, $token)
    {
        $invite = $this->inviteService->getInviteByToken($token);

        if (!$invite || $invite->used) {
            return redirect('/login')->with('error', 'This invitation has already been used or is invalid.');
        }

        $request->validate([
            'password' => 'required|min:6|confirmed'
        ]);

        $user = $this->inviteService->completeInvite($invite, $request->password);

        // Role-based redirection
        if ($user->role == 'admin') {
            return redirect()->route('admin.dashboard');
        }

        if ($user->role == 'employee') {
            return redirect()->route('employee.dashboard');
        }
    }

    public function index()
    {
        $invites = $this->inviteService->paginateInvites();
        return view('admin.users.manage-invites', compact('invites'));
    }

    public function destroy(Invite $invite)
    {
        $this->inviteService->deleteInvite($invite);

        return redirect()->route('admin.invites.index')
            ->with('success', 'Invite deleted successfully.');
    }
}
