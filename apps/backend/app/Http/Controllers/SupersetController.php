<?php

namespace App\Http\Controllers;

use App\Models\Superset;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SupersetController extends Controller
{
    public function add(Request $request)
    {
        $validation = $request->validate([
            "host" => ["required", "string"],
            "username" => ["required", "string"],
            "password" => ["required", "string"],
        ]);

        Superset::create([
            "host" => $validation["host"],
            "username" => $validation["username"],
            "password" => $validation["password"],
        ]);

        return response()->json(
            ["message" => "Superset added successfully."],
            Response::HTTP_OK,
        );
    }

    public function update(Request $request)
    {
        $validation = $request->validate([
            "id" => ["required", "string"],
            "host" => ["required_without:username,password", "string"],
            "username" => ["required_without:host,password", "string"],
            "password" => ["required_without:username,host", "string"],
        ]);

        $superset = Superset::find($validation["id"]);

        if (!$superset) {
            return response()->json(
                ["message" => "Superset does not exist."],
                Response::HTTP_NOT_FOUND,
            );
        }

        $superset->update(
            collect($validation)
                ->only(["host", "username", "password"])
                ->all(),
        );

        return response()->json([
            "message" => "Superset updated successfully.",
        ]);
    }

    public function delete(Request $request)
    {
        $validation = $request->validate([
            "id" => ["required", "string"],
        ]);

        $findExist = Superset::where([
            "id" => $validation["id"],
        ])->exists();

        if (!$findExist) {
            return response()->json(
                ["message" => "Superset does not exist."],
                Response::HTTP_NOT_FOUND,
            );
        }

        Superset::destroy((int) $validation["id"]);

        return response()->json(
            ["message" => "Superset deleted successfully."],
            Response::HTTP_OK,
        );
    }

    public function show(Request $request, ?string $id = null)
    {
        if ($id != null) {
            $findExist = Superset::where([
                "id" => $id,
            ])->exists();

            if (!$findExist) {
                return response()->json(
                    ["message" => "Superset does not exist."],
                    Response::HTTP_NOT_FOUND,
                );
            }

            return response()->json(
                ["data" => Superset::find((int) $id)],
                Response::HTTP_OK,
            );
        }

        return response()->json(["data" => Superset::all()], Response::HTTP_OK);
    }
}
