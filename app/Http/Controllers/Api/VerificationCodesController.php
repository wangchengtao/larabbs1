<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\VerificationCodeRequest;
use Illuminate\Http\Request;
use Overtrue\EasySms\EasySms;
use Overtrue\EasySms\Exceptions\NoGatewayAvailableException;

class VerificationCodesController extends Controller
{
    /**
     * @param \App\Http\Requests\Api\VerificationCodeRequest $request
     * @param \Overtrue\EasySms\EasySms                      $easySms
     * @throws \Overtrue\EasySms\Exceptions\InvalidArgumentException
     */
    public function store(VerificationCodeRequest $request, EasySms $easySms)
    {
        $captchasData = \Cache::get($request->captcha_key);

        if (!$captchasData) {
            $this->response->error('图片验证码失效', 422);
        }

        if (!hash_equals($captchasData['captcha'], $request->captcha_code)) {
            \Cache::forget($request->captcha_key);
            $this->response->errorUnauthorized('验证码错误');
        }

        $phone = $captchasData['phone'];
        if (!app()->environment('production')) {
            $code = '1234';
        } else {
            // 随机生成四位随机数, 左侧补0
            $code = str_pad(random_int(1, 9999), 4, 0, STR_PAD_LEFT);

            try {
                $easySms->send($phone, [
                    'template' => config('easysms.gateways.aliyun.templates.register'),
                    'data' => [
                        'code' => $code
                    ],
                ]);
            } catch (NoGatewayAvailableException $exception) {
                $message = $exception->getException('aliyun')->getMessage();
                $this->response->errorInternal($message ?: '短信发送异常');
            }
        }

        $key = 'verificationCode_' . str_random(15);
        $expireAt = now()->addMinutes(10);
        // 缓存验证码 10分钟过期
        \Cache::put($key, ['phone' => $phone, 'code' => $code], $expireAt);
        \Cache::forget($request->captcha_key);

        return $this->response->array([
            'key' => $key,
            'expired_at' => $expireAt->toDateTimeString(),
        ])->setStatusCode(201);
    }
}
