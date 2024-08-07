<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Validator;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * Кастомная валидация для реализации именованного пакета
     * ошибок для каждой формы на странице
     * @param $request
     * @return \Illuminate\Validation\Validator
     */
    public function validateForm($request)
    {
        return Validator::make($request->all(),
            $this->getRules(),
            $this->getMessages()
        );
    }
}
