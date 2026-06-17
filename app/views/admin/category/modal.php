<div id="discountModal" class="position-fixed top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center p-3 p-md-4 custom-modal-backdrop hidden transition-all">
    
    <div class="bg-white rounded-4 w-100 p-4 p-sm-5 border border-light-subtle shadow-lg custom-modal-box transition-all" style="max-width: 580px;">
        
        <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom border-light-subtle">
            <h3 id="modalTitle" class="fs-4 fw-bold text-dark mb-0" style="letter-spacing: -0.02em; color: #0f172a !important;">
                Thêm danh mục mới
            </h3>
            <button type="button" onclick="closeModal()" class="btn btn-close-luxury p-0 d-flex align-items-center justify-content-center text-muted" style="width: 32px; height: 32px; font-size: 1.25rem; line-height: 1;">
                &times;
            </button>
        </div>
        
        <form id="discountForm" method="POST" action="">
            <input type="hidden" id="disIdHidden" name="MaGiamGiaHidden">

            <div class="d-flex flex-column gap-3.5">
                <div>
                    <label class="form-label fw-bold text-dark small text-uppercase tracking-wider mb-2" style="font-size: 0.75rem; color: #4b5563 !important;">
                        Tên danh mục <span class="text-danger">*</span>
                    </label>
                    <input type="text" id="disId" name="TenDanhMuc" required 
                           placeholder="Nhập tên danh mục..." 
                           class="form-control input-privia rounded-3 px-3 py-2-5 shadow-none">
                </div>

                <div>
                    <label class="form-label fw-bold text-dark small text-uppercase tracking-wider mb-2" style="font-size: 0.75rem; color: #4b5563 !important;">
                        Mô tả chi tiết
                    </label>
                    <textarea id="disName" name="MoTa" rows="7" 
                              placeholder="Nhập mô tả ngắn..." 
                              class="form-control input-privia rounded-3 px-3 py-3 shadow-none w-100 h-auto lh-base" 
                              style="resize: vertical;"></textarea>
                </div>
            </div>

            <div class="mt-4 pt-4 border-top border-light-subtle d-flex justify-content-end gap-2">
                <button type="button" onclick="closeModal()" class="btn btn-cancel-luxury fw-medium rounded-3 px-4 py-2">
                    Hủy bỏ
                </button>
                <button type="submit" class="btn btn-submit-luxury fw-semibold rounded-3 px-4 py-2">
                    Lưu lại
                </button>
            </div>
        </form>

    </div>
</div>

