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
            'food-and-drinks' => 'عکاسی غذا و نوشیدنی',
            'Breakfast' => 'صبحانه',
            'Cafe' =>  'کافه',
            'Cake' =>  'کیک',
            'Drink' =>  'نوشیدنی',
            'Restaurant-Food' => 'غذا رستورانی' ,
            'shokolat' =>  'شکلات',


            'products' => 'محصولات',
            'product-photography' => 'عکاسی محصولات' ,
            'perfume' => 'عطر' ,
            'makeup-products' => 'لوازم آرایشی',
            'macro' => 'ماکرو',
            'leather' => 'چرم',
            'bag' => 'کیف',
            'shoe' => 'کفش',


            'outfit' => 'عکاسی پوشاک',
            'womens-fashion' => 'زنانه',
            'kids-clothing' => 'کودک'

        ];

        $categoryIcons = [
            'portrait' => 'solar:camera-linear',
            'food-and-drinks' => 'material-symbols:fastfood-outline-rounded',
            'products' => 'fluent-mdl2:product',
            'product-photography' => 'fluent-mdl2:product',
            'outfit' => 'hugeicons:dress-02',
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
