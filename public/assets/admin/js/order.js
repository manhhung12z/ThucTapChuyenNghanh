document.addEventListener("DOMContentLoaded", function () {
    const orderTableBody = document.getElementById("orderTableBody");
    const searchInput = document.getElementById("searchOrder");
    const paginationBlock = document.getElementById("paginationBlock");
    const orderModal = document.getElementById("orderModal");
    const modalTitle = document.getElementById("modalTitle");
    const modalMaDonHang = document.getElementById("modalMaDonHang");
    const modalKhachHang = document.getElementById("modalKhachHang");
    const modalTrangThai = document.getElementById("modalTrangThai");
    const modalTrangThaiGoc = document.getElementById("modalTrangThaiGoc");
    const optChoXacNhan = document.getElementById('optChoXacNhan');
    const optThanhCong = document.getElementById('optThanhCong');
    const optDaHuy = document.getElementById('optDaHuy');

    let searchTimeout = null;

    // 1. Sự kiện click trên bảng đơn hàng để mở Modal
    if (orderTableBody) {
        orderTableBody.addEventListener("click", function (e) {
            const editBtn = e.target.closest(".btn-edit-order");
            if (editBtn) {
                const orderData = JSON.parse(editBtn.getAttribute("data-order"));
                openOrderModal(orderData);
            }
        });
    }
if (modalTrangThai) {
    modalTrangThai.addEventListener('change', function() {
        const btnSubmit = document.querySelector('#btnSubmit');
        if (!btnSubmit) return;
        if (this.value === 'Đang giao hàng') {
            btnSubmit.style.display = 'block';
        } else {
            btnSubmit.style.display = 'none';
        }
    });
}
    // 2. Hàm mở modal
    window.openOrderModal = function(order) {
    if (!orderModal) return;
        const btnSubmit = document.querySelector('#btnSubmit');
        modalMaDonHang.value = order.MaDonHang || '';
        modalKhachHang.value = order.TenNguoiDung || '';
        if (btnSubmit) btnSubmit.style.display = 'none';
        if (order.TrangThai == 'Đang chờ duyệt') {
            modalTrangThai.disabled = false; 
            modalTrangThai.value = 'Đang chờ duyệt';
            if (optChoXacNhan) optChoXacNhan.style.display = 'block';
            if (optThanhCong) optThanhCong.style.display = 'none';
            if (optDaHuy) optDaHuy.style.display = 'none';
        } else {
            modalTrangThai.disabled = true; // Khóa luôn select nếu là trạng thái khác
            modalTrangThai.value = order.TrangThai || '';
            if (order.TrangThai == 'Giao hàng thành công' && optThanhCong) optThanhCong.style.display = 'block';
            if (order.TrangThai == 'Đã hủy' && optDaHuy) optDaHuy.style.display = 'block';
            btnSubmit.style.display = 'none'; 
        }
        
        orderModal.classList.remove('hidden');
    };

    // 3. Hàm đóng modal
    window.closeOrderModal = function() {
        if (!orderModal) return;
        
        orderModal.classList.add('hidden');
    };

    // 4. Gọi AJAX tìm kiếm đơn hàng
    window.fetchOrders = function(page = 1) {
        const keyword = searchInput ? searchInput.value.trim() : '';

        fetch(`${WEB_ROOT}/admin/OrderController/searchAjax?q=${encodeURIComponent(keyword)}&page=${page}`)
            .then(response => response.json())
            .then(res => {
                renderTable(res.data);
                renderPagination(res.totalPages, res.currentPage);
            })
            .catch(err => console.error("Lỗi AJAX đơn hàng:", err));
    };

    // 5. Render bảng đơn hàng (Chuẩn hóa hiển thị màu sắc và chuỗi dữ liệu JSON)
    function renderTable(data) {
        if (!orderTableBody) return;

        if (!data || data.length === 0) {
            orderTableBody.innerHTML = `<tr><td colspan="6" class="text-center py-5 text-secondary" style="font-size: 0.9rem;">Hệ thống chưa ghi nhận đơn hàng phù hợp.</td></tr>`;
            return;
        }

        orderTableBody.innerHTML = data.map(order => {
            const date = new Date(order.ngaydat);
            const pad = (n) => n.toString().padStart(2, '0');
            const formattedDate = `${pad(date.getDate())}/${pad(date.getMonth() + 1)}/${date.getFullYear()} ${pad(date.getHours())}:${pad(date.getMinutes())}`;

            // Định dạng tiền tệ
            const formattedTotal = new Intl.NumberFormat('vi-VN').format(order.tongtien);

            // Xử lý Class màu sắc trạng thái theo Bootstrap 5
            let statusClass = "";
            switch (order.trangthai) {
                case 'Giao hàng thành công':
                    statusClass = "bg-success-subtle text-success border-success-subtle";
                    break;
                case 'Đang giao hàng':
                    statusClass = "bg-primary-subtle text-primary border-primary-subtle";
                    break;
                case 'Đang chờ duyệt':
                    statusClass = "bg-warning-subtle text-warning border-warning-subtle";
                    break;
                case 'Đã hủy':
                    statusClass = "bg-danger-subtle text-danger border-danger-subtle";
                    break;
                default:
                    statusClass = "bg-light text-secondary border-light-subtle";
                    break;
            }

            const orderJson = JSON.stringify({
                MaDonHang: order.madonhang,
                TenNguoiDung: order.tennguoidung ? order.tennguoidung : 'Mã: ' + order.manguoidung,
                TrangThai: order.trangthai
            }).replace(/"/g, '&quot;');

            return `
                <tr data-id="${order.madonhang}">
                    <td class="ps-4">
                        <span class="font-monospace bg-light border border-light-subtle px-2 py-1 rounded text-secondary small">
                            ${order.madonhang}
                        </span>
                    </td>
                    <td class="fw-semibold text-dark truncate-customer" style="color: #111827 !important;">
                        ${order.tennguoidung ? order.tennguoidung : 'Mã: ' + order.manguoidung}
                    </td>
                    <td class="text-secondary">
                        ${formattedDate}
                    </td>
                    <td class="fw-bold text-primary">
                        ${formattedTotal}đ
                    </td>
                    <td class="text-center">
                        <span class="d-inline-flex align-items-center justify-content-center text-nowrap px-3 py-1 rounded-pill border shadow-sm user-select-none fw-semibold ${statusClass}" style="min-width: 110px; font-size: 0.725rem;">
                            ${order.trangthai}
                        </span>
                    </td>
                    <td class="text-center pe-4">
                        <div class="d-flex justify-content-center align-items-center">
                            <button data-order="${orderJson}" class="btn-edit-order btn btn-action-privia btn-process-privia d-inline-flex align-items-center gap-1">
                                ⚙️ Xử lý
                            </button>
                        </div>
                    </td>
                </tr>`;
        }).join('');
    }

function renderPagination(totalPages, currentPage) {
    if (!paginationBlock) return;
    let html = '';

    if (currentPage > 1) {
        html += `<a href="?page=${currentPage - 1}" onclick="event.preventDefault(); fetchOrders(${currentPage - 1});" class="page-btn-privia">&larr;</a>`;
    }

    for (let i = 1; i <= totalPages; i++) {
        const activeStyle = i == currentPage 
            ? 'background-color: #f43f5e; border-color: #f43f5e; color: #fff;' 
            : '';
            
        html += `<a href="?page=${i}" onclick="event.preventDefault(); fetchOrders(${i});" class="page-btn-privia" style="${activeStyle}">${i}</a>`;
    }
    if (currentPage < totalPages) {
        html += `<a href="?page=${currentPage + 1}" onclick="event.preventDefault(); fetchOrders(${currentPage + 1});" class="page-btn-privia">&rarr;</a>`;
    }

    paginationBlock.innerHTML = html;
}

    if (searchInput) {
        searchInput.addEventListener("input", function () {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                fetchOrders(1);
            }, 500);
        });
    }
});