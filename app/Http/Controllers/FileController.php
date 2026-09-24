<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class FileController extends Controller
{
    /**
     * Serve a file from private storage.
     */
    public function serve(Request $request)
    {
        $path = $request->query('path');

        if (!$path || !Storage::disk('local')->exists($path)) {
            abort(404);
        }

        // Use response()->file() for local path or Storage::response()
        // Storage::disk('local')->path($path) gives the absolute path
        return response()->file(storage_path('app/' . $path));
    }
}
