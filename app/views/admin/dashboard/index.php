<?php require_once(_DIR_ROOT . '/app/views/admin/layouts/index.php') ?>

<?php startblock('Acss', 0) ?>
    <link href="<?= asset('assets/admin/css/styledashboard.css') ?>" rel="stylesheet">

<?php endblock() ?>

<?php startblock('Atitle', 0) ?>
<h1 class="fs-4 fw-bold text-dark mb-0 text-truncate" style="letter-spacing: -0.02em;">Tổng quan hệ thống</h1>
<?php endblock() ?>

<?php startblock('Acontent', 0) ?>
    <div class="row g-4 mb-4">
        <div class="col-12">    
            <div class="privia-card p-4">
                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 mb-4">
                    <div>
                        <h2 class="fw-bold text-dark mb-1" style="font-size: 1.1rem;">Báo cáo doanh thu theo thời gian</h2>
                        <p class="text-muted small mb-0">Theo dõi dòng tiền và tiến độ tăng trưởng doanh số</p>
                    </div>
                    <div class="d-flex justify-content-end">
                        <select id = "revenueTimeframe" class="form-control search-privia py-1-5 px-3 shadow-none text-center " style="width: auto; font-size: 0.85rem;">
                            <option value="7days" selected>7 ngày qua</option>
                            <option value="month" >Tháng này</option>
                            <option value="year">Năm nay</option>
                        </select>
                    </div>
                </div>
                <div class="chart-container">
                    <canvas id="revenueTimelineChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
            <div class="privia-card h-100 overflow-hidden">
                <div class="p-4 bg-white">
                    <h2 class="fw-bold text-dark mb-1" style="font-size: 1.1rem;">Top sản phẩm doanh thu cao nhất</h2>
                    <p class="text-muted small mb-0">Chi tiết sản phẩm mang lại nguồn thu lớn nhất hệ thống Privia</p>
                </div>
                
                <div class="table-responsive w-100">
                    <table class="table table-borderless table-privia align-middle mb-0">
                        <thead>
                    <tr>
                        <th class="ps-4" style="width: 15%;">HẠNG</th>
                        <th style="width: 45%;">SẢN PHẨM</th>
                        <th style="width: 15%;">ĐÃ BÁN</th>
                        <th class="text-center pe-4" style="width: 25%;">DOANH THU</th>
                    </tr>
                </thead>
                <tbody id="dashboardTableBody">
                    <?php 
                    $hang=1;
                    if (!empty($TopRevenueProducts)): 
                        foreach ($TopRevenueProducts as $dashpro): 
                            $productJson = json_encode([
                                'MaSanPham' => $dashpro['masanpham'] ?? '',
                                'TenSanPham' => $dashpro['tensanpham'] ?? '',
                                'SoLuongDaBan' => $dashpro['daban'] ?? '',
                                'DoanhThu' => $dashpro['doanhthu'] ?? ''
                            ], JSON_UNESCAPED_UNICODE);
                            $productJsonHtml = htmlspecialchars($productJson, ENT_QUOTES, 'UTF-8');
                    ?>
                        <tr data-id="<?php echo $dashpro['masanpham']; ?>">
                            <td class="ps-4 fw-bold text-dark" style="color: #000000 !important;">
                                <?php echo $hang++ ?>
                            </td>
                            <td class=" fw-semibold text-dark" style="color: #000000 !important;">
                                <?php echo $dashpro['tensanpham']; ?>
                            </td>
                            <td class="ps-4" style="color: #4b5563;">
                                <?php echo $dashpro['daban']; ?>
                            </td>
                            <td class="text-center pe-4 fw-bold" style="color: #111827 !important;">
                                <?php echo number_format($dashpro['doanhthu'], 0, ',', '.'); ?>
                            </td>
                        </tr>
                    <?php 
                        endforeach;
                    else: 
                    ?>
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted" style="font-size: 0.9rem;">
                                Chưa có danh mục mỹ phẩm nào trong hệ thống Privia.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
                    </table>
                </div>
            </div>
    </div>
<?php endblock() ?>

<?php startblock('Ajs', 0) ?>
    <script>const WEB_ROOT = "<?= _WEB_ROOT ?>";</script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="<?= asset('assets/admin/js/dashboard.js') ?>"></script>
<?php endblock() ?>