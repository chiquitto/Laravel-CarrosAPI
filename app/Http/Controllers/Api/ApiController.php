<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class ApiController extends Controller
{

    const ACTION_STORE = 0;
    const ACTION_UPDATE = 1;

    protected function responseOk($data) {
        return $data;
    }

}
