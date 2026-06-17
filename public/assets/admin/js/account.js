document.addEventListener("DOMContentLoaded", function() {
    // 1. Khai báo các biến DOM mẫu tài khoản
    const modal = document.getElementById('accountModal'); 
    const modalTitle = document.getElementById('modalTitle');
    const form = document.getElementById('accountForm');
    const accIdInput = document.getElementById('accId');
    const accNameInput = document.getElementById('accName');
    const accEmailInput = document.getElementById('accEmail');
    const accPhoneInput = document.getElementById('accPhone');
    const accRoleInput = document.getElementById('accRole');
    const searchInput = document.getElementById('searchAccount');
    const tableBody = document.getElementById('accountTableBody');
    const paginationBlock = document.getElementById('paginationBlock');
    
    let timeout = null;

    // Hàm xử lý Xóa tài khoản bằng SweetAlert2
    window.deleteAccount = function(id, btn) {
        Swal.fire({
            title: 'Xóa tài khoản này?',
            text: "Hành động này không thể hoàn tác nếu tài khoản chưa có lịch sử mua hàng!",
            icon: 'warning',
            showCancelButton: true,
            heightAuto: false,
            confirmButtonColor: '#059669', // Đổi màu xác nhận sang Xanh Emerald đồng bộ thương hiệu
            cancelButtonColor: '#ef4444', 
            confirmButtonText: 'Xóa',
            cancelButtonText: 'Hủy'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = WEB_ROOT + "/admin/AccountController/deleteAccount?id=" + encodeURIComponent(id);
            }
        });
    };

    // 2. Các hàm xử lý đóng/mở Modal Tài Khoản
    window.openModal = function(action, accData = null) {
        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            modal.querySelector('.custom-modal-box').classList.remove('scale-95');
        }, 10);

        if (action === 'edit') {
            modalTitle.innerText = "Chỉnh sửa tài khoản";
            form.action = WEB_ROOT + "/admin/AccountController/update";
            if(accIdInput) accIdInput.value = accData.MaTaiKhoan;
            accNameInput.value = accData.TenNguoiDung;
            accEmailInput.value = accData.Email;
            if(accPhoneInput) accPhoneInput.value = accData.SoDienThoai || '';
            if (accRoleInput) {
                // Điền quyền của tài khoản cần sửa vào ô input trước
                accRoleInput.value = accData.LoaiTaiKhoan || 'user';
                
                const currentRole = String(window.CURRENT_USER_ROLE || '').toLowerCase();
                const roleWrapper = accRoleInput.closest('.col-12.col-sm-6');
                
                if (accRoleInput) {
                accRoleInput.value = accData.LoaiTaiKhoan || 'user';
                const currentRole = String(window.CURRENT_USER_ROLE || '').trim().toLowerCase();
                const accPhoneInput = document.getElementById('accPhone');
                if (currentRole === 'staff') {                    
                    if (accRoleInput.parentElement) {
                        accRoleInput.parentElement.style.display = 'none'; 
                    }
                    if (accPhoneInput && accPhoneInput.parentElement) {
                        accPhoneInput.parentElement.classList.remove('col-sm-6');
                        accPhoneInput.parentElement.classList.add('col-sm-12');
                    }
                } else {
                    if (accRoleInput.parentElement) {
                        accRoleInput.parentElement.style.display = ''; 
                    }
                    if (accPhoneInput && accPhoneInput.parentElement) {
                        accPhoneInput.parentElement.classList.remove('col-sm-12');
                        accPhoneInput.parentElement.classList.add('col-sm-6');
                    }
                }
}
            }
        }
    };

    window.closeModal = function() {
        modal.classList.add('opacity-0');
        modal.querySelector('.custom-modal-box').classList.add('scale-95');
        setTimeout(() => modal.classList.add('hidden'), 300);
    };

    // 3. Hàm gọi dữ liệu AJAX cho tài khoản
    window.fetchData = function(page) {
        const keyword = searchInput ? searchInput.value : '';
        
        fetch(`${WEB_ROOT}/admin/AccountController/searchAjax?q=${encodeURIComponent(keyword)}&page=${page}`)
            .then(res => res.json())
            .then(res => {
                renderTable(res.data);
                renderPagination(res.totalPages, res.currentPage);
            })
            .catch(err => console.error("Lỗi tải dữ liệu tài khoản:", err));
    };

    // 4. Render cấu trúc Bảng tài khoản Ajax (Đã chuyển sang Bootstrap 5 & Tone Xanh)
    function renderTable(data) {
        if (data.length === 0) {
            tableBody.innerHTML = `<tr><td colspan="5" class="text-center py-5 text-muted" style="font-size: 0.9rem;">Không tìm thấy tài khoản nào phù hợp.</td></tr>`;
            return;
        }
        
        tableBody.innerHTML = data.map(acc => {
            const accJson = JSON.stringify({
                MaTaiKhoan: acc.mataikhoan,
                TenNguoiDung: acc.tennguoidung,
                Email: acc.email,
                SoDienThoai: acc.sodienthoai,
                LoaiTaiKhoan: acc.loaitaikhoan
            }).replace(/"/g, '&quot;');

            // Giao diện Badge quyền hạn bằng Bootstrap 5
            const roleBadge = acc.loaitaikhoan === 'admin' 
                ? `<span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1" style="font-size: 0.75rem;">Admin</span>`
                : acc.loaitaikhoan === 'staff'
                ? `<span class="badge bg-info-subtle text-info-emphasis border border-info-subtle px-2 py-1" style="font-size: 0.75rem;">Staff</span>`
                : `<span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle px-2 py-1" style="font-size: 0.75rem;">User</span>`;
            return `
                <tr data-id="${acc.mataikhoan}">
                    <td class="ps-4 fw-semibold text-dark truncate-col" style="color: #111827 !important;">${acc.tennguoidung}</td>
                    <td class="text-secondary">${acc.email}</td>
                    <td class="text-muted">
                        ${acc.sodienthoai ? acc.sodienthoai : '<span class="text-muted-light italic" style="color: #cbd5e1;">Chưa có</span>'}
                    </td>
                    <td class="text-center">${roleBadge}</td>
                    <td class="text-center pe-4">
                        <div class="d-flex justify-content-center align-items-center gap-2">
                            <button onclick='openModal("edit", ${accJson})' 
                                    class="btn-action-privia btn-edit-privia">
                                Sửa
                            </button>
                            <button onclick="deleteAccount('${acc.mataikhoan}', this)" 
                                    class="btn-action-privia btn-delete-privia">
                                Xóa
                            </button>
                        </div>
                    </td>
                </tr>
            `;
        }).join('');
    }
    
    // 5. Render khối chuyển trang phân trang bằng Ajax (Đã chuyển sang nút đồng bộ)
    function renderPagination(totalPages, currentPage) {
        if (!paginationBlock) return;

        let html = '';

        if (currentPage > 1) {
            html += `<button onclick="fetchData(${currentPage - 1})" class="page-btn-privia">&larr;</button>`;
        }

        for (let i = 1; i <= totalPages; i++) {
            const isCurrent = i == currentPage;
            const activeStyle = isCurrent ? 'background-color: #059669; border-color: #059669; color: #fff;' : '';
            
            html += `
                <button onclick="fetchData(${i})" 
                        class="page-btn-privia" 
                        style="${activeStyle}">
                    ${i}
                </button>`;
        }

        if (currentPage < totalPages) {
            html += `<button onclick="fetchData(${currentPage + 1})" class="page-btn-privia">&rarr;</button>`;
        }

        paginationBlock.innerHTML = html;
    }

    // 6. Sự kiện lắng nghe ô Tìm kiếm (Debounce 500ms)
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(timeout);
            timeout = setTimeout(() => {
                fetchData(1);
            }, 500);
        });
    }
});