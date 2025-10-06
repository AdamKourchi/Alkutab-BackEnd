<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\File;
use Illuminate\Http\Request;

class SecureFileController extends Controller
{
    public function getFile(Request $request)
    {
        $path = $request->path;

        $file = storage_path('app/public/' . $path);

        return response()->file($file);
    }
}
