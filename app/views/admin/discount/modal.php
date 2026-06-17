<div id="discountModal" class="position-fixed top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center p-3 p-md-4 custom-modal-backdrop hidden transition-all">
    
    <div class="bg-white rounded-4 w-100 p-4 p-sm-5 border border-light-subtle shadow-lg custom-modal-box transition-all" style="max-width: 580px;">
        
        <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom border-light-subtle">
            <h3 id="modalTitle" class="fs-4 fw-bold gradient-text-privia mb-0">
                Thêm mã giảm giá mới
            </h3>
            <button type="button" onclick="closeModal()" class="btn btn-light rounded-3 p-0 d-flex align-items-center justify-content-center text-muted" style="width: 36px; height: 36px; font-size: 1.5rem; line-height: 1;">
                &times;
            </button>
        </div>
        
        <form id="discountForm" method="POST" action="">
            <input type="hidden" id="disIdHidden" name="MaGiamGiaHidden">

            <div class="d-flex flex-column gap-3-5">
                <div>
                    <label class="form-label fw-semibold text-secondary small text-uppercase tracking-wider mb-2">
                        Tên chương trình khuyến mãi <span class="text-danger">*</span>
                    </label>
                    <input type="text" id="disName" name="TenGiamGia" required 
                           placeholder="Nhập tên giảm giá..." 
                           class="form-control input-privia rounded-3 px-3 py-2-5 shadow-sm">
                </div>

                <div>
                    <label class="form-label fw-semibold text-secondary small text-uppercase tracking-wider mb-2">
                        Mức giảm giá (%) <span class="text-danger">*</span>
                    </label>
                    <input type="number" id="disPercent" name="PhanTram" required min="0" max="100" 
                           placeholder="Nhập số từ 0 đến 100" 
                           class="form-control input-privia rounded-3 px-3 py-2-5 shadow-sm">
                </div>

                <div class="row g-3">
                    <div class="col-12 col-sm-6">
                        <label class="form-label fw-semibold text-secondary small text-uppercase tracking-wider mb-2">
                            Ngày bắt đầu <span class="text-danger">*</span>
                        </label>
                        <input type="datetime-local" id="disStartDate" name="NgayBatDau" required 
                               class="form-control input-privia rounded-3 px-3 py-2-5 shadow-sm cursor-pointer">
                    </div>
                    <div class="col-12 col-sm-6">
                        <label class="form-label fw-semibold text-secondary small text-uppercase tracking-wider mb-2">
                            Ngày kết thúc <span class="text-danger">*</span>
                        </label>
                        <input type="datetime-local" id="disEndDate" name="NgayKetThuc" required 
                               class="form-control input-privia rounded-3 px-3 py-2-5 shadow-sm cursor-pointer">
                    </div>
                </div>
            </div>

            <div class="mt-4 pt-4 border-top border-light-subtle d-flex justify-content-end gap-2">
                <button type="button" onclick="closeModal()" class="btn btn-light text-secondary fw-medium rounded-3 px-4 py-2">
                    Hủy bỏ
                </button>
                <button type="submit" class="btn btn-gradient-privia fw-semibold rounded-3 px-4 py-2">
                    Lưu lại
                </button>
            </div>
        </form>

    </div>
</div>