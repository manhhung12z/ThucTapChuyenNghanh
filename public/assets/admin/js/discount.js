document.addEventListener("DOMContentLoaded", function() {
    const modal = document.getElementById('discountModal');
    const modalTitle = document.getElementById('modalTitle');
    const form = document.getElementById('discountForm');
    const disIdInput = document.getElementById('disId');         
    const disNameInput = document.getElementById('disName');     
    const disPercentInput = document.getElementById('disPercent'); 
    const disStartDateInput = document.getElementById('disStartDate'); 
    const disEndDateInput = document.getElementById('disEndDate');     
    const searchInput = document.getElementById('searchDiscount');
    const tableBody = document.getElementById('discountTableBody');
    const paginationBlock = document.getElementById('paginationBlock');
    
    let timeout = null;

    window.deleteDiscount = function(id, btn) {
        Swal.fire({
            title: 'Xóa giảm giá?',
            text: `Hành động này không thể hoàn tác!`,
            icon: 'warning',
            showCancelButton: true,
            heightAuto: false,
            confirmButtonColor: '#d97706', 
            cancelButtonColor: '#ef4444', 
            confirmButtonText: 'Xóa',
            cancelButtonText: 'Hủy'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = WEB_ROOT + "/admin/DiscountController/delete?id=" + encodeURIComponent(id);
            }
        });
    };

    window.openModal = function(action, disData = null) {
        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            modal.querySelector('.custom-modal-box').classList.remove('scale-95');
        }, 10);
        const tzoffset = (new Date()).getTimezoneOffset() * 60000; 
        const nowStr = (new Date(Date.now() - tzoffset)).toISOString().slice(0, 16);
        if (action === 'add') {
            modalTitle.innerText = "Thêm giảm giá mới";
            form.action = WEB_ROOT + "/admin/DiscountController/create";
            form.reset();
            disStartDateInput.setAttribute('min', nowStr);
            disEndDateInput.setAttribute('min', nowStr);
            // Chế độ thêm: Cho phép tự do nhập mã giảm giá mới
            disIdInput.removeAttribute('readonly');
            disIdInput.classList.remove('bg-light', 'text-muted', 'cursor-not-allowed');
        } else if (action === 'edit' && disData) {
            modalTitle.innerText = "Chỉnh sửa giảm giá";
            form.action = WEB_ROOT + "/admin/DiscountController/update";
            
            // Đổ dữ liệu vào các trường Input của form
            disNameInput.value = disData.TenGiamGia;
            disPercentInput.value = disData.PhanTram;

            if (disData.NgayBatDau) {
                const startFormatted = disData.NgayBatDau.replace(' ', 'T').substring(0, 16);
                disStartDateInput.value = startFormatted;
                
                // Ngày kết thúc tối thiểu phải bằng Ngày bắt đầu
                disEndDateInput.setAttribute('min', startFormatted);

                // Không lùi về quá khứ 
                if (startFormatted > nowStr) {
                    disStartDateInput.setAttribute('min', nowStr);
                } else {
                    // Nếu voucher chạy Xóa thuộc tính min để tránh lỗi Form khi giữ nguyên ngày cũ
                    disStartDateInput.removeAttribute('min');
                }
            }
            
            if (disData.NgayKetThuc) {
                disEndDateInput.value = disData.NgayKetThuc.replace(' ', 'T').substring(0, 16);
            }
            
            disIdInput.setAttribute('readonly', true);
            disIdInput.classList.add('bg-light', 'text-muted', 'cursor-not-allowed');
        }
    };
    disStartDateInput.addEventListener('change', function() {
        const valBatDau = this.value;
        disEndDateInput.setAttribute('min', valBatDau);
        if (disEndDateInput.value && disEndDateInput.value < valBatDau) {
            disEndDateInput.value = '';
            alert('Ngày kết thúc không thể nhỏ hơn ngày bắt đầu mới chọn. Vui lòng chọn lại ngày kết thúc!');
        }
});

    window.closeModal = function() {
        modal.classList.add('opacity-0');
        modal.querySelector('.custom-modal-box').classList.add('scale-95');
        setTimeout(() => modal.classList.add('hidden'), 300);
    };

    // Hàm gọi dữ liệu thông qua AJAX
    window.fetchData = function(page) {
        const keyword = searchInput ? searchInput.value : '';
        
        fetch(`${WEB_ROOT}/admin/DiscountController/searchAjax?q=${encodeURIComponent(keyword)}&page=${page}`)
            .then(res => res.json())
            .then(res => {
                renderTable(res.data);
                renderPagination(res.totalPages, res.currentPage);
            })
            .catch(err => console.error("Lỗi tải dữ liệu giảm giá:", err));
    };

    function formatDateTime(dateTimeStr) {
        const $ngayBatDau = $('#ngay_bat_dau');
        const $ngayKetThuc = $('#ngay_ket_thuc');
        const hoMNay = new Date().toISOString().slice(0, 16); // Lấy định dạng YYYY-MM-DDTHH:mm
        $ngayBatDau.attr('min', hoMNay);

        $ngayBatDau.on('change', function() {
            const valBatDau = $(this).val();
            
            // Ép ô "Ngày kết thúc" chỉ được chọn từ mốc "Ngày bắt đầu" trở đi
            $ngayKetThuc.attr('min', valBatDau);
            
            // Nếu ngày kết thúc hiện tại đang bị nhỏ hơn ngày bắt đầu mới chọn -> Xóa và bắt chọn lại
            if ($ngayKetThuc.val() && $ngayKetThuc.val() < valBatDau) {
                $ngayKetThuc.val('');
                alert('Ngày kết thúc không thể nhỏ hơn ngày bắt đầu. Vui lòng chọn lại!');
            }
        });
        if (!dateTimeStr) return '---';
        const t = dateTimeStr.split(/[- :]/);
        return `${t[2]}/${t[1]}/${t[0]} ${t[3]}:${t[4]}`;
    }

    function renderTable(data) {
        if (data.length === 0) {
            tableBody.innerHTML = `<tr><td colspan="5" class="text-center py-5 text-muted" style="font-size: 0.9rem;">Không tìm thấy mã giảm giá nào.</td></tr>`;
            return;
        }
        
        tableBody.innerHTML = data.map(dis => {
            const disJson = JSON.stringify({
                MaGiamGia: dis.magiamgia,
                TenGiamGia: dis.tengiamgia,
                PhanTram: dis.phantram,
                NgayBatDau: dis.ngaybatdau,
                NgayKetThuc: dis.ngaykethuc
            }).replace(/"/g, '&quot;');

            return `
                <tr data-id="${dis.magiamgia}">
                    <td class="ps-4 fw-semibold text-dark truncate-col" style="color: #111827 !important;">${dis.tengiamgia}</td>
                    <td class="fw-bold" style="color: #d97706;">${dis.phantram}%</td>
                    <td class="text-secondary fw-medium">${dis.tongsanpham || 0}</td>
                    <td class="text-center text-secondary small" style="font-size: 0.8rem; line-height: 1.5;">
                        <div><span class="text-success fw-semibold">Từ:</span> ${formatDateTime(dis.ngaybatdau)}</div>
                        <div><span class="text-danger fw-semibold">Đến:</span> ${formatDateTime(dis.ngaykethuc)}</div>
                    </td>
                    
                    <td class="text-center pe-4">
                        <div class="d-flex justify-content-center align-items-center gap-2">
                            <button onclick='openModal("edit", ${disJson})' 
                                    class="btn-action-privia btn-edit-privia">
                                Sửa
                            </button>
                            <button onclick="deleteDiscount('${dis.magiamgia}', this)" 
                                    class="btn-action-privia btn-delete-privia">
                                Xóa
                            </button>
                        </div>
                    </td>
                </tr>
            `;
        }).join('');
    }
        
    function renderPagination(totalPages, currentPage) {
        if (!paginationBlock) return;
        let html = '';

        if (currentPage > 1) {
            html += `<button onclick="fetchData(${currentPage - 1})" class="page-btn-privia">&larr;</button>`;
        }

        for (let i = 1; i <= totalPages; i++) {
            const isCurrent = i == currentPage;
            const activeStyle = isCurrent ? 'background-color: #d97706; border-color: #d97706; color: #fff; font-weight: 600;' : '';
            
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

    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(timeout);
            timeout = setTimeout(() => {
                fetchData(1);
            }, 500);
        });
    }
});