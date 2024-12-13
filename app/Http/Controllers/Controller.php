<?php

namespace App\Http\Controllers;

use App\Models\ApiResponse;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    protected $apiResponse;

    public function __construct()
    {
        $this->apiResponse = new ApiResponse();
    }

    public function response()
    {
        return response()->json($this->apiResponse->toArray(), $this->apiResponse->getCode());
    }
}
