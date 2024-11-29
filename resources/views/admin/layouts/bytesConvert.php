<?php
function ByteSize($bytes)
{
    $size = $bytes / 1024;
    if ($size < 1024) {
        $size = number_format($size, 2);
        $size .= ' KB';
    } else {
        if ($size / 1024 < 1024) {
            $size = number_format($size / 1024, 2);
            $size .= ' MB';
        } else if ($size / 1024 / 1024 < 1024) {
            $size = number_format($size / 1024 / 1024, 2);
            $size .= ' GB';
        } else if ($size / 1024 / 1024 / 1024 < 1024) {
            $size = number_format($size / 1024 / 1024 / 1024, 2);
            $size .= ' TB';
        } else if ($size / 1024 / 1024 / 1024 / 1024 < 1024) {
            $size = number_format($size / 1024 / 1024 / 1024 / 1024, 2);
            $size .= ' PB';
        }
    }
    $size = preg_replace('/.00/', '', $size);
    return $size;
}


?>