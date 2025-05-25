<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\File;

class imageController extends Controller
{
    /**
     * @OA\Get(
     *     path="/images/{path}",
     *     operationId="getImages",
     *     tags={"Images"},
     *     summary="Get images in folder or subfolder",
     *     description="Returns list of images from given path or first subfolder",
     *     @OA\Parameter(
     *         name="path",
     *         in="path",
     *         description="Folder path",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful response"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Folder not found"
     *     )
     * )
     */
    public function index($path = '')
    {
        $baseDir = storage_path('app/public/images');
        $fullPath = $baseDir . '/' . trim($path, '/');

        if (is_dir($fullPath)) {
            $files = File::files($fullPath);

            if (count($files) === 0) {
                $subDirs = File::directories($fullPath);

                if (count($subDirs) > 0) {
                    $fullPath = $subDirs[0];
                    $relativePath = str_replace($baseDir . '/', '', $fullPath);
                } else {
                    return response()->json(['error' => 'No files or subdirectories found in this folder.'], 404);
                }
            } else {
                $relativePath = trim($path, '/');
            }
        } else {
            return response()->json(['error' => 'Folder not found.'], 404);
        }

        $files = File::files($fullPath);

        $images = collect($files)->map(function ($file) use ($relativePath) {
            return [
                'name' => $file->getFilename(),
                'url' => asset('storage/images/' . $relativePath . '/' . $file->getFilename()),
            ];
        });

        return response()->json($images);
    }
}
