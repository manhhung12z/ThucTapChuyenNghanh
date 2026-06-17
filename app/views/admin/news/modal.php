
<div id="newsModal" class="position-fixed top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center p-3 p-md-4 custom-modal-backdrop hidden transition-all">
    
    <div class="bg-white rounded-4 w-100 p-4 p-sm-5 border border-light-subtle shadow-lg custom-modal-box transition-all" style="max-width: 680px;">
        
        <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom border-light-subtle">
            <h3 id="modalTitle" class="fs-4 fw-bold gradient-text-privia mb-0">
                Thêm tin tức mới
            </h3>
            <button type="button" onclick="closeModal()" class="btn btn-light rounded-3 p-0 d-flex align-items-center justify-content-center text-muted" style="width: 36px; height: 36px; font-size: 1.5rem; line-height: 1;">
                &times;
            </button>
        </div>
        
        <form id="newsForm" method="POST" action="">
            <input type="hidden" id="newsId" name="MaTinTuc">

            <div class="d-flex flex-column gap-3-5">
                <div>
                    <label class="form-label fw-semibold text-secondary small text-uppercase tracking-wider mb-2">
                        Tiêu đề bài viết <span class="text-danger">*</span>
                    </label>
                    <input type="text" id="newsTitle" name="Tieude" required 
                           placeholder="Nhập tiêu đề cho tin tức..." 
                           class="form-control input-privia rounded-3 px-3 py-2-5 shadow-sm">
                </div>

                <div>
                    <label class="form-label fw-semibold text-secondary small text-uppercase tracking-wider mb-2">
                        Nội dung chi tiết <span class="text-danger">*</span>
                    </label>
                    <div class="shadow-sm rounded-3 overflow-hidden">
                        <textarea id="newsContent" name="NoiDung" placeholder="Nhập nội dung chi tiết bài viết tại đây..."></textarea>
                    </div>
                </div>
            </div>

            <div class="mt-4 pt-4 border-top border-light-subtle d-flex justify-content-end gap-2">
                <button type="button" onclick="closeModal()" class="btn btn-light text-secondary fw-medium rounded-3 px-4 py-2">
                    Hủy bỏ
                </button>
                <button type="submit" class="btn btn-gradient-privia fw-semibold rounded-3 px-4 py-2">
                    Lưu bài viết
                </button>
            </div>
        </form>

    </div>
</div>
