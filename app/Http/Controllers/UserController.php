<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;

class UserController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', User::class);

        $users = User::all()->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                // أي بيانات أخرى تريدين عرضها
                'can_update' => Gate::allows('update', $user),
                'can_delete' => Gate::allows('delete', $user),
            ];
        });
        return Inertia::render('User/Index', [
            'users' => $users,
            'can_viewAny' => Gate::allows('viewAny', User::class),
            'can_create' => Gate::allows('create', User::class),
        ]);
    }

    public function create()
    {
        $this->authorize('create', User::class);

        return Inertia::render('User/Create');
    }
}
