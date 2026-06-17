// 1. Slider trang chủ
const slides = document.querySelectorAll('.slide');
const dots = document.querySelectorAll('.dot');
let currentindex = 0;

if (slides.length > 0) {
    function showslide(index) {
        slides.forEach(slide => slide.classList.remove('active'));
        dots.forEach(dot => dot.classList.remove('active'));
        if (slides[index]) slides[index].classList.add('active');
        if (dots[index]) dots[index].classList.add('active');
    }

    function nextSlide() {
        currentindex++;
        if (currentindex >= slides.length) {
            currentindex = 0;
        }
        showslide(currentindex);
    }

    let sliderInterval = setInterval(nextSlide, 3000);

    dots.forEach((dot, index) => {
        dot.addEventListener('click', () => {
            currentindex = index;
            showslide(currentindex);
            clearInterval(sliderInterval);
            sliderInterval = setInterval(nextSlide, 3000);
        });
    });
}

// 2. Brand Track
const track = document.querySelector(".brand-track");
if (track) {
    let index = 0;
    setInterval(() => {
        index++;
        if (index > 2) {
            index = 0;
        }
        track.style.transform = `translateX(-${index * 33.33}%)`;
    }, 3000);
}

// 3. Image Card Slider (Sản phẩm nổi bật)
const slider = document.querySelector('.imagecard');
const btnLeft = document.querySelector('.arrow.left');
const btnRight = document.querySelector('.arrow.right');
const card = document.querySelector('.card_image');

if (slider && card && btnLeft && btnRight) {
    let scrollAmount = card.offsetWidth + 20; // 20 là gap

    function updateButtons() {
        if (slider.scrollLeft <= 5) {
            btnLeft.style.display = 'none';
        } else {
            btnLeft.style.display = 'block';
        }
        if (slider.clientWidth + slider.scrollLeft >= slider.scrollWidth - 5) {
            btnRight.style.display = 'none';
        } else {
            btnRight.style.display = 'block';
        }
    }

    // Click sang phải
    btnRight.addEventListener('click', () => {
        slider.scrollBy({
            left: scrollAmount,
            behavior: 'smooth'
        });
        setTimeout(updateButtons, 300);
    });

    // Click sang trái
    btnLeft.addEventListener('click', () => {
        slider.scrollBy({
            left: -scrollAmount,
            behavior: 'smooth'
        });
        setTimeout(updateButtons, 300);
    });

    // Khi scroll bằng tay
    slider.addEventListener('scroll', () => {
        updateButtons();
    });

    // Khi resize
    window.addEventListener('resize', () => {
        scrollAmount = card.offsetWidth + 20;
        updateButtons();
    });

    // Chạy lần đầu
    updateButtons();
}