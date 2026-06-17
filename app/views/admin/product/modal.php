<div id="productModal" class="position-fixed top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center p-3 p-md-4 custom-modal-backdrop hidden transition-all">
    
    <div class="bg-white rounded-4 w-100 p-4 p-md-5 border border-light-subtle shadow-lg custom-modal-box transition-all" style="max-width: 768px; max-height: 90vh; display: flex; flex-direction: column;">
        
        <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom border-light-subtle flex-shrink-0">
            <h3 id="modalTitle" class="fs-4 fw-bold text-dark mb-0" style="letter-spacing: -0.02em;">
                Thêm sản phẩm mới
            </h3>
            <button type="button" onclick="closeModal()" class="btn btn-close-luxury p-0 d-flex align-items-center justify-content-center text-muted" style="width: 32px; height: 32px; font-size: 1.25rem; line-height: 1;">
                &times;
            </button>
        </div>
        
        <form id="productForm" method="POST" action="" enctype="multipart/form-data" class="d-flex flex-column flex-grow-1 overflow-hidden" style="min-height: 0;">
            <input type="hidden" id="proId" name="MaSanPham">

            <div class="overflow-y-auto pe-2 flex-grow-1 custom-scrollbar mb-1">
                <div class="row g-4 m-0">
                    
                    <div class="col-12 p-0">
                        <label class="form-label fw-bold text-dark small text-uppercase tracking-wider mb-2" style="font-size: 0.75rem; color: #4b5563 !important;">
                            Tên sản phẩm <span class="text-danger">*</span>
                        </label>
                        <input type="text" id="proName" name="TenSanPham" required placeholder="Nhập tên sản phẩm..." 
                               class="form-control input-product rounded-3 px-3 py-2-5 shadow-none">
                    </div>

                    <div class="col-12 col-md-6 ps-0 pe-md-2 p-0">
                        <label class="form-label fw-bold text-dark small text-uppercase tracking-wider mb-2" style="font-size: 0.75rem; color: #4b5563 !important;">
                            Giá bán (VNĐ)
                        </label>
                        <input type="number" id="proPrice" name="Gia" required placeholder="0" min="0"
                               class="form-control input-product rounded-3 px-3 py-2-5 shadow-none">
                    </div>

                    <div class="col-12 col-md-6 pe-0 ps-md-2 p-0">
                        <label class="form-label fw-bold text-dark small text-uppercase tracking-wider mb-2" style="font-size: 0.75rem; color: #4b5563 !important;">
                            Tồn kho
                        </label>
                        <input type="number" id="proStock" name="SoLuong" required placeholder="0" min="0"
                               class="form-control input-product rounded-3 px-3 py-2-5 shadow-none">
                    </div>
                    
                    <div class="col-12 col-md-6  ps-0 pe-md-2 p-0">
                        <label class="form-label fw-bold text-dark small text-uppercase tracking-wider mb-2" style="font-size: 0.75rem; color: #4b5563 !important;">
                            Danh mục <span class="text-danger">*</span>
                        </label>
                        <select id="proCategoryId" name="MaDanhMuc" required class="form-select input-product rounded-3 px-3 py-2-5 shadow-none">
                            <option value="">-- Chọn danh mục --</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= $cat['MaDanhMuc'] ?>"><?= htmlspecialchars($cat['TenDanhMuc']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-12 col-md-6 pe-0 ps-md-2 p-0">
                        <label class="form-label fw-bold text-dark small text-uppercase tracking-wider mb-2" style="font-size: 0.75rem; color: #4b5563 !important;">
                            Trạng thái
                        </label>
                        <select id="proStatus" name="TrangThai" class="form-select input-product rounded-3 px-3 py-2-5 shadow-none">
                            <option value="Còn hàng">Còn hàng</option>
                            <option value="Bán chạy">Bán chạy</option>
                            <option value="Cao cấp">Cao cấp</option>
                            <option value="Mới">Mới</option>
                            <option value="Giảm giá">Giảm giá</option>
                        </select>
                    </div>

                    <div class="col-12 ps-0 pe-0 p-0">
                        <label class="form-label fw-bold text-dark small text-uppercase tracking-wider mb-2" style="font-size: 0.75rem; color: #4b5563 !important;">
                            Chương trình giảm giá áp dụng
                        </label>
                        <select id="proDiscountId" name="MaGiamGia" class="form-select input-product rounded-3 px-3 py-2-5 shadow-none">
                            <option value="">-- Không áp dụng giảm giá --</option>
                            <?php foreach ($discounts as $dis): ?>
                                <option value="<?= $dis['MaGiamGia'] ?>">
                                    <?= htmlspecialchars($dis['TenGiamGia']) ?> (Giảm <?= number_format($dis['PhanTram']) ?>%)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-12 p-0">
                        <div class="row g-3 align-items-center p-3 rounded-3 border border-light-subtle m-0" style="background-color: #f9fafb;">
                            <div class="col-12 col-md-8 p-0 pe-md-3">
                                <label class="form-label fw-bold text-dark small text-uppercase tracking-wider mb-2" style="font-size: 0.75rem; color: #4b5563 !important;">
                                    Hình ảnh sản phẩm
                                </label>
                                <input type="file" id="proImage" name="HinhAnh" accept="image/*"
                                       class="form-control input-product rounded-3 px-3 py-2 bg-white small shadow-none">
                                <small class="text-muted mt-1 d-block" style="font-size: 0.8rem;">Chọn ảnh mới nếu muốn thay thế ảnh cũ.</small>
                            </div>

                            <div id="previewContainer" class="hidden col-12 col-md-4 d-flex flex-column align-items-center justify-content-center border-start border-light-subtle ps-0 ps-md-3 pt-3 pt-md-0">
                                <p class="text-muted small fw-medium mb-2 w-100 text-center text-md-start" style="font-size: 0.8rem;">Ảnh xem trước:</p>
                                <img id="proImagePreview" src="" alt="Preview" class="rounded-3 border border-light-subtle bg-white" style="width: 76px; height: 76px; object-fit: cover; box-shadow: 0 2px 8px rgba(0,0,0,0.04);">
                            </div>
                        </div>
                    </div>

                    <div class="col-12 p-0">
                        <label class="form-label fw-bold text-dark small text-uppercase tracking-wider mb-2" style="font-size: 0.75rem; color: #4b5563 !important;">
                            Mô tả sản phẩm
                        </label>
                        <div class="khoi-boc-ckeditor">
                            <textarea id="proDesc" name="MoTa" placeholder="Nhập mô tả sản phẩm..." class="shadow-none" style="resize: none;"></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="pt-4 border-top border-light-subtle d-flex justify-content-end gap-2 flex-shrink-0">
                <button type="button" onclick="closeModal()" class="btn btn-cancel-luxury fw-medium rounded-3 px-4 py-2">
                    Hủy
                </button>
                <button type="submit" class="btn btn-submit-blue fw-semibold rounded-3 px-4 py-2">
                    Lưu sản phẩm
                </button>
            </div>
        </form>
    </div>
</div>