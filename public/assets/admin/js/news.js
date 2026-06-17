document.addEventListener("DOMContentLoaded", function() {
    const modal = document.getElementById('newsModal');
    const modalTitle = document.getElementById('modalTitle');
    const form = document.getElementById('newsForm');
    const newsIdInput = document.getElementById('newsId');
    const newsTitleInput = document.getElementById('newsTitle');
    const searchInput = document.getElementById('searchNews');
    const tableBody = document.getElementById('newsTableBody');
    const paginationBlock = document.getElementById('paginationBlock');
    const editorElement = document.querySelector('#newsContent');

    let timeout = null;
    let editorInstance = null; // Biến lưu trữ đối tượng CKEditor

    function initializeEditor() {
        if (editorInstance) {
            return Promise.resolve(editorInstance);
        }
        if (!editorElement) {
            console.error('Không tìm thấy textarea #newsContent để khởi tạo CKEditor.');
            return Promise.reject(new Error('newsContent not found'));
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

    // Hàm XÓA TIN TỨC với SweetAlert2 
    window.deleteNews = function(id, btn) {
        Swal.fire({
            title: 'Xóa bài viết?',
            text: "Hành động này không thể hoàn tác!",
            icon: 'warning',
            showCancelButton: true,
            heightAuto: false,
            confirmButtonColor: '#db2777', 
            cancelButtonColor: '#ef4444', 
            confirmButtonText: 'Xóa',
            cancelButtonText: 'Hủy'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = WEB_ROOT + "/admin/NewsController/deleteNews?id=" + encodeURIComponent(id);
            }
        });
    };

    // 3. Hàm Modal điều khiển đóng mở
    window.openModal = function(action, newsData = null) {
        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            modal.querySelector('.custom-modal-box').classList.remove('scale-95');
        }, 10);

        if (action === 'add') {
            modalTitle.innerText = "Thêm bài viết mới";
            form.action = WEB_ROOT + "/admin/NewsController/create";
            if (newsIdInput) newsIdInput.value = ''; 
            newsTitleInput.value = ''; 
            
            setTimeout(() => {
                initializeEditor().then(editor => {
                    if (editor) editor.setData('');
                });
            }, 120);

        } else if (action === 'edit') {
            modalTitle.innerText = "Chỉnh sửa bài viết";
            form.action = WEB_ROOT + "/admin/NewsController/update";
            if (newsIdInput) newsIdInput.value = newsData.MaTinTuc;
            newsTitleInput.value = newsData.Tieude;
            
            setTimeout(() => {
                initializeEditor().then(editor => {
                    if (editor) editor.setData(newsData.NoiDung);
                });
            }, 120);
        }
    };
       // Đẩy dữ liệu từ CKEditor vào textarea trước khi submit form
    if (form) {
        form.addEventListener('submit', function(e) {
            if (editorInstance) {
                document.querySelector('#newsContent').value = editorInstance.getData();
            }
        });
    }
    window.closeModal = function() {
        modal.classList.add('opacity-0');
        modal.querySelector('.custom-modal-box').classList.add('scale-95');
        setTimeout(() => {
            modal.classList.add('hidden');
            destroyEditor();
        }, 300);
    };

 

    // Hàm gọi dữ liệu AJAX từ NewsController
    window.fetchData = function(page) {
        const keyword = searchInput ? searchInput.value : '';
        
        fetch(`${WEB_ROOT}/admin/NewsController/searchAjax?q=${encodeURIComponent(keyword)}&page=${page}`)
            .then(res => res.json())
            .then(res => {
                renderTable(res.data);
                renderPagination(res.totalPages, res.currentPage);
            })
            .catch(err => console.error("Lỗi tải dữ liệu tin tức:", err));
    };

    // Render danh sách bài viết vào Table body (Chuẩn cấu trúc Bootstrap 5)
    function renderTable(data) {
        if (data.length === 0) {
            tableBody.innerHTML = `<tr><td colspan="4" class="text-center py-5 text-muted" style="font-size: 0.9rem;">Không tìm thấy bài viết nào phù hợp.</td></tr>`;
            return;
        }
        
        tableBody.innerHTML = data.map(news => {
            const newsJson = JSON.stringify({  
                MaTinTuc: news.matintuc,
                Tieude: news.tieude,
                NoiDung: news.noidung
            }).replace(/"/g, '&quot;'); 

            // Loại bỏ các thẻ HTML để hiển thị văn bản thuần trên table
            const textPure = news.noidung ? news.noidung.replace(/<[^>]*>/g, '') : 'Chưa có nội dung';

            return `
                <tr data-id="${news.matintuc}">
                    <td class="ps-4 fw-medium text-secondary" style="width: 100px;">#${news.matintuc}</td>
                    <td class="fw-semibold text-dark truncate-col" style="color: #111827 !important; max-width: 250px;">${news.tieude}</td>
                    <td style="max-width: 380px;">
                    <div class="truncate-text text-muted" style="overflow: hidden; position: relative; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient: vertical;">
                        ${textPure}
                    </div>
                    </td>
                    <td class="text-center pe-4" style="width: 140px;">
                        <div class="d-flex justify-content-center align-items-center gap-2">
                            <button onclick='openModal("edit", ${newsJson})' 
                                    class="btn-action-privia btn-edit-privia">
                                Sửa
                            </button>
                            <button onclick="deleteNews('${news.matintuc}', this)" 
                                    class="btn-action-privia btn-delete-privia">
                                Xóa
                            </button>
                        </div>
                    </td>
                </tr>
            `;
        }).join('');
    }
    
    // Render Thanh Phân Trang AJAX (Đồng bộ mã màu Hồng đậm #db2777)
    function renderPagination(totalPages, currentPage) {
        if (!paginationBlock) return;
        let html = '';

        if (currentPage > 1) {
            html += `<button onclick="fetchData(${currentPage - 1})" class="page-btn-privia">&larr;</button>`;
        }

        for (let i = 1; i <= totalPages; i++) {
            const isCurrent = i == currentPage;
            const activeStyle = isCurrent ? 'background-color: #db2777; border-color: #db2777; color: #fff;' : '';
            
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
            timeout = setTimeout(() => { fetchData(1); }, 500);
        });
    }
});