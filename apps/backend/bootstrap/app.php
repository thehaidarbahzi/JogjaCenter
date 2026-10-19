<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . "/../routes/web.php",
        api: __DIR__ . "/../routes/api.php",
        commands: __DIR__ . "/../routes/console.php",
        health: "/up",
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->redirectGuestsTo(null);

        $middleware->alias([
            "role" => \App\Http\Middleware\RequireRole::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn(Request $request) => $request->is("api/*") ||
                $request->expectsJson(),
        );

        $exceptions->render(function (
            MethodNotAllowedHttpException $e,
            Request $request,
        ): ?JsonResponse {
            if (!$request->is("api/*")) {
                return null;
            }

            return response()->json(
                [
                    "message" => "HTTP method not allowed.",
                ],
                Response::HTTP_METHOD_NOT_ALLOWED,
            );
        });

        $exceptions->render(function (
            NotFoundHttpException $e,
            Request $request,
        ): ?JsonResponse {
            if (!$request->is("api/*")) {
                return null;
            }

            return response()->json(
                [
                    "message" => "Resource not found.",
                ],
                Response::HTTP_NOT_FOUND,
            );
        });
    })
    ->create();
