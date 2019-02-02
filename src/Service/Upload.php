<?php

namespace App\Service;

class Upload
{
    public function upload($directory, $file)
    {
        $fileName = md5(uniqid()) . '.' . $file->guessExtension();

        $file->move(
            $directory,
            $fileName
        );

        return $fileName;
    }
}