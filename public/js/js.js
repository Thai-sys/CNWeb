// banner
const slides = document.querySelectorAll('.main_banner img');

if (slides.length > 0) {
    const totalSlides = slides.length;
    let Index = 0;
    let intervalId;

    for (let i = 1; i < totalSlides; i++) {
        slides[i].style.display = 'none';
    }

    function showSlide(index) {
        slides.forEach(slide => {
            slide.style.display = 'none';
        });
        if (index < 0) {
            Index = totalSlides - 1;
        } else if (index >= totalSlides) {
            Index = 0;
        } else {
            Index = index;
        }
        slides[Index].style.display = 'block';
    }

    function nextSlide() {
        showSlide(Index + 1);
    }

    function prevSlide() {
        showSlide(Index - 1);
    }

    function startSlideshow() {
        intervalId = setInterval(nextSlide, 4000);
    }

    startSlideshow();
}

// product
function showProducts(category) {
    const bestSellerProducts = document.getElementById('best-seller-products');
    const featuredProducts = document.getElementById('featured-products');
    const newProducts = document.getElementById('new-products');
    const domesticProducts = document.getElementById('domestic-products');
    const importedProducts = document.getElementById('imported-products');

    if (bestSellerProducts) bestSellerProducts.classList.add('hidden');
    if (featuredProducts) featuredProducts.classList.add('hidden');
    if (newProducts) newProducts.classList.add('hidden');
    if (domesticProducts) domesticProducts.classList.add('hidden');
    if (importedProducts) importedProducts.classList.add('hidden');

    if (category === 'best_seller' && bestSellerProducts) {
        bestSellerProducts.classList.remove('hidden');
        document.getElementById('product-title').textContent = 'Sản phẩm bán chạy';
    } else if (category === 'featured' && featuredProducts) {
        featuredProducts.classList.remove('hidden');
        document.getElementById('product-title').textContent = 'Sản phẩm nổi bật';
    } else if (category === 'new' && newProducts) {
        newProducts.classList.remove('hidden');
        document.getElementById('product-title').textContent = 'Sản phẩm mới ra mắt';
    } else if (category === 'domestic' && domesticProducts) {
        domesticProducts.classList.remove('hidden');
        document.getElementById('product-title').textContent = 'Sản phẩm nội địa';
    } else if (category === 'imported' && importedProducts) {
        importedProducts.classList.remove('hidden');
        document.getElementById('product-title').textContent = 'Sản phẩm ngoại địa';
    }

    // Bỏ lớp nổi bật cho tất cả các nút và thêm lớp nổi bật cho nút được chọn
    const buttons = document.querySelectorAll('.product-button');
    buttons.forEach(button => {
        button.classList.remove('bg-coffee-light', 'text-coffee-dark'); // Xóa lớp nổi bật
        button.classList.add('bg-coffee-dark', 'text-coffee-light'); // Thêm lớp mặc định
    });

    const selectedButton = document.querySelector(`button[onclick="showProducts('${category}')"]`);
    if (selectedButton) {
        selectedButton.classList.add('bg-coffee-light', 'text-coffee-dark'); // Thêm lớp nổi bật cho nút được chọn
    }
}

// sử lí nút hủy cho thông báo yêu cầu đăng nhập 
const cancelButton = document.getElementById('cancel-button');
if (cancelButton) {
    cancelButton.addEventListener('click', function () {
        document.getElementById('login-modal').classList.add('hidden'); // Ẩn modal
    });
}

