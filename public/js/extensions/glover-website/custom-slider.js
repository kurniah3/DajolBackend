$(document).ready(function () {
    const slider = $('.slider');
    const images = $('.slider img');
    const prevBtn = $('.prev');
    const nextBtn = $('.next');
    let currentIndex = 0;

    function updateSlider() {
        slider.css('transform', `translateX(-${currentIndex * 100}%)`);
    }

    nextBtn.click(function () {
        if (currentIndex < images.length - 1) {
            currentIndex++;
            updateSlider();
        }
    });

    prevBtn.click(function () {
        if (currentIndex > 0) {
            currentIndex--;
            updateSlider();
        }
    });
});