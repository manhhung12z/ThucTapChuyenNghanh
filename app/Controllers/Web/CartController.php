<?php
require_once(_DIR_ROOT . '/app/Controllers/Web/WebController.php');
require_once(_DIR_ROOT . '/app/Models/Web/cart.php');
require_once(_DIR_ROOT . '/core/auth.php');
require_once(_DIR_ROOT . '/app/Models/Web/product.php');

class CartController extends WebController
{
    private function getGuestCart()
    {
        return $_SESSION['GuestCart'] ?? [];
    }
    private function saveGuestCart($cart)
    {
        $_SESSION['GuestCart'] = $cart;
    }
    public static function mergeGuestCartToDb($userId)
    {
        if (empty($_SESSION['GuestCart']))
            return;
        $cartModel = new cart();
        $maGioHang = $cartModel->getOrCreateCart($userId);
        foreach ($_SESSION['GuestCart'] as $maSanPham => $item) {
            $cartModel->addItem($maGioHang, $maSanPham, $item['SoLuongMua']);
        }
        unset($_SESSION['GuestCart']);
    }


    public function getCount()
    {
        header('Content-Type: application/json');
        $cartModel = new cart();
        $user = auth::getUser('user');
        $cartCount = 0;
        if ($user) {
            $maGioHang = $cartModel->getOrCreateCart($user['MaTaiKhoan']);
            $cartCount = $cartModel->countItems($maGioHang);
        } else {
            if (!empty($_SESSION['GuestCart'])) {
                foreach ($_SESSION['GuestCart'] as $item) {
                    $cartCount += $item['SoLuongMua'] ?? 0;
                }
            }
        }
        echo json_encode(['success' => true, 'cartCount' => $cartCount]);
        exit;
    }

    public function add()
    {
        header('Content-Type: application/json');
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request']);
            exit;
        }
        $cartModel = new cart();
        $MaSanPham = $_POST['MaSanPham'] ?? null;
        $SoLuongMua = (int) ($_POST['number_detail'] ?? 1);
        $GiaSauGiam = $_POST['GiaSauGiam'] ?? 0;
        $user = auth::getUser('user');
        $productModel = new product();
        $productDetail = $productModel->findid($MaSanPham);
        if (!$productDetail) {
            echo json_encode(['success' => false, 'message' => 'Không tìm thấy sản phẩm']);
            exit;
        }
        if ($user) {
            // ĐÃ ĐĂNG NHẬP → DATABASE
            $maGioHang = $cartModel->getOrCreateCart($user['MaTaiKhoan']);
            $cartModel->addItem($maGioHang, $MaSanPham, $SoLuongMua);
            $totalItems = $cartModel->countItems($maGioHang);
            $_SESSION['cart_count_' . $user['MaTaiKhoan']] = $totalItems;
            echo json_encode(['success' => true, 'totalItems' => $totalItems]);
        } else {
            // CHƯA ĐĂNG NHẬP → SESSION
            $guestcart = $this->getGuestCart();
            $cartItem = [
                'TenSanPham' => $productDetail['TenSanPham'] ?? '',
                'GiaSauGiam' => $GiaSauGiam,
                'SoLuongMua' => $SoLuongMua,
                'Gia' => $productDetail['Gia'] ?? 0,
                'HinhAnh' => $productDetail['HinhAnh'] ?? ''
            ];
            if (isset($guestcart[$MaSanPham])) {
                $guestcart[$MaSanPham]['SoLuongMua'] += $SoLuongMua;
            } else {
                $guestcart[$MaSanPham] = $cartItem;
            }
            $this->saveGuestCart($guestcart);
            $totalItems = 0;
            foreach ($guestcart as $item)
                $totalItems += $item['SoLuongMua'];
            echo json_encode(['success' => true, 'totalItems' => $totalItems]);
        }
    }
    public function remove()
    {
        $cartModel = new cart();
        header('Content-Type: application/json');
        $MaSanPham = $_POST['MaSanPham'] ?? null;
        $user = auth::getUser('user');
        if ($user) {
            $magiohang = $cartModel->getOrCreateCart($user['MaTaiKhoan']);
            $cartModel->removeItem($magiohang, $MaSanPham);
            $totalItems = $cartModel->countItems($magiohang);
            $_SESSION['cart_count_' . $user['MaTaiKhoan']] = $totalItems;
        } else {
            $guestcart = $this->getGuestCart();
            unset($guestcart[$MaSanPham]);
            $this->saveGuestCart($guestcart);
            $totalItems = 0;
            foreach ($this->getGuestCart() as $item)
                $totalItems += $item['SoLuongMua'];
        }
        echo json_encode(['success' => true, 'totalItems' => $totalItems]);
        exit;
    }
    public function update()
    {
        $cartModel = new cart();
        header('Content-Type: application/json');
        $MaSanPham = $_POST['MaSanPham'] ?? null;
        $SoLuong = (int) ($_POST['SoLuong'] ?? 1);
        $user = auth::getUser('user');
        if ($user) {
            $maGioHang = $cartModel->getOrCreateCart($user['MaTaiKhoan']);
            $cartModel->updateItemQuantity($maGioHang, $MaSanPham, $SoLuong);
            $totalItems = $cartModel->countItems($maGioHang);
            $_SESSION['cart_count_' . $user['MaTaiKhoan']] = $totalItems;
        } else {
            $guestCart = $this->getGuestCart();
            if (isset($guestCart[$MaSanPham])) {
                if ($SoLuong <= 0)
                    unset($guestCart[$MaSanPham]);
                else
                    $guestCart[$MaSanPham]['SoLuongMua'] = $SoLuong;
                $this->saveGuestCart($guestCart);
            }
            $totalItems = 0;
            foreach ($this->getGuestCart() as $item)
                $totalItems += $item['SoLuongMua'];
        }
        echo json_encode(['success' => true, 'totalItems' => $totalItems]);
        exit;
    }
    public function index()
    {
        $cartModel = new cart();
        $user = auth::getUser('user');
        $cartItems = [];
        if ($user) {
            $cart = $cartModel->findByUser($user['MaTaiKhoan']);
            if ($cart) {
                $cartItems = $cartModel->getCartItems($cart['MaGioHang']);
            }
        } else {
            foreach ($this->getGuestCart() as $maSP => $item) {
                $cartItems[] = [
                    'MaSanPham' => $maSP,
                    'TenSanPham' => $item['TenSanPham'],
                    'GiaSauGiam' => $item['GiaSauGiam'],
                    'SoLuongMua' => $item['SoLuongMua'],
                    'Gia' => $item['Gia'] ?? 0,
                    'HinhAnh' => $item['HinhAnh'] ?? ''
                ];
            }
        }
        return $this->views('cart/index', ['cartItems' => $cartItems]);
    }
}
