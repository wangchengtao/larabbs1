<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\CaptchasRequest;
use Gregwar\Captcha\CaptchaBuilder;
use Illuminate\Http\Request;

class CaptchasController extends Controller
{
    public function store(CaptchasRequest $request, CaptchaBuilder $builder)
    {
        $key = 'captcha-'. str_random(15);
        $phone = $request->phone;

        $captcha = $builder->build();
        $expiredAt = now()->addMinutes(10);

        \Cache::put($key, ['phone' => $phone, 'captcha' => $captcha->getPhrase()], $expiredAt);

        $result = [
            'captcha_key' => $key,
            'expired_at' => $expiredAt->toDateTimeString(),
            'captcha_image_content' => $captcha->inline()
        ];

        return $this->response->array($result)->setStatusCode(201);
    }
}
