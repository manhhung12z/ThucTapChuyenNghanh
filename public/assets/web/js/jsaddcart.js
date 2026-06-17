// thêm sản phẩm vào giỏ hàng
document.querySelectorAll('.add-cart').forEach(button => {
    button.addEventListener('click', function () {
        let product = document.querySelector('.product-container');
        let img = document.querySelector('.main-img');
        let cart = document.querySelector('.cart-icon');
        let clone = img.cloneNode(true);
        clone.classList.add('fly-img');
        document.body.appendChild(clone);
        let rect = img.getBoundingClientRect();
        clone.style.left = rect.left + 'px';
        clone.style.top = rect.top + 'px';

        clone.style.width = '70px';
        clone.style.height = '70px';
        clone.style.opacity = '1';
        clone.style.transition = 'none'; // tắt transition để set vị trí ban đầu
        //ban đầu gọi thẻ ảnh chứa ảnh đó khi click vào, sau đó lấy thuộc tính giỏ hàng, sử dụng clonenode để copy ảnh ảnh đó vào clone. thêm clone vào class fly img để css thêm vào cuối body
        //getboundingClientReact để lấy vị trí ảnh đó để biết clone ở vị trí nào top và left .
        requestAnimationFrame(() => {
            requestAnimationFrame(() => {
                clone.style.transition = 'all 0.7s cubic-bezier(0.25, 1, 0.5, 1)';
                let cartRect = cart.getBoundingClientRect();
                clone.style.left = (cartRect.left + cartRect.width / 2 - 10) + 'px';
                clone.style.top = (cartRect.top + cartRect.height / 2 - 10) + 'px';
                clone.style.width = '10px';
                clone.style.height = '10px';
                clone.style.opacity = '0.5';
            });
        });


        setTimeout(() => clone.remove(), 800);
        setTimeout(() => {
            cart.classList.add('shake');
            setTimeout(() => cart.classList.remove('shake'), 500);
        }, 600);
        // Submit form to CartController after short delay so animation can run
        // Gửi AJAX đến CartController/add (không reload trang)
        const form = this.closest('form');
        if (form) {
            const formData = new FormData(form);
            // Thay thế 'OrderController/prepare' thành 'CartController/add'
            const cartUrl = form.action.replace('OrderController/prepare', 'CartController/add');
            fetch(cartUrl, {
                method: 'POST',
                body: formData
            })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        // Cập nhật số trên icon giỏ hàng bằng dữ liệu thật từ server
                        document.querySelector('.cart-count').innerText = data.totalItems;
                    } else if (data.redirect) {
                        // Chưa đăng nhập → chuyển đến trang login
                        alert(data.message);
                        window.location.href = data.redirect;
                    }
                })
                .catch(err => console.error('Lỗi:', err));
        }

    })
});
