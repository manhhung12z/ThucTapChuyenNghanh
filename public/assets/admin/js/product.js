document.addEventListener("DOMContentLoaded", function() {
    const modal = document.getElementById('productModal'); 
    const modalTitle = document.getElementById('modalTitle');
    const form = document.getElementById('productForm');

    const proIdInput = document.getElementById('proId');
    const proNameInput = document.getElementById('proName');
    const proPriceInput = document.getElementById('proPrice');
    const proStockInput = document.getElementById('proStock');
    const proStatusInput = document.getElementById('proStatus');
    const proDescInput = document.getElementById('proDesc');
    const searchInput = document.getElementById('searchProduct');
    const tableBody = document.getElementById('productTableBody');
    const paginationBlock = document.getElementById('paginationBlock');
    const previewContainer = document.getElementById('previewContainer');
    const imagePreview = document.getElementById('proImagePreview');
    const imageInput = document.getElementById('proImage');
    const editorElement = document.querySelector('#proDesc');
    

    let timeout = null;
    let editorInstance = null; // Biến lưu trữ đối tượng CKEditor


      function initializeEditor() {
        if (editorInstance) {
            return Promise.resolve(editorInstance);
        }
        if (!editorElement) {
            console.error('Không tìm thấy textarea #proDesc để khởi tạo CKEditor.');
            return Promise.reject(new Error('proDesc not found'));
        }
        const editorConfig = {
            toolbar: [ 
                'heading', '|', 
                'bold', 'italic', 'link', '|', 
                'bulletedList', 'numberedList', 'blockQuote', '|', 
                'undo', 'redo' 
            ]
        };
        return ClassicEditor
            .create(editorElement, editorConfig)
            .then(editor => {
                editorInstance = editor;
                return editor;
            })
            .catch(error => {
                console.error("Không thể khởi tạo bộ soạn thảo CKEditor:", error);
            });
    }

    function destroyEditor() {
        if (!editorInstance) {
            return Promise.resolve();
        }
        return editorInstance.destroy()
            .then(() => {
                editorInstance = null;
            })
            .catch(error => {
                console.error('Lỗi khi huỷ CKEditor:', error);
            });
    } 
  
    // Xóa sản phẩm 
    window.deleteProduct = function(id, btn) {
        if (window.CURRENT_USER_ROLE === 'staff') {
            Swal.fire({
                title: 'Không có quyền!',
                text: "Tài khoản nhân viên không được phép xóa sản phẩm.",
                icon: 'error',
                confirmButtonColor: '#2563eb',
                customClass: {
                    popup: 'rounded-4 shadow-lg border border-light-subtle'
                }
            });
            return;
        }
        Swal.fire({
            title: 'Xóa sản phẩm?',
            text: "Hành động này không thể hoàn tác!",
            icon: 'warning',
            showCancelButton: true,
            heightAuto: false,
            confirmButtonColor: '#2563eb', 
            cancelButtonColor: '#ef4444', 
            confirmButtonText: 'Xóa ngay',
            cancelButtonText: 'Hủy bỏ',
            customClass: {
                popup: 'rounded-4 shadow-lg border border-light-subtle'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = WEB_ROOT + "/admin/ProductController/delete?id=" + encodeURIComponent(id);
            }
        });
    };

    // Quản lý trạng thái Đóng/Mở Modal 
    window.openModal = function(action, proData = null) {
        if (!modal) return;
        modal.classList.remove('hidden');
        const discountSelect = document.getElementById('proDiscountId');
        if (action === 'add') {
            modalTitle.innerText = "Thêm sản phẩm mới";
            form.action = WEB_ROOT + "/admin/ProductController/create";
            form.reset();
            if (imageInput) imageInput.value = "";
            if (imagePreview) imagePreview.src = "";
            if (previewContainer) previewContainer.classList.add('hidden');
            if (discountSelect) discountSelect.value = "";
            setTimeout(() => {
                initializeEditor().then(editor => {
                    if (editor) editor.setData('');
                });
            }, 120);
        } else if (action === 'edit' && proData) {
            modalTitle.innerText = "Chỉnh sửa sản phẩm";
            form.action = WEB_ROOT + "/admin/ProductController/update";
            proIdInput.value = proData.MaSanPham || '';
            proNameInput.value = proData.TenSanPham || '';
            proPriceInput.value = proData.Gia || '';
            proStockInput.value = proData.SoLuong || '';
            proDescInput.value = proData.MoTa || '';
            
            
            const catSelect = document.getElementById('proCategoryId');
            if (catSelect) catSelect.value = proData.MaDanhMuc || '';
            if (discountSelect) discountSelect.value = proData.MaGiamGia || '';
            
            proStatusInput.value = proData.TrangThai || 'Còn hàng';
            if (imageInput) imageInput.value = "";

            if (proData.HinhAnh) {
                imagePreview.src = proData.HinhAnh;
                previewContainer.classList.remove('hidden');
            } else {
                imagePreview.src = "";
                previewContainer.classList.add('hidden');
            }
            setTimeout(() => {
                initializeEditor().then(editor => {
                    if (editor) editor.setData(proData.MoTa);
                });
            }, 120);

        }
    };
    if (form) {
        form.addEventListener('submit', function(e) {
            if (editorInstance) {
                document.querySelector('#proDesc').value = editorInstance.getData();
            }
        });
    }
    window.closeModal = function() {
        if (!modal) return;
        modal.classList.add('hidden');
        destroyEditor(); 
    };

    // Lắng nghe sự kiện thay đổi file để preview ảnh lập tức
    if (imageInput) {
        imageInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.src = e.target.result;
                    previewContainer.classList.remove('hidden');
                }
                reader.readAsDataURL(file);
            }
        });
    }

    // AJAX tải danh sách sản phẩm
    window.fetchData = function(page) {
        const keyword = searchInput ? searchInput.value : '';
        fetch(`${WEB_ROOT}/admin/ProductController/searchAjax?q=${encodeURIComponent(keyword)}&page=${page}`)
            .then(res => res.json())
            .then(res => {
                renderTable(res.data);
                renderPagination(res.totalPages, res.currentPage);
            })
            .catch(err => console.error("Lỗi tải dữ liệu dữ án:", err));
    };
    window.handleExcelUpload = function(event) {
        const input = event.target;
        const file = input.files[0];
        if (!file) return;
        const fileType = file.name.split('.').pop().toLowerCase();
        if (fileType !== 'xlsx' && fileType !== 'xls') {
            Swal.fire({
                title: 'Sai định dạng!',
                text: 'Vui lòng chọn file Excel có đuôi .xlsx hoặc .xls',
                icon: 'warning',
                confirmButtonColor: '#2563eb',
                customClass: { popup: 'rounded-4 shadow-lg border border-light-subtle' }
            });
            input.value = '';
            return;
        }
        const formData = new FormData();
        formData.append('excelFile', file);
        // Hiện thông báo đang xử lý (tùy chọn cho UX tốt hơn)
        Swal.fire({
            title: 'Đang xử lý...',
            text: 'Vui lòng chờ trong giây lát',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        fetch(`${WEB_ROOT}/admin/ProductController/importExcel`, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    title: 'Thành công!',
                    text: data.message || 'Dữ liệu đã được nhập thành công!',
                    icon: 'success',
                    confirmButtonColor: '#2563eb',
                    customClass: { popup: 'rounded-4 shadow-lg border border-light-subtle' }
                });
                fetchData(1);
            } else {
                Swal.fire({
                    title: 'Thất bại!',
                    text: data.message || 'Có lỗi xảy ra khi nhập dữ liệu từ Excel.',
                    icon: 'error',
                    confirmButtonColor: '#ef4444',
                    customClass: { popup: 'rounded-4 shadow-lg border border-light-subtle' }
                });
            }
        })
        .catch(error => {
            console.error('Lỗi khi tải file Excel:', error);
            Swal.fire({
                title: 'Lỗi hệ thống!',
                text: 'Mạng chậm hoặc máy chủ gặp sự cố khi xử lý file Excel.',
                icon: 'error',
                confirmButtonColor: '#ef4444',
                customClass: { popup: 'rounded-4 shadow-lg border border-light-subtle' }
            });
        })
        .finally(() => {            
            input.value = ''; // Luôn dọn dẹp input sau khi xong
        });
    }
    // Render cấu trúc hàng trong Bảng (Đồng bộ class với file index.php màu Blue)
    function renderTable(data) {
        if (!tableBody) return;
        if (data.length === 0) {
            tableBody.innerHTML = `<tr><td colspan="6" class="text-center py-5 text-muted" style="font-size: 0.9rem;">Chưa có sản phẩm nào.</td></tr>`;
            return;
        }
        
        tableBody.innerHTML = data.map(pro => {
            const proJson = JSON.stringify({
                MaSanPham: pro.masanpham,
                TenSanPham: pro.tensanpham,
                MaDanhMuc: pro.madanhmuc,
                Gia: pro.gia,
                SoLuong: pro.tonkho,
                TrangThai: pro.trangthai,
                MoTa: pro.mota,
                HinhAnh: pro.hinhanh,
                MaGiamGia: pro.magiamgia || ''
            }).replace(/"/g, '&quot;');

            // Bản đồ dải màu Badge đồng bộ Xanh Dương Luxury
            const statusClasses = {
                'Còn hàng': 'bg-success-subtle text-success',
                'Bán chạy': 'bg-primary-subtle text-primary',
                'Mới': 'bg-info-subtle text-info',
                'Cao cấp': 'bg-warning-subtle text-warning-emphasis',
                'Giảm giá': 'bg-danger-subtle text-danger'
            };
            const colorClass = statusClasses[pro.trangthai] || 'bg-light text-secondary';
            const statusBadge = `<span class="badge ${colorClass} px-2-5 py-1-5 fw-medium" style="font-size: 0.75rem; border-radius: 6px;">${pro.trangthai}</span>`;
            
            return `
                <tr>
                    <td class="ps-4 fw-semibold text-dark align-middle">
                        <div class="text-truncate-custom" style="max-width: 220px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="${pro.tensanpham}">
                            ${pro.tensanpham}
                        </div>
                    </td>
                    <td class="text-muted align-middle">${pro.tendanhmuc}</td>
                    <td class="fw-bold align-middle" style="color: #2563eb !important;">${new Intl.NumberFormat('vi-VN').format(pro.gia)}đ</td>
                    <td class="fw-semibold text-dark align-middle">${pro.tonkho}</td>
                    <td class="text-center align-middle">${statusBadge}</td>
                    <td class="text-center pe-4 align-middle">
                        <div class="d-flex justify-content-center align-items-center gap-2">
                            <button onclick='openModal("edit", ${proJson})' class="btn btn-action-privia btn-edit-privia">Sửa</button>
                            ${window.CURRENT_USER_ROLE !== 'staff' ? `<button onclick="deleteProduct('${pro.masanpham}', this)" class="btn btn-action-privia btn-delete-privia">Xóa</button>` : ''}
                        </div>
                    </td>
                </tr>
            `;
        }).join('');
    }

    // Thanh điều hướng phân trang cao cấp
    function renderPagination(totalPages, currentPage) {
        if (!paginationBlock) return;
        let html = '';
        
        if (currentPage > 1) {
            html += `<button onclick="fetchData(${currentPage - 1})" class="btn btn-light btn-sm mx-1 rounded-2 border border-light-subtle px-2-5 py-1-5">←</button>`;
        }
        
        for (let i = 1; i <= totalPages; i++) {
            const activeClass = (i == currentPage) 
                ? 'bg-blue-600 text-white border-blue-600 fw-bold' 
                : 'btn-light text-secondary border-light-subtle';
            html += `<button onclick="fetchData(${i})" class="btn btn-sm mx-1 rounded-2 px-3 py-1-5 transition-all ${activeClass}" style="${i == currentPage ? 'background-color:#2563eb !important; border-color:#2563eb !important;' : ''}">${i}</button>`;
        }
        
        if (currentPage < totalPages) {
            html += `<button onclick="fetchData(${currentPage + 1})" class="btn btn-light btn-sm mx-1 rounded-2 border border-light-subtle px-2-5 py-1-5">→</button>`;
        }
        
        paginationBlock.innerHTML = html;
    }

    // Tìm kiếm thông minh với cơ chế Debounce tăng tốc
    if (searchInput) {
        searchInput.addEventListener('input', () => {
            clearTimeout(timeout);
            timeout = setTimeout(() => fetchData(1), 400);
        });
    }
});