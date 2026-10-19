<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ActivityController extends Controller
{
    public function show(Request $request, ?string $id = null)
    {
        if ($id !== null) {
            $activity = Activity::find((int) $id);

            if (!$activity) {
                return response()->json(
                    ["message" => "Activity log does not exist."],
                    Response::HTTP_NOT_FOUND,
                );
            }

            return response()->json(
                ["data" => $activity],
                Response::HTTP_OK,
            );
        }

        $validation = $request->validate([
            "log_name" => ["nullable", "string"],
            "event" => ["nullable", "string"],
            "causer_id" => ["nullable", "integer"],
            "subject_type" => ["nullable", "string"],
            "from" => ["nullable", "date"],
            "to" => ["nullable", "date"],
            "per_page" => ["nullable", "integer", "min:1", "max:100"],
        ]);

        $activities = Activity::query()
            ->when(
                $validation["log_name"] ?? null,
                fn ($query, $logName) => $query->inLog($logName),
            )
            ->when(
                $validation["event"] ?? null,
                fn ($query, $event) => $query->forEvent($event),
            )
            ->when(
                $validation["causer_id"] ?? null,
                fn ($query, $causerId) =>
                    $query->where("causer_id", (int) $causerId),
            )
            ->when(
                $validation["subject_type"] ?? null,
                fn ($query, $subjectType) =>
                    $query->where("subject_type", $subjectType),
            )
            ->when(
                $validation["from"] ?? null,
                fn ($query, $from) => $query->where("created_at", ">=", $from),
            )
            ->when(
                $validation["to"] ?? null,
                fn ($query, $to) =>
                    $query->where("created_at", "<=", $to . " 23:59:59"),
            )
            ->latest()
            ->paginate($validation["per_page"] ?? 15);

        return response()->json(
            [
                "data" => $activities->items(),
                "meta" => [
                    "current_page" => $activities->currentPage(),
                    "per_page" => $activities->perPage(),
                    "total" => $activities->total(),
                    "last_page" => $activities->lastPage(),
                ],
            ],
            Response::HTTP_OK,
        );
    }
}
