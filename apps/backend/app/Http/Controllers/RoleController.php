<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::all();
        return response()->json($roles, Response::HTTP_OK);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            "name" => ["required", "string", "max:15", "unique:roles,name"],
        ]);

        $role = Role::create($validated);
        return response()->json($role, Response::HTTP_CREATED);
    }

    public function show(Role $role)
    {
        return response()->json($role, Response::HTTP_OK);
    }
}
