<?php

namespace App\Http\Controllers;

use App\Role;
use App\User;
use App\Http\Services\UserService;

use Illuminate\Support\Facades\Validator;


class UsersController extends Controller
{
    /**
     * The user service instance.
     *
     * @var UserService
     */
    private $userService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(UserService $userService)
    {
        $this->middleware('auth');
        $this->userService = $userService;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $users = User::with('roles')->paginate(10);
        return view('users.index', compact('users'));
    }

    public function create()
    {
        $title = 'New user';
        $button = 'Create';
        $policy = 'createUser';
        $route = route('users.store');
        $routeMethod = 'POST';
        $user = new User();
        $roles = Role::all();

        return view('users.form', compact('title', 'button', 'policy', 'route', 'routeMethod', 'user', 'roles'));
    }

    public function store()
    {
        $user = $this->userService->processUser();
        $roles = $this->userService->validateRoles();

        if (count($roles['roles']) > 0) {
            $user->roles()->attach($roles['roles']);
        }

        return redirect(route("users.index"));
    }

    public function edit($id)
    {
        $title = 'Edit user';
        $button = 'Save';
        $policy = 'editUser';
        $route = route('users.update', $id);
        $routeMethod = 'PATCH';
        $user = User::findOrFail($id);
        $roles = Role::all();

        return view('users.form', compact('title', 'button', 'policy', 'route', 'routeMethod', 'user', 'roles'));
    }

    public function update($id)
    {
        $user = $this->userService->processUser($id);
        $roles = $this->userService->validateRoles();

        foreach ($user->roles as $role) {
            // If the role is not in the new roles, we mark it as deleted
            if (!in_array($role->id, $roles['roles'])) {
                $user->roles()->updateExistingPivot($role->id, ['deleted_at' => now()]);
            }
        }

        foreach ($roles['roles'] as $role) {
            if ($user->roleswithtrashed()->where('role_user.role_id', $role)->exists()) {
                // If the role already exists, we just update the deleted_at field
                $user->roles()->updateExistingPivot($role, ['deleted_at' => null]);
                continue;
            } else {
                // If the role does not exist, we attach it
                $user->roles()->attach($role);
            }
        }

        return redirect(route("users.index"));
    }
}
