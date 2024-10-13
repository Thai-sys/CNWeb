

//banner

const slides = document.querySelectorAll('.main_banner img')
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







// product
function showProducts(category) {

    document.getElementById('best-seller-products').classList.add('hidden');
    document.getElementById('featured-products').classList.add('hidden');
    document.getElementById('new-products').classList.add('hidden');
    document.getElementById('domestic-products').classList.add('hidden');
    document.getElementById('imported-products').classList.add('hidden');


    if (category === 'best_seller') {
        document.getElementById('best-seller-products').classList.remove('hidden');
        document.getElementById('product-title').textContent = 'Sản phẩm bán chạy';
    } else if (category === 'featured') {
        document.getElementById('featured-products').classList.remove('hidden');
        document.getElementById('product-title').textContent = 'Sản phẩm nổi bật';
    } else if (category === 'new') {
        document.getElementById('new-products').classList.remove('hidden');
        document.getElementById('product-title').textContent = 'Sản phẩm mới ra mắt';
    } else if (category === 'domestic') {
        document.getElementById('domestic-products').classList.remove('hidden');
        document.getElementById('product-title').textContent = 'Sản phẩm nội địa';
    } else if (category === 'imported') {
        document.getElementById('imported-products').classList.remove('hidden');
        document.getElementById('product-title').textContent = 'Sản phẩm ngoại địa';
    }

    // Bỏ lớp nổi bật cho tất cả các nút và thêm lớp nổi bật cho nút được chọn
    const buttons = document.querySelectorAll('.product-button');
    buttons.forEach(button => {
        button.classList.remove('bg-coffee-light', 'text-coffee-dark'); // Xóa lớp nổi bật
        button.classList.add('bg-coffee-dark', 'text-coffee-light'); // Thêm lớp mặc định
    });

    const selectedButton = document.querySelector(`button[onclick="showProducts('${category}')"]`);
    selectedButton.classList.add('bg-coffee-light', 'text-coffee-dark'); // Thêm lớp nổi bật cho nút được chọn
}