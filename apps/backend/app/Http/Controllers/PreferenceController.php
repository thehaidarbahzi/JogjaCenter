<?php

namespace App\Http\Controllers;

use App\Models\Preference;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use RuntimeException;
use Symfony\Component\HttpFoundation\Response;

class PreferenceController extends Controller
{
    public function add(Request $request)
    {
        if ($this->hasInvalidUpload($request)) {
            return response()->json(
                ["message" => "The value failed to upload."],
                Response::HTTP_UNPROCESSABLE_ENTITY,
            );
        }

        $type = $request->input("type");
        $key = (string) $request->input("key", "");

        $rules = [
            "key" => ["required", "string", "max:255"],
            "type" => ["required", "in:text,image"],
        ];

        if ($type === "image") {
            $rules["value"] = array_merge(
                ["required", "file"],
                $this->imageRules($key),
            );
        } else {
            $rules["value"] = ["required", "string"];
        }

        $validation = $request->validate($rules);

        $findExist = Preference::where([
            "key" => $validation["key"],
        ])->exists();

        if ($findExist) {
            return response()->json(
                ["message" => "Preference already exist."],
                Response::HTTP_CONFLICT,
            );
        }

        $value = $validation["value"];

        if ($validation["type"] === "image") {
            try {
                $value = $this->storeImage(
                    $validation["value"],
                    $validation["key"],
                );
            } catch (RuntimeException $e) {
                return response()->json(
                    ["message" => "Image could not be processed."],
                    Response::HTTP_UNPROCESSABLE_ENTITY,
                );
            }
        }

        try {
            $preference = Preference::create([
                "key" => $validation["key"],
                "value" => $value,
                "type" => $validation["type"],
            ]);
        } catch (\Throwable $e) {
            if ($validation["type"] === "image") {
                $this->deleteStoredImage($value);
            }

            throw $e;
        }

        return response()->json(
            [
                "message" => "Preference added successfully.",
                "data" => $preference,
            ],
            Response::HTTP_OK,
        );
    }

    public function update(Request $request)
    {
        $validation = $request->validate([
            "id" => ["required", "integer"],
            "key" => [
                "sometimes",
                "required_without_all:value,type",
                "string",
                "max:255",
                Rule::unique("preferences", "key")->ignore(
                    $request->input("id"),
                ),
            ],
            "type" => [
                "sometimes",
                "required_without_all:key,value",
                "in:text,image",
            ],
        ]);

        $preference = Preference::find($validation["id"]);

        if (!$preference) {
            return response()->json(
                ["message" => "Preference does not exist."],
                Response::HTTP_NOT_FOUND,
            );
        }

        if ($this->hasInvalidUpload($request)) {
            return response()->json(
                ["message" => "The value failed to upload."],
                Response::HTTP_UNPROCESSABLE_ENTITY,
            );
        }

        $newType = $validation["type"] ?? $preference->type;
        $newKey = $validation["key"] ?? $preference->key;
        $newValue = null;
        $oldValue = null;

        if ($request->hasFile("value")) {
            if ($newType !== "image") {
                return response()->json(
                    [
                        "message" =>
                            "Value must be a string for text preferences.",
                    ],
                    Response::HTTP_UNPROCESSABLE_ENTITY,
                );
            }

            $request->validate([
                "value" => array_merge(
                    ["file"],
                    $this->imageRules($newKey),
                ),
            ]);

            try {
                $newValue = $this->storeImage(
                    $request->file("value"),
                    $newKey,
                );
            } catch (RuntimeException $e) {
                return response()->json(
                    ["message" => "Image could not be processed."],
                    Response::HTTP_UNPROCESSABLE_ENTITY,
                );
            }

            $oldValue =
                $preference->type === "image" ? $preference->value : null;
        } elseif ($request->has("value")) {
            if ($newType !== "text") {
                return response()->json(
                    [
                        "message" =>
                            "Value must be an uploaded image for image preferences.",
                    ],
                    Response::HTTP_UNPROCESSABLE_ENTITY,
                );
            }

            $request->validate([
                "value" => ["string"],
            ]);

            $newValue = $request->input("value");
            $oldValue =
                $preference->type === "image" ? $preference->value : null;
        } elseif ($newType !== $preference->type) {
            return response()->json(
                [
                    "message" =>
                        "Value is required when changing the preference type.",
                ],
                Response::HTTP_UNPROCESSABLE_ENTITY,
            );
        }

        try {
            $preference->update(
                array_filter(
                    [
                        "key" => $validation["key"] ?? null,
                        "type" => $validation["type"] ?? null,
                        "value" => $newValue,
                    ],
                    fn($value) => $value !== null,
                ),
            );
        } catch (\Throwable $e) {
            if ($request->hasFile("value") && $newValue !== null) {
                $this->deleteStoredImage($newValue);
            }

            throw $e;
        }

        if ($oldValue !== null) {
            $this->deleteStoredImage($oldValue);
        }

        return response()->json([
            "message" => "Preference updated successfully.",
            "data" => $preference->fresh(),
        ]);
    }

    public function delete(Request $request)
    {
        $validation = $request->validate([
            "id" => ["required", "integer"],
        ]);

        $preference = Preference::find($validation["id"]);

        if (!$preference) {
            return response()->json(
                ["message" => "Preference does not exist."],
                Response::HTTP_NOT_FOUND,
            );
        }

        $oldValue = $preference->type === "image" ? $preference->value : null;

        $preference->delete();

        if ($oldValue !== null) {
            $this->deleteStoredImage($oldValue);
        }

        return response()->json(
            ["message" => "Preference deleted successfully."],
            Response::HTTP_OK,
        );
    }

    public function show(Request $request, ?string $id = null)
    {
        if ($id != null) {
            if (!is_numeric($id)) {
                return response()->json(
                    ["message" => "Preference does not exist."],
                    Response::HTTP_NOT_FOUND,
                );
            }

            $preference = Preference::find($id);

            if (!$preference) {
                return response()->json(
                    ["message" => "Preference does not exist."],
                    Response::HTTP_NOT_FOUND,
                );
            }

            return response()->json(
                ["data" => $preference],
                Response::HTTP_OK,
            );
        }

        return response()->json(
            ["data" => Preference::all()],
            Response::HTTP_OK,
        );
    }

    /**
     * @return array<int, string>
     */
    private function imageRules(string $key): array
    {
        $key = strtolower($key);

        if (str_contains($key, "favicon")) {
            return ["mimes:ico,png", "max:512"];
        }

        if (str_contains($key, "logo")) {
            return ["mimes:png,jpg,jpeg", "max:2048"];
        }

        if (
            str_contains($key, "home") ||
            str_contains($key, "photo") ||
            str_contains($key, "foto")
        ) {
            return ["mimes:jpg,jpeg,png", "max:5120"];
        }

        return ["mimes:png,jpg,jpeg,webp", "max:5120"];
    }

    private function shouldConvertToWebp(string $key): bool
    {
        return !str_contains(strtolower($key), "favicon");
    }

    private function storeImage(UploadedFile $file, string $key): string
    {
        $disk = Storage::disk("public");
        $name =
            (Str::slug($key) ?: "image") .
            "-" .
            Str::lower(Str::random(12));

        if (!$this->shouldConvertToWebp($key)) {
            $extension = $file->extension() ?: "bin";
            $path = "preferences/{$name}.{$extension}";

            $disk->put($path, $file->getContent());

            return $path;
        }

        $temporary = tempnam(sys_get_temp_dir(), "preference_");
        $output = $temporary . ".webp";

        try {
            $result = Process::timeout(60)->run([
                "magick",
                $file->getRealPath(),
                "-auto-orient",
                "-strip",
                "-background",
                "none",
                "-quality",
                "80",
                $output,
            ]);

            if (!$result->successful()) {
                throw new RuntimeException(
                    trim($result->errorOutput()) ?:
                    "Image conversion failed.",
                );
            }

            $path = "preferences/{$name}.webp";
            $disk->put($path, file_get_contents($output));

            return $path;
        } finally {
            @unlink($temporary);
            @unlink($output);
        }
    }

    private function deleteStoredImage(?string $value): void
    {
        if (!$value) {
            return;
        }

        if (Str::startsWith($value, ["http://", "https://", "/"])) {
            return;
        }

        Storage::disk("public")->delete($value);
    }

    private function hasInvalidUpload(Request $request): bool
    {
        $file = $request->file("value");

        return $file instanceof UploadedFile && !$file->isValid();
    }
}
