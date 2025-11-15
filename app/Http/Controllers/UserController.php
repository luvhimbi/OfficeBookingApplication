<?php

namespace App\Http\Controllers;


use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $search = strtolower($request->input('search')); // Convert to lowercase

        $users = User::select('firstname', 'lastname', 'position', 'role', 'email')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where(DB::raw('LOWER(firstname)'), 'like', "%{$search}%")
                        ->orWhere(DB::raw('LOWER(lastname)'), 'like', "%{$search}%")
                        ->orWhere(DB::raw('LOWER(email)'), 'like', "%{$search}%")
                        ->orWhere(DB::raw('LOWER(role)'), 'like', "%{$search}%")
                        ->orWhere(DB::raw('LOWER(position)'), 'like', "%{$search}%");
                });
            })
            ->orderBy('firstname')
            ->paginate(5);

        return view('admin.users.index', compact('users', 'search'));
    }
}
