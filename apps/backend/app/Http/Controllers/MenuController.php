<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MenuController extends Controller
{
    public function add(Request $request)
    {
        $validation = $request->validate([
            "name" => ["required", "string"],
            "description" => ["required", "string"],
            "visibility" => ["required", "in:public,private,off"],
        ]);

        Menu::create([
            "name" => $validation["name"],
            "description" => $validation["description"],
            "visibility" => $validation["visibility"],
        ]);

        return response()->json(
            ["message" => "Menu added successfully."],
            Response::HTTP_OK,
        );
    }

    public function update(Request $request)
    {
        $validation = $request->validate([
            "id" => ["required", "string"],
            "name" => ["required_without:description,visibility", "string"],
            "description" => ["required_without:name,visibility", "string"],
            "visibility" => [
                "required_without:name,description",
                "in:public,private,off",
            ],
        ]);

        $menu = Menu::find($validation["id"]);

        if (!$menu) {
            return response()->json(
                ["message" => "Menu does not exist."],
                Response::HTTP_NOT_FOUND,
            );
        }

        $menu->update(
            collect($validation)
                ->only(["name", "description", "visibility"])
                ->all(),
        );

        return response()->json([
            "message" => "Menu updated successfully.",
        ]);
    }

    public function delete(Request $request)
    {
        $validation = $request->validate([
            "id" => ["required", "string"],
        ]);

        $findExist = Menu::where([
            "id" => $validation["id"],
        ])->exists();

        if (!$findExist) {
            return response()->json(
                ["message" => "Menu does not exist."],
                Response::HTTP_NOT_FOUND,
            );
        }

        Menu::destroy((int) $validation["id"]);

        return response()->json(
            ["message" => "Menu deleted successfully."],
            Response::HTTP_OK,
        );
    }

    public function show(Request $request, ?string $id = null)
    {
        if ($id != null) {
            $findExist = Menu::where([
                "id" => $id,
            ])->exists();

            if (!$findExist) {
                return response()->json(
                    ["message" => "Menu does not exist."],
                    Response::HTTP_NOT_FOUND,
                );
            }

            return response()->json(
                ["data" => Menu::find((int) $id)],
                Response::HTTP_OK,
            );
        }

        return response()->json(["data" => Menu::all()], Response::HTTP_OK);
    }
}
