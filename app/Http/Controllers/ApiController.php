<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\UserFile;


class ApiController extends Controller
{
    /**
     * Handles uploading of a new file
     * @param Request $request
     * @return JsonResponse
     */
    public function upload(Request $request): JsonResponse
    {
        $validated_data = $request->validate([
            'filename' => ['max:127'],
            'file' => ['required', 'max:8192'],
        ]);

        $file = $validated_data['file'];
        $file_name = $validated_data['filename'] ?? null;

        $uploadedFile = UserFile::uploadFile($file, $file_name);
        return response()->json(['message' => 'ok', 'slug' => $uploadedFile->slug]);
    }

    /**
     * Handles updates -- deletes old files from disk
     * and places new ones
     * @param Request $request
     * @param string $slug
     * @return JsonResponse
     */
    public function update(Request $request, string $slug): JsonResponse
    {
        $validated_data = $request->validate([
            'filename' => ['max:127'],
            'file' => ['max:8192'],
        ]);

        /**
         * @var UserFile|null $file
         */
        $file = UserFile::where('slug', $slug)->first();

        if (!$file) {
            return response()->json(['message' => 'Record not found'], 404);
        }

        $new_file = $validated_data['file'] ?? null;
        $new_title = $validated_data['filename'] ?? null;

        $file->updateFile($new_file, $new_title);
        return response()->json(['message' => 'ok']);
    }

    public function delete($slug): JsonResponse
    {
        $file = UserFile::where('slug', $slug)->first();

        if (!$file) {
            return response()->json(['message' => 'Record not found'], 404);
        }

        $file->deleteFile();
        return response()->json(['message' => 'ok']);
    }
}
