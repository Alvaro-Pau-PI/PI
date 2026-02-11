document.addEventListener('DOMContentLoaded', () => {
    const banners = [
        { src: 'img/banner_gaming.png', alt: 'Ultimate Gaming Setup' },
        { src: 'img/banner_arena.png', alt: 'Esports Arena Live' },
        { src: 'img/banner2.png', alt: 'Intel Core i9 Promotion' }
    ];

    let currentIndex = 0;
    const bannerImg = document.querySelector('.banner img');

    if (!bannerImg || banners.length <= 1) return;

    // Preload images
    banners.forEach(b => {
        const img = new Image();
        img.src = b.src;
    });

    setInterval(() => {
        currentIndex = (currentIndex + 1) % banners.length;

        // Simple fade effect
        bannerImg.style.opacity = '0';

        setTimeout(() => {
            bannerImg.src = banners[currentIndex].src;
            bannerImg.alt = banners[currentIndex].alt;
            bannerImg.style.opacity = '1';
        }, 500); // Wait for fade out

    }, 5000); // Change every 5 seconds

    // Add CSS transition for opacity
    bannerImg.style.transition = 'opacity 0.5s ease-in-out';
});
