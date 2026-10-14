<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MenuContentController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\PreferenceController;
use App\Http\Controllers\SupersetController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix("auth")->group(function () {
    Route::post("/login", [AuthController::class, "login"])->name("login");
    Route::post("/request-reset", [AuthController::class, "generateReset"]);
    Route::post("/validate-reset", [AuthController::class, "validateReset"]);
    Route::post("/reset", [AuthController::class, "resetUser"]);
    Route::post("/register/invitation", [
        UserController::class,
        "acceptInvitation",
    ]);
});

Route::middleware("auth:sanctum")->group(function () {
    Route::prefix("auth")->group(function () {
        Route::post("/logout", [AuthController::class, "logout"]);
    });
});

Route::middleware(["auth:sanctum", "role:superadmin"])->group(function () {
    Route::prefix("app")->group(function () {
        Route::prefix("user")->group(function () {
            Route::post("/invite", [UserController::class, "invite"]);
        });

        Route::prefix("setting")->group(function () {
            Route::post("/preference", [PreferenceController::class, "add"]);
            Route::put("/preference", [PreferenceController::class, "update"]);
            Route::delete("/preference", [
                PreferenceController::class,
                "delete",
            ]);
            Route::get("/preference/{id?}", [
                PreferenceController::class,
                "show",
            ]);

            Route::post("/superset", [SupersetController::class, "add"]);
            Route::put("/superset", [SupersetController::class, "update"]);
            Route::delete("/superset", [SupersetController::class, "delete"]);
            Route::get("/superset/{id?}", [SupersetController::class, "show"]);
        });

        Route::prefix("dashboard")->group(function () {
            Route::post("/menu", [MenuController::class, "add"]);
        });
    });
});

Route::middleware(["auth:sanctum", "role:superadmin|admin"])->group(
    function () {
        Route::prefix("app")->group(function () {
            Route::prefix("dashboard")->group(function () {
                Route::put("/menu", [MenuController::class, "update"]);
                Route::delete("/menu", [MenuController::class, "delete"]);
                Route::get("/menu/{id?}", [MenuController::class, "show"]);

                Route::post("/content", [MenuContentController::class, "add"]);
                Route::delete("/content", [
                    MenuContentController::class,
                    "delete",
                ]);
                Route::get("/content/{id?}", [
                    MenuContentController::class,
                    "show",
                ]);
                Route::put("/content", [
                    MenuContentController::class,
                    "update",
                ]);
            });
        });
    },
);
