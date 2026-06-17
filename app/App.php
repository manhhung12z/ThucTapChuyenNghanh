<?Php
class App
{
    private $__controller, $__action, $__params, $__folder;

    public function __construct()
    {
        $this->__controller = 'HomepageController';
        $this->__action = 'index';
        $this->__params = [];
        $this->__folder = 'Web';
        $this->handleUrl();
    }
    public function getUrl()
    {
        if (!empty($_SERVER['PATH_INFO'])) {
            $url = $_SERVER['PATH_INFO'];
        } else {
            $url = '/';
        }
        return $url;
    }
    public function handleUrl()
    {
        $url = $this->getUrl() ?? '';
        $urlArr = array_filter(explode('/', $url));//cắt chuỗi mảng về dạng có mảng / và array_filter để loại bỏ phần tử rỗng
        $urlArr = array_values($urlArr);//đặt lại chỉ số cho mảng sau khi đã loại bỏ phần tử rỗng
        //cấu hình cho folder thể hiện controller
        if (!empty($urlArr[0]) && strtolower($urlArr[0]) == 'admin') {
            $this->__folder = ucfirst($urlArr[0]); // Lấy tên folder từ URL và chuyển chữ cái đầu thành hoa
            array_shift($urlArr); // Loại bỏ phần tử đầu tiên (admin) khỏi mảng URL để tiếp tục xử lý controller và action
        }

        if (!empty($urlArr[0])) {
            $this->__controller = ucfirst($urlArr[0]);
        }
        $controllerPath = 'app/Controllers/' . $this->__folder . '/' . $this->__controller . '.php';

        if (file_exists($controllerPath)) {
            require_once $controllerPath;
            if (class_exists($this->__controller)) {
                $this->__controller = new $this->__controller();
                array_shift($urlArr);
            } else {
                header("location: " . _WEB_ROOT . "errors/Notfound");
                return;
            }
        } else {
            header("location: " . _WEB_ROOT . "errors/Notfound");
            return;
        }

        if (!empty($urlArr[0])) {
            $this->__action = $urlArr[0];
            unset($urlArr[0]); // Loại bỏ phần tử đầu tiên (action) khỏi mảng URL để tiếp tục xử lý params
        }
        $this->__params = array_values($urlArr); // Đặt lại chỉ số cho mảng params sau khi đã loại bỏ phần tử action
        //kiểm tra method tồn tại
        if (method_exists($this->__controller, $this->__action)) {//kiêm tra method tồn tại trong controller
            $output = call_user_func_array([$this->__controller, $this->__action], $this->__params); //gọi method với tham số truyền vào
            if (is_string($output)) {
                echo $output;
            }
        } else {
            header("location: " . _WEB_ROOT . "errors/Notfound");
            return;
        }
    }
}
