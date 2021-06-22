<?php

define('PAGINATION_COUNT', 10);

use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

function uploadPhoto($image, $folderName)
{
    $position = strpos($image, ';');
    $sub = substr($image, 0, $position);
    $ext = explode('/', $sub)[1];

    $name = rand(11111,999999).date("HisYmd").time().rand(1111,99999).".".$ext;
    $img = Image::make($image)->resize(500, 500, function ($constraint) {
        $constraint->aspectRatio();
        $constraint->upsize();
    });
    $upload_path = 'uploads/'.$folderName;
    $folderFullPath = public_path($upload_path);
    if (!File::isDirectory($folderFullPath)){
        File::makeDirectory($folderFullPath);
    }
    $image_url = $upload_path.'/'.$name;
    $img->save($image_url);

    return $image_url;
}

?>
