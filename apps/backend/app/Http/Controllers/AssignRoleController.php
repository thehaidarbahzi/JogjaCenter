<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AssignRoleController extends Controller
{
    public function assign(Request $request)
    {
        $validated = $request->validate([
            "user_id" => ["required", "integer", "exists:users,id"],
            "role_id" => ["required", "integer", "exists:roles,id"],
        ]);

        $user = User::findOrFail($validated["user_id"]);
        $role = Role::findOrFail($validated["role_id"]);

        $user->roles()->syncWithoutDetaching([$role->id]);

        return response()->json(
            [
                "message" => "Role assigned successfully",
                "user" => $user->load("roles"),
            ],
            Response::HTTP_OK,
        );
    }

    public function remove(Request $request)
    {
        $validated = $request->validate([
            "user_id" => ["required", "integer", "exists:users,id"],
            "role_id" => ["required", "integer", "exists:roles,id"],
        ]);

        $user = User::findOrFail($validated["user_id"]);

        $user->roles()->detach($validated["role_id"]);

        return response()->json(
            [
                "message" => "Role removed successfully",
                "user" => $user->load("roles"),
            ],
            Response::HTTP_OK,
        );
    }

    public function listUserRoles(int $userId)
    {
        $user = User::with("roles")->findOrFail($userId);
        return response()->json($user->roles, Response::HTTP_OK);
    }
}
