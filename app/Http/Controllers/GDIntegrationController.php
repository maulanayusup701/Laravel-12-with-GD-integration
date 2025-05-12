<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class GDIntegrationController extends Controller
{
    public function index()
    {
        $path = public_path() . '/' . 'filename.png';
        $filename = 'filename.png';
        Storage::disk('google')->put($filename, File::get($path));

        return response()->json('success');
    }
}
