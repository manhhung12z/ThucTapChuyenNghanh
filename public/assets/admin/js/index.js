// 1. Khai báo biến ở phạm vi toàn cục (ngoài mọi hàm)
let sidebar, overlay, urlParams;

document.addEventListener("DOMContentLoaded", function() {
    // 2. Gán giá trị cho các biến khi trang đã tải xong
    sidebar = document.getElementById('adminSidebar');
    overlay = document.getElementById('sidebarOverlay');
    urlParams = new URLSearchParams(window.location.search);
    

    handleStatusNotification();
});

function toggleSidebar() {
    // Kiểm tra an toàn trước khi dùng
    if (!sidebar || !overlay) return;

    if (sidebar.classList.contains('-translate-x-full')) {
        // Mở menu
        sidebar.classList.remove('-translate-x-full');
        overlay.classList.remove('hidden');
        setTimeout(() => overlay.classList.add('opacity-100'), 10);
        document.body.classList.add('overflow-hidden');
    } else {
        sidebar.classList.add('-translate-x-full');
        
        overlay.classList.remove('opacity-100'); 
        
        setTimeout(() => overlay.classList.add('hidden'), 300);
        document.body.classList.remove('overflow-hidden');
    }
}

function handleStatusNotification() {
    if (urlParams && urlParams.get('status') === 'success') {
        Toastify({
            text: "Thao tác thành công!",
            duration: 3000,
            gravity: "top",      // Đặt ở trên cùng
            position: "center",  // Đặt ở giữa màn hình
            style: {
                background: "linear-gradient(to right, #22c55e, #16a34a)",
                borderRadius: "8px", // Bo góc cho đẹp hơn
                boxShadow: "0 4px 6px -1px rgba(0, 0, 0, 0.1)" // Thêm bóng đổ cho nổi bật
            }
        }).showToast();
    } else if (urlParams && urlParams.get('status') === 'error') {
        // Thông báo lỗi
        Toastify({
            text: "⚠️ Không thể xóa!",
            duration: 4000,
            gravity: "top",
            position: "center",
            style: { 
                background: "#ffffff", 
                color: "#dc2626", // Màu đỏ
                borderRadius: "12px", 
                border: "1px solid #fee2e2" 
            }
        }).showToast();
    } else if (urlParams && urlParams.get('status') === 'permission_denied') {
        // Thông báo không có quyền
        Toastify({
            text: "🔒 Bạn không có quyền thực hiện thao tác này!",
            duration: 4000,
            gravity: "top",
            position: "center",
            style: { 
                background: "#ffffff", 
                color: "#e11d48", 
                borderRadius: "12px", 
                border: "1px solid #ffe4e6",
                boxShadow: "0 10px 15px -3px rgba(0, 0, 0, 0.1)"
            }
        }).showToast();
    }
    window.history.replaceState({}, document.title, window.location.pathname);
}
