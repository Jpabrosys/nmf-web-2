function initRashifal() {
    const slider = document.querySelector('.rashifal-slider');
    if (!slider) return;

    const titleEl = document.getElementById('rashifal-title');
    const textEl = document.getElementById('rashifal-text');
    const prevBtn = document.querySelector('.nav-btn.prev');
    const nextBtn = document.querySelector('.nav-btn.next');

    // 1. Unified function to Update Active Class, Text, and Scroll Position
    function activateItem(imgElement) {
        if (!imgElement) return;

        // A. Update Active Class
        slider.querySelectorAll('img').forEach(img => img.classList.remove('active'));
        imgElement.classList.add('active');

        // B. Update Text Content (Title & Description)
        if (titleEl) titleEl.textContent = "आपके तारे - दैनिक: " + imgElement.dataset.title;
        if (textEl) textEl.textContent = imgElement.dataset.description;

        // C. Center the selected item in the slider
        centerItem(imgElement);
    }

    // 2. Helper to Center the Wrapper Element
    function centerItem(imgElement) {
        const itemWrapper = imgElement.closest('.rashifal-item');
        if (!itemWrapper) return;

        // Use getBoundingClientRect for precise positioning relative to the viewport
        const sliderRect = slider.getBoundingClientRect();
        const itemRect = itemWrapper.getBoundingClientRect();

        // Calculate offset to center
        const offset = itemRect.left - sliderRect.left;
        const centerPos = (sliderRect.width / 2) - (itemRect.width / 2);
        
        // Scroll
        slider.scrollBy({
            left: offset - centerPos,
            behavior: 'smooth'
        });
    }

    // 3. Click Event (User clicks an icon directly)
    slider.addEventListener('click', (e) => {
        const icon = e.target.closest('img');
        if (icon) {
            activateItem(icon);
        }
    });

    // 4. Next / Prev Button Logic
    function handleNavigation(direction) {
        // Get all actual horoscope images (ignoring spacers)
        const images = Array.from(slider.querySelectorAll('.rashifal-item img'));
        
        // Find current active index
        let currentIndex = images.findIndex(img => img.classList.contains('active'));
        
        // Default to 0 if nothing is active
        if (currentIndex === -1) currentIndex = 0;

        let newIndex;
        if (direction === 'next') {
            newIndex = currentIndex + 1;
            // Loop back to start if we reach the end
            if (newIndex >= images.length) newIndex = 0;
        } else {
            newIndex = currentIndex - 1;
            // Loop to end if we are at the start
            if (newIndex < 0) newIndex = images.length - 1;
        }

        // Trigger the update for the new item
        activateItem(images[newIndex]);
    }

    // Attach listeners to buttons
    if (prevBtn) prevBtn.addEventListener('click', () => handleNavigation('prev'));
    if (nextBtn) nextBtn.addEventListener('click', () => handleNavigation('next'));

    // 5. Initialize (Ensure first item is centered/active if needed)
    const activeIcon = slider.querySelector('img.active');
    if (activeIcon) {
        // Optional: uncomment if you want it to center on load
        // centerItem(activeIcon); 
    }
}

document.addEventListener('DOMContentLoaded', initRashifal);