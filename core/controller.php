<?php
class controller
{
    public function model($model, $module)
    {
        if (file_exists(_DIR_ROOT . '/app/Models/' . $module . '/' . $model . '.php')) {
            require_once _DIR_ROOT . '/app/Models/' . $module . '/' . $model . '.php';
            if (class_exists($model)) {
                $model = new $model;
                return $model;
            }
        }
    }
    public function views($view, $data = [])
    {
        if (file_exists(_DIR_ROOT . '/app/views/' . $view . '.php')) {
            extract($data);
            require_once _DIR_ROOT . '/app/views/' . $view . '.php';
        }
    }

    public function render($view, $data = [])
    {
        extract($data);
        template::init(); // Bật buffer
        require_once _DIR_ROOT . '/app/views/' . $view . '.php'; // Chạy file view và "nhốt" HTML vào buffer

        $raw_html = ob_get_clean(); // Lấy HTML gốc và xóa buffer
        $content = Template::callback($raw_html); // Tự động gọi callback để chèn các block vào đúng vị trí
        return $content;
    }
}
