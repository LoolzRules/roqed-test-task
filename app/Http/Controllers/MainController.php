<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\UserFile;
use Inertia\Inertia;


class MainController extends Controller
{
    public function list(Request $request): \Inertia\Response
    {
        $search_query = $request->input('search');
        $per_page = 50;

        $query = isset($search_query)
            ? UserFile::where('title', 'like', '%' . trim($search_query) . '%')->paginate($per_page)
            : UserFile::paginate($per_page);

        return Inertia::render('Main', [
            'search' => $search_query ?? '',
            'pagination' => $query
                ->withPath('/')
                ->appends(request()->except('page'))
                ->toArray(),
        ]);
    }

    public function upload(Request $request): \Inertia\Response
    {
        return Inertia::render('Upload');
    }
    public function edit(Request $request, string $slug): \Inertia\Response
    {
        $file = UserFile::where('slug', $slug)->first();

        if (!$file) {
            return abort(404);
        }

        return Inertia::render('Edit', [
            'file' => $file->toArray(),
        ]);
    }
}
