<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\UserFile;


class UserFileController extends Controller
{
    /**
     * Retrieves the list of files based
     * on query params 'search' and 'page'
     * @param Request $request
     * @return JsonResponse
     */
    public function list(Request $request): JsonResponse
    {
        $search_query = $request->input('search');
        $per_page = 50;

        $query = isset($search_query)
            ? UserFile::where('title', '%', $search_query)->paginate($per_page)
            : UserFile::paginate($per_page);

        return response()
            ->json([
                'status' => true,
                'data' => $query
                    ->appends(request()->except('page'))
                    ->toArray(),
            ]);
    }

    /**
     * Handles uploading of a new file
     * @param Request $request
     * @return JsonResponse
     */
    public function upload(Request $request): JsonResponse
    {
        $file = $request->file('file');
        $file_name = $request->get('filename');

        if ($file) {
            $uploadedFile = UserFile::uploadFile($file, $file_name);
            return response()->json(['status' => true, 'file' => $uploadedFile]);
        }

        return response()->json(['status' => false, 'error' => 'No file provided'], 400);
    }

    /**
     * Handles updates
     * @param Request $request
     * @param string $slug
     * @return JsonResponse
     */
    public function update(Request $request, string $slug): JsonResponse
    {
        $file = UserFile::where('slug', $slug)->first();

        if (!$file) {
            return response()->json(['status' => false, 'error' => 'Record not found'], 404);
        }

        $new_file = $request->file('new_file');
        $new_title = $request->get('new_title');

        if (!$new_file) {
            return response()->json(['status' => false, 'error' => 'No new file provided'], 400);
        }

        $file->updateFile($new_file, $new_title);
        return response()->json(['status' => true, 'file' => $file]);
    }

    public function delete($slug): JsonResponse
    {
        $file = UserFile::where('slug', $slug)->first();

        if (!$file) {
            return response()->json(['status' => false, 'error' => 'Record not found'], 404);
        }

        $file->deleteFile();
        return response()->json(['status' => true]);
    }
}
