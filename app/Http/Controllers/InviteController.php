<?php
namespace App\Http\Controllers;


use App\Mail\UserInviteMail;
use App\Models\Invite;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class InviteController extends Controller
{
    public function create()
    {
        $invites = Invite::latest()->take(8)->get(); // recent 8 invites
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

$token = Str::random(40);

Invite::create([
'email' => $request->email,
'firstname' => $request->firstname,
'lastname' => $request->lastname,
'role' => $request->role,
'token' => $token
]);

// send invite link
Mail::to($request->email)->send(new UserInviteMail($token));

return redirect()->back()->with('success','Invite sent successfully.');
}

public function accept($token)
{
$invite = Invite::where('token', $token)->firstOrFail();

if ($invite->used) {
return redirect('/login')->with('error', 'This invitation has already been used.');
}

return view('auth.invite-set-password', compact('invite'));
}

    public function complete(Request $request, $token)
    {
        $invite = Invite::where('token', $token)->firstOrFail();

        $request->validate([
            'password' => 'required|min:6|confirmed'
        ]);

        // Create the user
        $user = User::create([
            'firstname' => $invite->firstname,
            'lastname' => $invite->lastname,
            'email' => $invite->email,
            'role' => $invite->role,
            'password' => Hash::make($request->password),
        ]);

        // Mark invite as used
        $invite->update(['used' => true]);

        // Log user in
        Auth::login($user);


        // EXACT structure as you requested
        if ($user->role == 'admin') {
            return redirect()->route('admin.dashboard');
        }

        if ($user->role == 'employee') {
            return redirect()->route('employee.dashboard');
        }

    }

    public function index()
    {
        $invites = Invite::orderBy('created_at', 'desc')->paginate(10);

        return view('admin.users.manage-invites', compact('invites'));
    }

    public function destroy(Invite $invite)
    {
        $invite->delete();

        return redirect()->route('admin.invites.index')
            ->with('success', 'Invite deleted successfully.');
    }


}
