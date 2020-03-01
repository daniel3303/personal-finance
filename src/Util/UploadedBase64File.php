<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 23/02/2020
 * Time: 01:48
 */

namespace App\Util;


use RuntimeException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploadedBase64File extends UploadedFile {

    public function __construct(string $base64String) {
        //Check is valid base64
        if(strpos($base64String, 'base64,')){
            $base64String = explode(',', $base64String)[1];
        }
        if(base64_decode($base64String, true) === false){
            throw new RuntimeException('Invalid Base64 file', 1);
        }

        $filePath = tempnam(sys_get_temp_dir(), 'Base64UploadedFile');
        $data = base64_decode($base64String);
        file_put_contents($filePath, $data);
        $error = 0;
        $mimeType = mime_content_type($filePath);
        $test = true;

        parent::__construct($filePath, uniqid('Base64UploadedFile', true), $mimeType, $error, $test);
    }

}