<?php

namespace App\Http\Controllers\Api;

use Illuminate\Routing\Controller as BaseController;
use Dingo\Api\Routing\Helpers;

abstract class ApiController extends BaseController
{
    use Helpers;
}
