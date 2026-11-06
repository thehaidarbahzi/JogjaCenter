<?php

namespace App\Http\Controllers;

use App\Mail\UserInvite;
use App\Models\Invitation;
use App\Models\Role;
use App\Models\User;
use App\Models\UserRole;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    public function invite(Request $request)
    {
        $validation = $request->validate([
            "email" => ["required", "email", "disposable_email"],
            "role" => ["required", "integer", "exists:roles,id"],
            "inviter" => ["required", "integer", "exists:users,id"],
        ]);

        $findRole = Role::where(["id" => $validation["role"]])->exists();
        $findUser = User::where(["id" => $validation["inviter"]])->exists();

        if (!$findRole || !$findUser) {
            return response()->json(
                ["message" => "Invitation request is invalid."],
                Response::HTTP_NOT_FOUND,
            );
        }

        $expire = CarbonImmutable::now()->add(1, "day");
        $id = str()->random(64);

        Invitation::create([
            "id" => $id,
            "email" => $validation["email"],
            "invited_by" => (int) $validation["inviter"],
            "role_id" => (int) $validation["role"],
            "expire_at" => $expire,
        ]);

        Mail::to($validation["email"])->queue(new UserInvite($id))->render();

        return response()->json(
            ["message" => "Invitation has been sent."],
            Response::HTTP_OK,
        );
    }

    public function validateInvitation(Request $request)
    {
        $validation = $request->validate([
            "id" => ["required", "string"],
        ]);

        $invitation = Invitation::where("id", $validation["id"])
            ->where("expire_at", ">", CarbonImmutable::now())
            ->first();

        if (!$invitation) {
            return response()->json(
                ["message" => "Invitation is invalid or expired."],
                Response::HTTP_NOT_FOUND,
            );
        }

        return response()->json(
            ["message" => "Invitation accepted. You can now create account."],
            Response::HTTP_OK,
        );
    }

    public function acceptInvitation(Request $request)
    {
        $validation = $request->validate([
            "id" => ["required", "string"],
            "fullname" => ["required", "string", "max:100"],
            "username" => [
                "required",
                "string",
                "min:5",
                "max:30",
                Rule::unique("users", "username")->withoutTrashed(),
            ],
            "password" => [
                "required",
                "confirmed",
                "string",
                Password::min(8)->mixedCase()->numbers(),
            ], // tambahan body password_confirmation
        ]);

        $invitation = Invitation::where("id", $validation["id"])
            ->where("expire_at", ">", CarbonImmutable::now())
            ->first();

        if (!$invitation) {
            return response()->json(
                ["message" => "Invitation is invalid or expired."],
                Response::HTTP_NOT_FOUND,
            );
        }

        $user = User::where("email", $invitation->email)->first();

        if ($user) {
            return response()->json(
                ["message" => "User aleady exists."],
                Response::HTTP_CONFLICT,
            );
        }

        $user = User::create([
            "username" => $validation["username"],
            "fullname" => $validation["fullname"],
            "email" => $invitation->email,
            "password" => $validation["password"],
        ]);

        UserRole::create([
            "user_id" => (int) $user->id,
            "role_id" => (int) $invitation->role_id,
        ]);

        $invitation->update(["accepted_at" => CarbonImmutable::now()]);

        activity("invitations")
            ->causedBy($user)
            ->performedOn($invitation)
            ->withProperties(["user_id" => (int) $user->id])
            ->log("User accepted the invitation and joined the system");

        return response()->json(
            ["message" => "Invitation accepted. You can now login."],
            Response::HTTP_OK,
        );
    }
}
