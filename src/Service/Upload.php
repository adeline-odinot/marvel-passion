<?php

namespace App\Service;

class Upload
{
    public function upload($directory, $file, $inputError, $oldFile = '')
    {
        if(isset($file))
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
                $inputError->addError(new FormError("Le format d'image n'est pas accepté (jpg, jpeg, png)."));
                return false;
            }
        }
    }
}