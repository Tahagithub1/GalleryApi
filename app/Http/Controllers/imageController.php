<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class imageController extends Controller
{
    public function index()
    {
        $basePath = storage_path('app/public/images');
        $baseUrl = asset('storage/images');

        $persianLabels = [
            'portrait' => 'عکاسی پرتره',
            'Breakfast' => 'صبحانه',
            'food-and-drinks' => 'غذا و نوشیدنی',
            'wedding' => 'عروسی',
            'product' => 'محصولات',
            'men' => 'مردانه',
            'women' => 'زنانه',
            'child' => 'کودک',
            'food' => 'غذا',
            'landscape' => 'منظره'
        ];

        $categoryIcons = [
            'portrait' => 'fa-camera-retro',
            'breakfast' => 'fa-coffee',
            'nature' => 'fa-tree',
            'wedding' => 'fa-heart',
            'product' => 'fa-shopping-bag'
        ];

        $scanFolder = function ($path, $relativePath = '') use (&$scanFolder, $baseUrl, $persianLabels, $categoryIcons) {
            $items = [];
            $fullPath = $path . ($relativePath ? '/' . $relativePath : '');
            $pathParts = explode('/', $relativePath);
            $currentFolder = end($pathParts);

            foreach (File::directories($fullPath) as $dir) {
                $name = basename($dir);
                $newRelativePath = trim($relativePath . '/' . $name, '/');
                $isMainCategory = count(explode('/', $newRelativePath)) === 1;

                $folderData = [
                    'type' => 'folder',
                    'name' => $name,
                    'name_fa' => $persianLabels[$name] ?? $name,
                    'path' => $newRelativePath,
                    'children' => $scanFolder($path, $newRelativePath),
                    'icon' => $isMainCategory ? ($categoryIcons[$name] ?? 'fa-folder') : 'fa-folder-open'
                ];

                $items[] = $folderData;
            }

            foreach (File::files($fullPath) as $file) {
                $fileName = $file->getFilename();
                $items[] = [
                    'type' => 'file',
                    'name' => $fileName,
                    'name_fa' => pathinfo($fileName, PATHINFO_FILENAME),
                    'url' => $baseUrl . '/' . trim($relativePath, '/') . '/' . $fileName,
                    'icon' => 'fa-file'
                ];
            }

            return $items;
        };

        $tree = $scanFolder($basePath);

        return response()->json([
            'success' => true,
            'data' => $tree,
            'message' => 'ساختار پوشه‌ها با موفقیت دریافت شد'
        ]);
    }
}
