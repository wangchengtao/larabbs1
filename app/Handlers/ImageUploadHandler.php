<?php

namespace App\Handlers;

use \Illuminate\Http\UploadedFile;

class ImageUploadHandler
{
    protected $allowed_ext = ['png', 'jpg', 'gif', 'jpeg'];

    public function save(UploadedFile $file, $folder, $file_prefix)
    {
        // 构建存储的文件夹规则
        $folderName = "uploads/images/$folder/" . date('Ymd');

        // 文件具体存储的物理路径
        $upload_path = public_path($folderName);

        // 获取文件的后缀名
        $extension = strtolower($file->getClientOriginalExtension()) ?: 'png';

        // 拼接文件名
        $filename = $file_prefix . '_' . time() . '_' . str_random(10) . '.' . $extension;

        // 如果上传的图片不允许
        if (! in_array($extension, $this->allowed_ext)) {
            return false;
        }

        // 将图片移动到目标存储路径中
        $file->move($upload_path, $filename);

        return [
            'path' =>config('app.url') . "/$folderName/$filename",
        ];
    }
}
