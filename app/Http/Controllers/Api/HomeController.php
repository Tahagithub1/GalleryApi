<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use OpenApi\Annotations as OA;

class HomeController extends Controller
{
    /**
     * @OA\PathItem(path="/api")
     *
     * @OA\Info(
     *      version="0.0.0",
     *      title="API Documentation"
     *  )
     */

    public function index(){
        return "aa";
    }

}
