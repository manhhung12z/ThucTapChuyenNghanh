document.addEventListener("DOMContentLoaded", function() {
    const modal = document.getElementById('reviewModal');
    const modalTitle = document.getElementById('modalTitle');
    const form = document.getElementById('reviewForm');
    const revIdInput = document.getElementById('revId');
    const revStarsInput = document.getElementById('revStars');
    const revContentInput = document.getElementById('revContent');
    const searchInput = document.getElementById('searchReview');
    const tableBody = document.getElementById('reviewTableBody');
    const paginationBlock = document.getElementById('paginationBlock');
    
    let timeout = null;

    // Hàm xử lý Xóa đánh giá bằng SweetAlert2
    window.deleteReview = function(id, btn) {
        Swal.fire({
            title: 'Xóa đánh giá?',
            text: "Hành động này không thể hoàn tác!",
            icon: 'warning',
            showCancelButton: true,
            heightAuto: false,
            confirmButtonColor: '#0ea5e9', // Đổi màu nút xác nhận thành màu Sky chuẩn hệ thống
            cancelButtonColor: '#ef4444', 
            confirmButtonText: 'Xóa',
            cancelButtonText: 'Hủy',
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = WEB_ROOT + "/admin/ReviewController/deletereview?id=" + encodeURIComponent(id);
            }
        });
    };

    // Hàm gọi dữ liệu AJAX 
    window.fetchData = function(page) {
        const keyword = searchInput ? searchInput.value : '';
        
        fetch(`${WEB_ROOT}/admin/ReviewController/searchAjax?q=${encodeURIComponent(keyword)}&page=${page}`)
            .then(res => res.json())
            .then(res => {
                renderTable(res.data);
                renderPagination(res.totalPages, res.currentPage);
            })
            .catch(err => console.error("Lỗi tải dữ liệu đánh giá:", err));
    };

    // Render cấu trúc Bảng đánh giá bằng AJAX (Chuẩn Bootstrap 5 & Tone Sky)
    function renderTable(data) {
        if (data.length === 0) {
            tableBody.innerHTML = `<tr><td colspan="5" class="text-center py-5 text-muted" style="font-size: 0.9rem;">Không tìm thấy đánh giá nào thỏa mãn.</td></tr>`;
            return;
        }
        
        tableBody.innerHTML = data.map(rev => {
            const revJson = JSON.stringify({
                MaDanhGia: rev.madanhgia,
                SoSao: rev.sosao,
                NoiDung: rev.noidung
            }).replace(/"/g, '&quot;');

            // Tạo chuỗi sao hiển thị động màu vàng cam rực rỡ
            let starHtml = '';
            const stars = parseInt(rev.sosao || 5);
            for (let i = 1; i <= 5; i++) {
                starHtml += i <= stars ? '★' : '☆';
            }

            return `
                <tr data-id="${rev.madanhgia}">
                    <td class="ps-4 fw-semibold text-dark" style="color: #111827 !important;">${rev.tennguoidung}</td>
                    <td class="text-secondary fw-medium">${rev.tensanpham}</td>
                    <td class="text-center whitespace-nowrap">
                        <div class="d-flex align-items-center justify-content-center text-warning gap-1" style="font-size: 1.05rem;">
                            ${starHtml}
                        </div>
                    </td>
                    <td class="text-muted text-truncate-custom" style="max-width: 260px;">
                        ${rev.noidung || '<span class="text-muted-light italic" style="color: #cbd5e1;">Không có nội dung</span>'}
                    </td>
                    <td class="text-center pe-4">
                        <div class="d-flex justify-content-center align-items-center">
                            <button onclick="deleteReview('${rev.madanhgia}', this)" 
                                    class="btn-action-privia btn-delete-privia">
                                Xóa
                            </button>
                        </div>
                    </td>
                </tr>
            `;
        }).join('');
    }
    
    // Render khối chuyển trang phân trang AJAX với mã màu Sky (#0ea5e9)
    function renderPagination(totalPages, currentPage) {
        if (!paginationBlock) return;

        let html = '';

        if (currentPage > 1) {
            html += `<button onclick="fetchData(${currentPage - 1})" class="page-btn-privia">&larr;</button>`;
        }

        for (let i = 1; i <= totalPages; i++) {
            const isCurrent = i == currentPage;
            // Áp dụng chuẩn mã màu xanh da trời Sky cho nút active
            const activeStyle = isCurrent ? 'background-color: #0ea5e9; border-color: #0ea5e9; color: #fff;' : '';
            
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

    // Sự kiện lắng nghe ô tìm kiếm (Debounce 500ms chống spam request)
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(timeout);
            timeout = setTimeout(() => {
                fetchData(1);
            }, 500);
        });
    }
});