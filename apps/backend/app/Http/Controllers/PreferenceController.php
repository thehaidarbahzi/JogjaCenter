<?php

namespace App\Http\Controllers;

use App\Models\Preference;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PreferenceController extends Controller
{
    public function add(Request $request)
    {
        $validation = $request->validate([
            "key" => ["required", "string"],
            "value" => ["required", "string"],
        ]);

        $findExist = Preference::where([
            "key" => $validation["key"],
        ])->exists();

        if ($findExist) {
            return response()->json(
                ["message" => "Preference already exist."],
                Response::HTTP_CONFLICT,
            );
        }

        Preference::create([
            "key" => $validation["key"],
            "value" => $validation["value"],
        ]);

        return response()->json(
            ["message" => "Preference added successfully."],
            Response::HTTP_OK,
        );
    }

    public function update(Request $request)
    {
        $validation = $request->validate([
            "id" => ["required", "string"],
            "key" => ["required_without:value", "string"],
            "value" => ["required_without:key", "string"],
        ]);

        $preference = Preference::find($validation["id"]);

        if (!$preference) {
            return response()->json(
                ["message" => "Preference does not exist."],
                Response::HTTP_NOT_FOUND,
            );
        }

        $preference->update(
            collect($validation)
                ->only(["key", "value"])
                ->all(),
        );

        return response()->json([
            "message" => "Preference updated successfully.",
        ]);
    }

    public function delete(Request $request)
    {
        $validation = $request->validate([
            "id" => ["required", "string"],
        ]);

        $findExist = Preference::where([
            "id" => $validation["id"],
        ])->exists();

        if (!$findExist) {
            return response()->json(
                ["message" => "Preference does not exist."],
                Response::HTTP_NOT_FOUND,
            );
        }

        Preference::destroy((int) $validation["id"]);

        return response()->json(
            ["message" => "Preference deleted successfully."],
            Response::HTTP_OK,
        );
    }

    public function show(Request $request, ?string $id = null)
    {
        if ($id != null) {
            $findExist = Preference::where([
                "id" => $id,
            ])->exists();

            if (!$findExist) {
                return response()->json(
                    ["message" => "Preference does not exist."],
                    Response::HTTP_NOT_FOUND,
                );
            }

            return response()->json(
                ["data" => Preference::find((int) $id)],
                Response::HTTP_OK,
            );
        }

        return response()->json(
            ["data" => Preference::all()],
            Response::HTTP_OK,
        );
    }
}
