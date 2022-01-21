<?php


namespace App\Controller;

class EditionFile
{
    public static function edit(
        $file,
        $ext,
        $name,
        $path,
        $old,
        $end,
        $size
    )
    {
        $flag = 0;

            if(isset($old) && file_exists($path.$old))
            {
                unlink($path.$old);
            }
            $nameNew = $name.$end;
            $parts = explode('.',$file['name']);
            $extension = strtolower($parts[count($parts)-1]);
            if(in_array($extension, $ext) && $file['size'] <= $size)
            {
                move_uploaded_file($file['tmp_name'],$path.$nameNew);
            }
            $flag = 1;


        if($flag == 1) {
            $flag = $nameNew;
        } elseif(isset($name)) {
            if($old == $nameNew)
            {
                $flag = $old;
            } else {
                rename($path.$old, $path.$nameNew);
                $flag = $nameNew;
            }
        }
        return $flag;
    }

    public static function editDbl(
        $file,
        $ext,
        $name,
        $path,
        $old,
        $end,
        $size,
        $nameImg
    )
    {
        $flag = 0;

        if(isset($old) && file_exists($path.$old))
        {
            unlink($path.$old);
        }
        $nameNew = $name.$end;
        $parts = explode('.',$file['name'][$nameImg]);
        $extension = strtolower($parts[count($parts)-1]);
        if(in_array($extension, $ext) && $file['size'][$nameImg] <= $size)
        {
            move_uploaded_file($file['tmp_name'][$nameImg],$path.$nameNew);
        }
        $flag = 1;


        if($flag == 1) {
            $flag = $nameNew;
        } elseif(isset($name)) {
            if($old == $nameNew)
            {
                $flag = $old;
            } else {
                rename($path.$old, $path.$nameNew);
                $flag = $nameNew;
            }
        }
        return $flag;
    }

    public static function rename_file($old, $new, $path, $ext)
    {
        if(file_exists($path.$old))
        {
            rename($path.$old, $path.$new.$ext);
        }
    }
}