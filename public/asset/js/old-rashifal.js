function initRashifal() {
    const slider = document.querySelector('.rashifal-slider');
    if (!slider) return;

    const titleEl = document.getElementById('rashifal-title');
    const textEl = document.getElementById('rashifal-text');
    const prevBtn = document.querySelector('.nav-btn.prev');
    const nextBtn = document.querySelector('.nav-btn.next');

    function activateItem(imgElement) {
        if (!imgElement) return;

        slider.querySelectorAll('img').forEach(img => img.classList.remove('active'));
        imgElement.classList.add('active');

        if (titleEl) titleEl.textContent = "आपके तारे - दैनिक: " + (imgElement.dataset.title || "");
        if (textEl) textEl.textContent = imgElement.dataset.description || "";

        setTimeout(() => centerItem(imgElement), 50);
    }

    function centerItem(imgElement) {
        const itemWrapper = imgElement.closest('.rashifal-item');
        if (!itemWrapper) return;

        const sliderRect = slider.getBoundingClientRect();
        const itemRect = itemWrapper.getBoundingClientRect();

        const offset = itemRect.left - sliderRect.left;
        const centerPos = (sliderRect.width / 2) - (itemRect.width / 2);
        
        slider.scrollBy({
            left: offset - centerPos,
            behavior: 'smooth'
        });
    }

    slider.addEventListener('click', (e) => {
        const icon = e.target.closest('img');
        if (icon) {
            activateItem(icon);
        }
    });

    function handleNavigation(direction) {
        const images = Array.from(slider.querySelectorAll('.rashifal-item img'));
        let currentIndex = images.findIndex(img => img.classList.contains('active'));
        
        if (currentIndex === -1) currentIndex = 0;

        let newIndex;
        if (direction === 'next') {
            newIndex = currentIndex + 1;
            if (newIndex >= images.length) newIndex = 0;
        } else {
            newIndex = currentIndex - 1;
            if (newIndex < 0) newIndex = images.length - 1;
        }

        activateItem(images[newIndex]);
    }

    if (prevBtn) prevBtn.addEventListener('click', () => handleNavigation('prev'));
    if (nextBtn) nextBtn.addEventListener('click', () => handleNavigation('next'));

    let startIcon = slider.querySelector('img.active');

    if (!startIcon) {
        const allIcons = Array.from(slider.querySelectorAll('.rashifal-item img'));
        const mithunIcon = allIcons.find(img => img.dataset.title && img.dataset.title.trim() === 'मिथुन');

        if (mithunIcon) {
            startIcon = mithunIcon;
        } else if (allIcons.length > 2) {
            startIcon = allIcons[2]; 
        } else if (allIcons.length > 0) {
            startIcon = allIcons[0]; 
        }
    }

    if (startIcon) {
        activateItem(startIcon);
    }
}

window.addEventListener('load', initRashifal);