<?php

namespace App\Http\Controllers;

use App\Models\MenuContent;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MenuContentController extends Controller
{
    public function add(Request $request)
    {
        $validation = $request->validate([
            "name" => ["required", "string", "max:255"],
            "description" => ["required", "string"],
            "superset_id" => ["required", "string", "size:36"],
            "menu_id" => ["required", "integer", "exists:menus,id"],
            "visibility" => ["required", "in:public,private,off"],
        ]);

        MenuContent::create($validation);

        return response()->json(
            ["message" => "Menu Content added successfully."],
            Response::HTTP_OK,
        );
    }

    public function update(Request $request)
    {
        $validation = $request->validate([
            "id" => ["required", "string"],
            "name" => [
                "required_without:description,superset_id,menu_id,visibility",
                "string",
                "max:255",
            ],
            "description" => [
                "required_without:name,superset_id,menu_id,visibility",
                "string",
            ],
            "superset_id" => [
                "required_without:description,name,menu_id,visibility",
                "string",
                "size:36",
            ],
            "menu_id" => [
                "required_without:description,superset_id,name,visibility",
                "integer",
                "exists:menus,id",
            ],
            "visibility" => [
                "required_without:description,superset_id,menu_id,name",
                "in:public,private,off",
            ],
        ]);

        $menu = MenuContent::find($validation["id"]);

        if (!$menu) {
            return response()->json(
                ["message" => "Menu Content does not exist."],
                Response::HTTP_NOT_FOUND,
            );
        }

        $menu->update(
            collect($validation)
                ->only([
                    "name",
                    "description",
                    "superset_id",
                    "menu_id",
                    "visibility",
                ])
                ->all(),
        );

        return response()->json([
            "message" => "Menu Content updated successfully.",
        ]);
    }

    public function delete(Request $request)
    {
        $validation = $request->validate([
            "id" => ["required", "string"],
        ]);

        $findExist = MenuContent::where([
            "id" => $validation["id"],
        ])->exists();

        if (!$findExist) {
            return response()->json(
                ["message" => "Menu Content does not exist."],
                Response::HTTP_NOT_FOUND,
            );
        }

        MenuContent::destroy((int) $validation["id"]);

        return response()->json(
            ["message" => "Menu Content deleted successfully."],
            Response::HTTP_OK,
        );
    }

    public function show(Request $request, ?string $id = null)
    {
        if ($id != null) {
            $findExist = MenuContent::where([
                "id" => $id,
            ])->exists();

            if (!$findExist) {
                return response()->json(
                    ["message" => "Menu Content does not exist."],
                    Response::HTTP_NOT_FOUND,
                );
            }

            return response()->json(
                ["data" => MenuContent::find((int) $id)],
                Response::HTTP_OK,
            );
        }

        return response()->json(
            ["data" => MenuContent::all()],
            Response::HTTP_OK,
        );
    }
}
