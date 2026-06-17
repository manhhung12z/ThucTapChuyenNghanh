<?php
require_once(_DIR_ROOT . '/app/Controllers/Web/WebController.php');
require_once(_DIR_ROOT . '/app/Models/Web/product.php');
require_once(_DIR_ROOT . '/app/Models/Web/cart.php');
require_once(_DIR_ROOT . '/core/auth.php');
require_once(_DIR_ROOT . '/core/Flash.php');
require_once(_DIR_ROOT . '/app/Models/Web/order.php');
require_once(_DIR_ROOT . '/app/Models/Web/orderdetail.php');
require_once(_DIR_ROOT . '/app/Models/Web/transport.php');
require_once(_DIR_ROOT . '/app/Models/Web/payment.php');
class OrderController extends WebController
{
        public function prepare()
        {
                $maSanPhamList = $_POST['MaSanPham'] ?? [];
                if (!is_array($maSanPhamList)) {
                        $maSanPhamList = [$maSanPhamList];
                }

                $soLuongPost = $_POST['SoLuong'] ?? [];
                $numberDetail = $_POST['number_detail'] ?? 1;
                $_SESSION['checkout_data'] = [
                        'MaSanPham' => $maSanPhamList,
                        'SoLuong' => $soLuongPost,
                        'number_detail' => $numberDetail,
                ];
                header("Location: " . url('OrderController/index')); //yêu cầu trình duyệt chuyển hướng đến phương thức index() để hiển thị trang Checkout
                //còn đối với require thì không đổi , vẫn giữ nguyên url file ban đầu đc gọi  
                exit;
        }
        public function index()
        {
                $checkoutData = $_SESSION['checkout_data'] ?? null;
                if (!$checkoutData) {
                        header("Location: " . url(''));
                        exit;
                }
                $productDetails = [];
                $maSanPhamList = $checkoutData['MaSanPham'] ?? [];
                if (!is_array($maSanPhamList)) {
                        $maSanPhamList = [$maSanPhamList];
                }
                $soLuongPost = $checkoutData['SoLuong'] ?? [];
                $numberDetail = $checkoutData['number_detail'] ?? 1;
                if (!empty($maSanPhamList)) {
                        $productModel = new product();
                        foreach ($maSanPhamList as $maSanPham) {
                                $productDetail = $productModel->find_product_with_discount($maSanPham);
                                if ($productDetail) {
                                        if (isset($soLuongPost[$maSanPham])) {
                                                $qty = intval($soLuongPost[$maSanPham]);
                                        } else {
                                                $qty = intval($numberDetail);
                                        }
                                        $productDetail['SoLuongMua'] = $qty > 0 ? $qty : 1;
                                        $productDetails[] = $productDetail;
                                }
                        }
                }
                return $this->views('order/index', ['productDetails' => $productDetails]);
        }
        public function handlecheckout()
        {
                $checkout = new checkout();
                if (isset($_POST['order'])) {
                        $id = date("YmdHis") . rand(100, 999);
                        $name = $_POST['name'];
                        $phone = $_POST['phone'];
                        $address = $_POST['address'] . ', ' . $_POST['ward'] . ', ' . $_POST['district'] . ', ' . $_POST['provinces'];
                        $payment = $_POST['payment'];
                        $total = $_POST['total'];
                        // Lấy mảng sản phẩm từ giao diện gửi lên
                        $maSanPhamArr = $_POST['MaSanPham'] ?? [];
                        $soLuongArr = $_POST['SoLuongMua'] ?? [];
                        $donGiaArr = $_POST['DonGia'] ?? [];
                        // === KIỂM TRA TỒN KHO TRƯỚC KHI CHO ĐẶT HÀNG ===
                        if (is_array($maSanPhamArr)) {
                                $productModel = new product();
                                for ($i = 0; $i < count($maSanPhamArr); $i++) {
                                        $prod = $productModel->findid($maSanPhamArr[$i]);
                                        if (!$prod) {
                                                header("Location: " . url(''));
                                                exit;
                                        }
                                        $slMua = $soLuongArr[$i] ?? 1;
                                        if ($prod['SoLuong'] < $slMua) {
                                                $tenSP = $prod ? $prod['TenSanPham'] : 'Sản phẩm';
                                                $slTon = $prod ? (int)$prod['SoLuong'] : 0;
                                                Flash::set('error_sl', "Rất tiếc, $tenSP chỉ còn $slTon sản phẩm trong kho.");
                                                // Quay trở lại trang Checkout cũ
                                                header("Location: " . $_SERVER['HTTP_REFERER']);
                                                exit;
                                        }
                                }
                        }
                        // Lấy ID User hiện tại đang đăng nhập
                        $userId = auth::handleUser();
                        $orderModel = new order();
                        $orderData = [
                                'MaDonHang' => $id,
                                'MaTaiKhoan' => $userId,
                                'NgayDat' => date('Y-m-d H:i:s'),
                                'TongTien' => $total,
                                'TrangThai' => 'Đang chờ duyệt',
                        ];
                        $orderModel->insertorder($orderData);
                        // lưu vào bảng chi tiết đơn hàng
                        $orderDetailModel = new orderdetail();
                        //Lưu vào bảng thanh toán
                        $transaction = [
                                'MaDonHang' => $id,
                                'PhuongThuc' => ($payment == 'cod') ? 'COD' : 'MoMo',
                                'TrangThai'   => 'Chưa thanh toán',
                        ];
                        $paymentModel = new payment();
                        $paymentModel->insertpayment($transaction);

                        // Chạy vòng lặp qua mảng sản phẩm để lưu từng món
                        if (is_array($maSanPhamArr)) {
                                for ($i = 0; $i < count($maSanPhamArr); $i++) {
                                        $detailData = [
                                                'MaDonHang' => $id,
                                                'MaSanPham' => $maSanPhamArr[$i],
                                                'SoLuong'   => $soLuongArr[$i],
                                                'DonGia'    => $donGiaArr[$i]
                                        ];
                                        $orderDetailModel->insertdetail($detailData);
                                }
                        }
                        $transportModel = new transport();
                        $tranport = [
                                'MaGiaoHang' => 'GH' . $id,
                                'MaDonHang' => $id,
                                'DiaChiGiao' => $address,
                                'TrangThai' => 'Chờ lấy hàng',
                        ];
                        $transportModel->inserttranport($tranport);
                        // TODO: Ở đây bạn có thể gọi Model để Insert (Lưu) đơn hàng vào Database trước khi thanh toán.
                        //Trừ Số lượng trong kho
                        if (is_array($maSanPhamArr)) {
                                $productModel = new product();
                                for ($i = 0; $i < count($maSanPhamArr); $i++) {
                                        $productModel->deleteStock($maSanPhamArr[$i], $soLuongArr[$i]);
                                }
                        }
                        if ($payment == "momo") {
                                $_POST['id'] = $id;
                                // Chuẩn bị dữ liệu bắt buộc cho hàm createPayment() của MoMo
                                $_POST['total'] = $total; // MoMo yêu cầu tối thiểu 10.000đ, không thể truyền 0đ
                                $_POST['orderInfo'] = "Thanh toan don hang " . $id;
                                $_POST['redirectUrl'] = url('OrderController/momo_return'); // sau khi thanh toán xong chuyển người dùng về website.
                                $_POST['ipnUrl'] = url('OrderController/momo_return'); //Momo gửi thông báo thanh toán đến server
                                $_POST['extraData'] = "";
                                return $checkout->createPayment();
                        } else if ($payment == "cod") {
                                $cartModel = new cart();
                                $maGioHang = $cartModel->getOrCreateCart($userId);
                                $cartModel->clearCart($maGioHang);

                                Flash::set('success', '🎉 Đặt hàng thành công! Chúng tôi sẽ sớm giao hàng đến bạn.');
                                header("Location: " . url('OrderController/history'));
                                exit;
                        }
                }
                header("Location: " . url(''));
                exit;
        }
        public function momo_return()
        {
                $orderId = $_GET['orderId'] ?? null;
                $resultCode = $_GET['resultCode'] ?? null;
                if ($orderId != null && $resultCode != null) {
                        $paymentModel = new payment();
                        $orderModel = new order();
                        $userId = auth::handleUser();
                        if ($resultCode == 0) {
                                $paymentModel->updatepayment(['TrangThai' => 'Thành công'], $orderId);
                                $orders = $orderModel->get_order_history_by_user_id($userId);
                                $cartModel = new cart();
                                $maGioHang = $cartModel->getOrCreateCart($userId);
                                $cartModel->clearCart($maGioHang);
                                return $this->views("order/history", ['success' => 'Giao dịch MoMo thành công! Đơn hàng ' . $orderId . ' đã được thanh toán.', 'orders' => $orders]);
                        } else {
                                $paymentModel->updatepayment(['TrangThai' => 'Chưa thanh toán'], $orderId);
                                $orders = $orderModel->get_order_history_by_user_id($userId);
                                return $this->views("order/history", [
                                        'error' => 'Giao dịch thất bại hoặc bạn đã hủy thanh toán.',
                                        'orders' => $orders
                                ]);
                        }
                }
        }
        public function history()
        {
                $orderModel = new order();
                $orders = $orderModel->get_order_history_by_user_id(auth::handleUser());
                $success = Flash::get('success');
                return $this->views('order/history', [
                        'orders' => $orders,
                        'success' => $success
                ]);
        }
        public function cancel()
        {
                $orderId = $_GET['id'] ?? null;
                if ($orderId) {
                        $orderModel = new order();
                        $order = $orderModel->find($orderId);
                        $userId = auth::handleUser();
                        // Bảo mật: Đảm bảo đơn hàng tồn tại, thuộc về tài khoản đang đăng nhập và đang chờ duyệt
                        if ($order && $order['MaTaiKhoan'] === $userId && $order['TrangThai'] === 'Đang chờ duyệt') {
                                $orderModel->update(['TrangThai' => 'Đã hủy'], $orderId);
                                // 2. Cập nhật trạng thái thanh toán thành 'Đã hủy'
                                $paymentModel = new payment();
                                $paymentModel->updatepayment(['TrangThai' => 'Đã hủy'], $orderId);
                                // 3. Cập nhật trạng thái giao hàng thành 'Đã hủy'
                                $transportModel = new transport();
                                $transportModel->update(['TrangThai' => 'Đã hủy'], 'GH' . $orderId);
                                // 4. Trả lại số lượng sản phẩm vào kho
                                $orderDetailModel = new orderdetail();
                                $details = $orderDetailModel->anyfind("SELECT * FROM chitietdonhang WHERE MaDonHang = '$orderId'");
                                $productModel = new product();
                                foreach ($details as $detail) {
                                        $productModel->deleteStock($detail['MaSanPham'], -$detail['SoLuong']);
                                }
                                Flash::set('success_sl_product', 'Hủy đơn hàng thành công và đã hoàn lại số lượng kho!');
                        } else {
                                Flash::set('error_sl_product', 'Không thể hủy đơn hàng này hoặc trạng thái không hợp lệ!');
                        }
                }
                header("Location: " . url('OrderController/history'));
                exit;
        }
}
