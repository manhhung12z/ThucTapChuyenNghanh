<?php
function asset($path)
{
    // Chỉ tự động thêm ?v= để chống cache đối với file CSS và JS
    if (preg_match('/\.(css|js)$/i', $path)) {
        $filePath = _DIR_ROOT . '/public/' . $path;
        $version = file_exists($filePath) ? filemtime($filePath) : time();
        return _WEB_ROOT . '/public/' . $path . '?v=' . $version;
    }
    // Còn lại (hình ảnh, fonts...) thì giữ nguyên đường dẫn
    return _WEB_ROOT . '/public/' . $path;
}
function url($path)
{
    return _WEB_ROOT . '/' . $path;
}

function urllocal($path)
{
    return _DIR_ROOT . '/' . $path;
}
function redirect($url)
{
    header("location: " . _WEB_ROOT . "/" . $url);
    exit;
}
?>