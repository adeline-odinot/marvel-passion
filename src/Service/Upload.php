<?php

namespace App\Service;

class Upload
{
    public function upload($directory, $file, $oldFile = '')
    {
        if($file->guessExtension() == 'jpg' || $file->guessExtension() == 'jpeg' || $file->guessExtension() == 'png')
        {
            if ($oldFile != '')
            {
                unlink($directory .'/'. $oldFile); 
            }
            $fileName = md5(uniqid()) . '.' . $file->guessExtension();

            $file->move(
                $directory,
                $fileName
            );
    
            return $fileName;
        }
        else
        {
            return false;
        }
    }
}