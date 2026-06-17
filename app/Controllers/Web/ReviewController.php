<?php
require_once(_DIR_ROOT . '/app/Controllers/Web/WebController.php');
require_once(_DIR_ROOT . '/core/auth.php');
require_once(_DIR_ROOT . '/app/Models/Web/Review.php');
require_once(_DIR_ROOT . '/core/Flash.php');
require_once(_DIR_ROOT . '/app/Models/Web/order.php');
class ReviewController extends WebController
{
     public function history()
    {
        $orderModel = new order();
        $orders = $orderModel->get_order_history_by_user_id(auth::handleUser());
        return $this->views('order/history', ['orders' => $orders]);
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $reviewModel = new Review();
            $maSanPham = $_POST['MaSanPham'] ?? null;
            $maKhachHang = Auth::handleUser();

            $data = [
                'MaSanPham' => $maSanPham,
                'MaTaiKhoan' => $maKhachHang,
                'SoSao' => $_POST['SoSao'] ?? 0,
                'NoiDung' => $_POST['NoiDung'] ?? '',
            ];
            if (!$reviewModel->canview($maKhachHang, $maSanPham)) {
                Flash::set('error_review', 'Đánh giá sản phẩm thất bại');
                return redirect("ReviewController/history");
            }

            $result = $reviewModel->insert_review($data);
            if ($result) {
                Flash::set('success_review', 'Đánh giá sản phẩm thành công');
                return redirect("ReviewController/history");
            } else {
                Flash::set('error_review', 'Đánh giá sản phẩm thất bại');
                return redirect("ReviewController/history");
            }
        }
    }
}
