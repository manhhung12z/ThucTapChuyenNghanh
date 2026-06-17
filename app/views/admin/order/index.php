<?php require_once(_DIR_ROOT . '/app/views/admin/layouts/index.php') ?>

<?php startblock('Acss', 0) ?>
    <link href="<?= asset('assets/admin/css/styleorder.css') ?>" rel="stylesheet">
<?php endblock() ?>

<?php startblock('Atitle', 0) ?>
    <h1 class="fs-4 fw-bold text-dark mb-0 text-truncate">Quản lý đơn hàng</h1>
<?php endblock() ?>

<?php startblock('Acontent', 0) ?>
    <div class="privia-card overflow-hidden mb-4">
        
        <div class="px-4 py-4 d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center gap-3">
            <span class="fw-bold text-dark" style="font-size: 0.95rem; color: #1f2937 !important;">Tất cả đơn hàng</span>
            <input type="text" 
                   id="searchOrder"
                   placeholder="Tìm kiếm đơn hàng..." 
                   class="form-control search-privia w-100 shadow-none" 
                   style="max-width: 100%;">
        </div>

        <div class="table-responsive w-100 custom-scrollbar">
            <table class="table table-borderless table-privia align-middle mb-0" style="min-width: 760px;">
                <thead>
                    <tr>
                        <th class="ps-4" style="width: 15%;">MÃ ĐƠN HÀNG</th>
                        <th style="width: 25%;">KHÁCH HÀNG</th>
                        <th style="width: 20%;">NGÀY ĐẶT</th>
                        <th style="width: 15%;">TỔNG TIỀN</th>
                        <th class="text-center" style="width: 13%;">TRẠNG THÁI</th>
                        <th class="text-center pe-4" style="width: 12%;">HÀNH ĐỘNG</th>
                    </tr>
                </thead>
                <tbody id="orderTableBody">
                    <?php 
                    if (!empty($orders)): 
                        foreach ($orders as $order): 
                            $orderJson = json_encode([
                                'MaDonHang' => $order['madonhang'] ?? '',
                                'TenNguoiDung' => $order['tennguoidung'] ?? $order['mataikhoan'],
                                'TrangThai' => $order['trangthai'] ?? ''
                            ], JSON_UNESCAPED_UNICODE);
                            $orderJsonHtml = htmlspecialchars($orderJson, ENT_QUOTES, 'UTF-8');
                    ?>
                        <tr data-id="<?php echo $order['madonhang']; ?>">
                            <td class="ps-4">
                                <span class="font-monospace bg-light border border-light-subtle px-2 py-1 rounded text-secondary small">
                                    <?php echo $order['madonhang']; ?>
                                </span>
                            </td>
                            
                            <td class="fw-semibold text-dark truncate-customer" style="color: #111827 !important;">
                                <?php echo !empty($order['tennguoidung']) ? $order['tennguoidung'] : 'Mã: ' . $order['manguoidung']; ?>
                            </td>
                            
                            <td class="text-secondary">
                                <?php echo date('d/m/Y H:i', strtotime($order['ngaydat'])); ?>
                            </td>

                            <td class="fw-bold text-primary">
                                <?php echo number_format($order['tongtien']); ?>đ
                            </td>
                            
                            <td class="text-center">
                                <?php 
                                $status = $order['trangthai'];
                                $classes = "d-inline-flex align-items-center justify-content-center text-nowrap px-3 py-1 rounded-pill border shadow-sm user-select-none fw-semibold";
                                $style = "min-width: 110px; font-size: 0.725rem;";
                                
                                switch ($status) {
                                    case 'Giao hàng thành công': 
                                        echo "<span class='$classes bg-success-subtle text-success border-success-subtle' style='$style'>$status</span>"; 
                                        break;
                                    case 'Đang giao hàng': 
                                        echo "<span class='$classes bg-primary-subtle text-primary border-primary-subtle' style='$style'>$status</span>"; 
                                        break;
                                    case 'Đang chờ duyệt': 
                                        echo "<span class='$classes bg-warning-subtle text-warning border-warning-subtle' style='$style'>$status</span>"; 
                                        break;
                                    case 'Đã hủy': 
                                        echo "<span class='$classes bg-danger-subtle text-danger border-danger-subtle' style='$style'>$status</span>"; 
                                        break;
                                    default:         
                                        echo "<span class='$classes bg-light text-secondary border-light-subtle' style='$style'>$status</span>"; 
                                        break;
                                }
                                ?>
                            </td>
                            
                            <td class="text-center pe-4">
                                <div class="d-flex justify-content-center align-items-center">
                                    <button data-order="<?= $orderJsonHtml ?>" class="btn-edit-order btn btn-action-privia btn-process-privia d-inline-flex align-items-center gap-1">
                                        ⚙️ Xử lý
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php 
                        endforeach;
                    else: 
                    ?>
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted" style="font-size: 0.9rem;">
                                Hệ thống chưa ghi nhận đơn hàng nào trong danh sách Privia.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div> 
        
        <?php require_once _DIR_ROOT . '/app/views/admin/layouts/includes/pagination.php'; ?>
    </div>

    <?php require_once _DIR_ROOT . '/app/views/admin/order/modal.php'; ?>
<?php endblock() ?>

<?php startblock('Ajs', 0) ?>
    <script>
        const WEB_ROOT = "<?= _WEB_ROOT ?>";
    </script>
    <script src="<?= asset('assets/admin/js/order.js') ?>"></script>
<?php endblock() ?>