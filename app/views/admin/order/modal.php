<div id="orderModal" class="position-fixed top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center p-3 p-md-4 custom-modal-backdrop hidden transition-all">
    
    <div class="bg-white rounded-4 w-100 p-4 p-sm-5 border border-light-subtle shadow-lg custom-modal-box transition-all" style="max-width: 500px;">
        
        <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom border-light-subtle">
            <h3 id="modalTitle" class="fs-4 fw-bold gradient-text-rose mb-0" style="letter-spacing: -0.01em;">
                Cập nhật đơn hàng
            </h3>
            <button type="button" onclick="closeOrderModal()" class="btn btn-close-luxury p-0 d-flex align-items-center justify-content-center text-muted" style="width: 32px; height: 32px; font-size: 1.25rem; line-height: 1;">
                &times;
            </button>
        </div>
        
        <form action="<?= _WEB_ROOT ?>/admin/OrderController/update" method="POST" id="orderForm">
            
            <div class="d-flex flex-column gap-4">
                
                <div>
                    <label class="form-label fw-bold text-secondary small text-uppercase tracking-wider mb-2" style="font-size: 0.75rem;">
                        Mã đơn hàng
                    </label>
                    <input type="text" name="MaDonHang" id="modalMaDonHang" readonly 
                           class="form-control input-privia bg-light text-secondary rounded-3 px-3 py-2-5 shadow-none border-light-subtle">
                </div>

                <div>
                    <label class="form-label fw-bold text-secondary small text-uppercase tracking-wider mb-2" style="font-size: 0.75rem;">
                        Khách hàng
                    </label>
                    <input type="text" id="modalKhachHang" disabled 
                           class="form-control input-privia bg-light text-secondary rounded-3 px-3 py-2-5 shadow-none border-light-subtle">
                </div>

                <div>
                    <label class="form-label fw-bold text-dark small text-uppercase tracking-wider mb-2" style="font-size: 0.75rem; color: #4b5563 !important;">
                        Trạng thái đơn hàng <span class="text-danger">*</span>
                    </label>
                    <select name="TrangThai" id="modalTrangThai" class="form-select input-privia rounded-3 px-3 py-2-5 shadow-none text-dark fw-medium style-select-privia">
                        <option value="Đang chờ duyệt" id="optChoXacNhan">Đang chờ duyệt</option>
                        <option value="Đang giao hàng" id="optDangGiao">Đang giao hàng</option>
                        <option value="Giao hàng thành công" id="optThanhCong" style="display: none;">Giao hàng thành công</option>
                        <option value="Đã hủy" id="optDaHuy" style="display: none;">Đã hủy</option>
                </select>
                <input type="hidden" name="TrangThaiGoc" id="modalTrangThaiGoc">
                </div>
                
            </div>

            <div class="mt-4 pt-4 border-top border-light-subtle d-flex justify-content-end gap-2">
                <button type="button" onclick="closeOrderModal()" class="btn btn-cancel-luxury fw-medium rounded-3 px-4 py-2">
                    Hủy
                </button>
                <button id="btnSubmit" type="submit" class="btn btn-submit-rose fw-semibold rounded-3 px-4 py-2">
                    Cập nhật
                </button>
            </div>
        </form>

    </div>
</div>
