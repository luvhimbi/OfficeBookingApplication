<?php

namespace App\Http\Controllers;

use App\Mail\UserInviteMail;
use App\Models\Invite;
use Exception;
use Illuminate\Http\Request;
use App\Services\InviteService;
use Illuminate\Support\Facades\Mail;

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
    public function resend(Invite $invite)
    {
        try {

            if ($invite->expires_at && now()->greaterThan($invite->expires_at)) {
                return redirect()->back()->with('error', 'This invite has expired and cannot be resent.');
            }

            Mail::to($invite->email)->send(new UserInviteMail($invite->token));

            return redirect()->back()->with('success', 'Invite resent successfully.');

        } catch (\Swift_TransportException $ex) {

            // This usually means SMTP/network is down
            return redirect()->back()->with('error', 'Failed to send email due to network or mail server issues. Please try again.');

        } catch (Exception $ex) {

            // Optional: Log for debugging
            \Log::error('Invite resend failed: ' . $ex->getMessage());

            return redirect()->back()->with('error', 'Something went wrong while sending the email.');
        }
    }
}
