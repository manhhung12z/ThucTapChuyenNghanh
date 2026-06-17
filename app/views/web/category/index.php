<?php require_once(_DIR_ROOT . '/app/views/web/layouts/index.php') ?>
<?php startblock('css') ?>
<style>
    .filter-box {
        border-right: 1px solid #eee;
        padding-right: 30px;
    }

    .filter-box h6 {
        font-weight: 700;
        margin-bottom: 20px;
        font-size: 1.1rem !important;
        color: #212529 !important;
        border-bottom: 2px solid #f8f9fa !important;
        text-transform: uppercase !important;
        letter-spacing: 0.5px !important;
        padding-bottom: 10px !important;
    }

    .filter-box label {
        display: block;
        margin-bottom: 14px;
        font-size: 14px;
        color: #555;
    }
    @media(max-width:992px)
    {
        .filter-box {
            border-right: none;
            padding-right: 0;
            margin-bottom: 30px;
            border-bottom: 1px solid #eee;
            padding-bottom: 20px;

        }
    }
</style>
<?php endblock() ?>
<?php startblock('content') ?>
<div class="container py-5">
    <form method="GET" action="<?php echo url('CategoryController/index') ?>">
        <div class="row">
            <!--sidebar -->
            <div class="col-lg-3">
                <div class="filter-box">
                    <h6>Category</h6>
                    <?php if (!empty($categories)) {
                        foreach ($categories as $category) { ?>
                            <label><input type="radio" name="MaDanhMuc" value="<?php echo $category['MaDanhMuc']; ?>" onchange="this.form.submit()" <?php echo (isset($currentCategory) && $currentCategory == $category['MaDanhMuc']) ? 'checked' : ''; ?>> <?php echo htmlspecialchars($category['TenDanhMuc']); ?></label>
                    <?php }
                    } ?>
                </div>

            </div>
            <div class="col-lg-9">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="d-flex justify-content-between align-items-center mb-5">
                            <select class="form-select w-auto" name="sort" onchange="this.form.submit()">
                                <option value="" <?php echo ($selectedSort == '') ? 'selected' : ''; ?>>Top Sellers</option>
                                <option value="price_low_to_high" <?php echo ($selectedSort == 'price_low_to_high') ? 'selected' : ''; ?>>Price Low to High</option>
                            </select>
                            <div>
                                <i class="fa-solid fa-border-all me-3"></i>
                                <i class="fa-solid fa-list"></i>
                            </div>
                        </div>
                        <div class="product-best-slider">
                            <div class="col-12">
                                <div class="imagecard_copy gap-4" style="margin-top: 60px;">
                                    <?php if (!empty($products)) {
                                        foreach ($products as $product) { ?>
                                            <a href="<?php echo url('ProductController?MaSanPham=' . $product['MaSanPham']) ?>"
                                                style="text-decoration: none; color: inherit;">
                                                <div class="card_image">
                                                    <div class="discount-badge">
                                                        -<?php echo $product['PhanTram'] ?>%
                                                    </div>
                                                    <img src="<?php echo $product['HinhAnh'] ?>" class="card-img-top" alt="...">
                                                    <div class="card-body">
                                                        <h5 class="card-title"><?php echo $product['MaDanhMuc'] ?></h5>
                                                        <p class="card-text"><?php echo $product['TenSanPham'] ?></p>
                                                        <div class="price-box">
                                                            <span class="old-price">
                                                                <?php echo number_format($product['Gia']) ?>đ
                                                            </span>

                                                            <span class="new-price">
                                                                <?php echo number_format($product['GiaSauGiam']) ?>đ
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                    <?php }
                                    } else {
                                        echo "<p>Không có sản phẩm nào trong danh mục này.</p>";
                                    } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<?php endblock() ?>