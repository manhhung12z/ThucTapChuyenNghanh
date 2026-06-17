document.addEventListener("DOMContentLoaded", function() {
    // Đã đồng bộ ID khớp chuẩn 100% với HTML phía dưới
    const modal = document.getElementById('discountModal');
    const modalTitle = document.getElementById('modalTitle');
    const form = document.getElementById('discountForm');
    const catIdInput = document.getElementById('disIdHidden'); // Khớp với input hidden mã danh mục/giảm giá
    const catNameInput = document.getElementById('disId');     // Khớp ô nhập tên/mã chính
    const catDescInput = document.getElementById('disName');   // Khớp ô nhập mô tả/chương trình
    const searchInput = document.getElementById('searchCategory');
    const tableBody = document.getElementById('categoryTableBody');
    const paginationBlock = document.getElementById('paginationBlock');
    
    let timeout = null;

    // Hàm xử lý Xóa danh mục bằng SweetAlert2 (Đã chuyển sang tone Đỏ Rose Quý Tộc)
    window.deleteCategory = function(id, btn) {
        if (window.CURRENT_USER_ROLE === 'staff') {
            Swal.fire({
                title: 'Không có quyền!',
                text: "Tài khoản nhân viên không được phép xóa danh mục.",
                icon: 'error',
                confirmButtonColor: '#f43f5e',
                customClass: {
                    popup: 'rounded-4 shadow-lg border border-light-subtle'
                }
            });
            return;
        }
        Swal.fire({
            title: 'Xóa danh mục?',
            text: "Hành động này không thể hoàn tác!",
            icon: 'warning',
            showCancelButton: true,
            heightAuto: false,
            confirmButtonColor: '#f43f5e', // Chuyển sang nút Xóa màu Đỏ Rose Luxury quyến rũ
            cancelButtonColor: '#9ca3af',  // Nút hủy xám mờ tinh tế
            confirmButtonText: 'Xóa ngay',
            cancelButtonText: 'Hủy'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = WEB_ROOT + "/admin/CategoryController/deletecategory?id=" + encodeURIComponent(id);
            }
        });
    };

    // Hàm điều khiển đóng/mở cấu trúc Modal
    window.openModal = function(action, catData = null) {
        if(!modal) return;
        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            modal.querySelector('.custom-modal-box').classList.remove('scale-95');
        }, 10);

        if (action === 'add') {
            modalTitle.innerText = "Thêm danh mục mới";
            form.action = WEB_ROOT + "/admin/CategoryController/create";
            if(catIdInput) catIdInput.value = ''; 
            if(catNameInput) catNameInput.value = ''; 
            if(catDescInput) catDescInput.value = '';
        } else if (action === 'edit') {
            modalTitle.innerText = "Chỉnh sửa danh mục";
            form.action = WEB_ROOT + "/admin/CategoryController/update";
            if(catIdInput) catIdInput.value = catData.MaDanhMuc;
            if(catNameInput) catNameInput.value = catData.TenDanhMuc;
            if(catDescInput) catDescInput.value = catData.MoTa;
        }
    };

    window.closeModal = function() {
        if(!modal) return;
        modal.classList.add('opacity-0');
        modal.querySelector('.custom-modal-box').classList.add('scale-95');
        setTimeout(() => modal.classList.add('hidden'), 300);
    };

    // Hàm gọi dữ liệu bất đồng bộ AJAX
    window.fetchData = function(page) {
        const keyword = searchInput ? searchInput.value : '';
        fetch(`${WEB_ROOT}/admin/CategoryController/searchAjax?q=${encodeURIComponent(keyword)}&page=${page}`)
            .then(res => res.json())
            .then(res => {
                renderTable(res.data);
                renderPagination(res.totalPages, res.currentPage);
            })
            .catch(err => console.error("Lỗi tải dữ liệu:", err));
    };

    // Render danh sách hàng của bảng danh mục
    function renderTable(data) {
        if (!tableBody) return;
        if (data.length === 0) {
            tableBody.innerHTML = `<tr><td colspan="4" class="text-center py-5 text-muted" style="font-size: 0.9rem;">Không tìm thấy danh mục nào.</td></tr>`;
            return;
        }
        
        tableBody.innerHTML = data.map(cat => {
            const catJson = JSON.stringify({  
                MaDanhMuc: cat.madanhmuc,
                TenDanhMuc: cat.tendanhmuc,
                MoTa: cat.mota
            }).replace(/"/g, '&quot;'); 

            return `
                <tr data-id="${cat.madanhmuc}" class="align-middle">
                    <td class="ps-4 fw-semibold text-dark" style="color: #111827 !important;">${cat.tendanhmuc}</td>
                    <td class="fw-bold text-secondary">${cat.tongsanpham || 0}</td>
                    <td class="text-muted">
                        <div class="text-truncate-custom" style="max-width: 350px;">
                            ${cat.mota || '<span class="text-muted-light italic" style="color: #cbd5e1;">Chưa có mô tả</span>'}
                        </div>
                    </td>
                    <td class="text-center pe-4">
                        <div class="d-flex justify-content-center align-items-center gap-2">
                            <button onclick='openModal("edit", ${catJson})' class="btn-action-privia btn-edit-privia">Sửa</button>
                            ${window.CURRENT_USER_ROLE !== 'staff' ? `<button onclick="deleteCategory('${cat.madanhmuc}', this)" class="btn-action-privia btn-delete-privia">Xóa</button>` : ''}
                        </div>
                    </td>
                </tr>
            `;
        }).join(''); 
    }
    
    // Render khối chuyển trang phân trang AJAX phong cách Đỏ Ruby Quý Tộc
    function renderPagination(totalPages, currentPage) {
        if (!paginationBlock) return;
        let html = '';

        if (currentPage > 1) {
            html += `<button onclick="fetchData(${currentPage - 1})" class="page-btn-privia">&larr;</button>`;
        }

        for (let i = 1; i <= totalPages; i++) {
            const isCurrent = i == currentPage;
            // Đỏ quý tộc: Nút hiện tại nhận màu nền #f43f5e phát sáng mềm
            const activeStyle = isCurrent 
                ? 'background-color: #f43f5e; border-color: #f43f5e; color: #ffffff; font-weight: 600; box-shadow: 0 4px 12px rgba(244, 63, 94, 0.2);' 
                : 'background-color: #ffffff; border-color: #e5e7eb; color: #4b5563;';
            
            html += `<button onclick="fetchData(${i})" class="page-btn-privia" style="${activeStyle}">${i}</button>`;
        }

        if (currentPage < totalPages) {
            html += `<button onclick="fetchData(${currentPage + 1})" class="page-btn-privia">&rarr;</button>`;
        }

        paginationBlock.innerHTML = html;
    }

    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(timeout);
            timeout = setTimeout(() => { fetchData(1); }, 500);
        });
    }
});