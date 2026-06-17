<div id="accountModal" class="position-fixed top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center p-3 p-md-4 custom-modal-backdrop hidden transition-all">
    
    <div class="bg-white rounded-4 w-100 p-4 p-sm-5 border border-light-subtle shadow-lg custom-modal-box transition-all" style="max-width: 620px;">
        
        <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom border-light-subtle">
            <h3 id="modalTitle" class="fs-4 fw-bold gradient-text-privia mb-0">
                Thêm tài khoản mới
            </h3>
            <button type="button" onclick="closeModal()" class="btn btn-light rounded-3 p-0 d-flex align-items-center justify-content-center text-muted" style="width: 36px; height: 36px; font-size: 1.5rem; line-height: 1;">
                &times;
            </button>
        </div>
        
        <form id="accountForm" method="POST" action="">
            <input type="hidden" id="accId" name="MaTaiKhoan">

            <div class="d-flex flex-column gap-3-5">
                <div>
                    <label class="form-label fw-semibold text-secondary small text-uppercase tracking-wider mb-2">
                        Tên người dùng <span class="text-danger">*</span>
                    </label>
                    <input type="text" id="accName" name="TenNguoiDung" required placeholder="Không có tên" readonly
                           class="form-control bg-light text-muted rounded-3 px-3 py-2-5 shadow-sm fw-medium border-light-subtle">
                </div>

                <div>
                    <label class="form-label fw-semibold text-secondary small text-uppercase tracking-wider mb-2">
                        Địa chỉ Email <span class="text-danger">*</span>
                    </label>
                    <input type="email" id="accEmail" name="Email" required placeholder="Không có email" readonly
                           class="form-control bg-light text-muted rounded-3 px-3 py-2-5 shadow-sm fw-medium border-light-subtle">
                </div>

                <div class="row g-3 ">
                    <div class="col-12 col-sm-6">
                        <label class="form-label fw-semibold text-secondary small text-uppercase tracking-wider mb-2">
                            Số điện thoại
                        </label>
                        <input type="tel" id="accPhone" name="SoDienThoai" placeholder="Không có" readonly
                               class="form-control bg-light text-muted rounded-3 px-3 py-2-5 shadow-sm fw-medium border-light-subtle">
                    </div>
                    
                    <div class="col-12 col-sm-6">
                        <label class="form-label fw-semibold text-secondary small text-uppercase tracking-wider mb-2">
                            Phân quyền hệ thống <span class="text-danger">*</span>
                        </label>
                        <select id="accRole" name="LoaiTaiKhoan" required 
                                class="form-select input-privia rounded-3 px-3 py-2-5 shadow-sm bg-white cursor-pointer">
                            <option value="user">Người dùng (User)</option>
                            <option value="admin">Quản trị viên (Admin)</option>
                            <option value="staff">Nhân viên (Staff)</option>
                        </select>
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
