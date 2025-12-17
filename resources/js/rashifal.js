// 1. Listen for clicks on the entire document (Works for Lazy Loaded content)
document.addEventListener('click', function(e) {
    
    // CASE A: User clicked a Zodiac Icon (Image)
    const icon = e.target.closest('.rashifal-item img');
    if (icon) {
        // Find the main container (.rashifal-box) to keep things organized
        const box = icon.closest('.rashifal-box');
        if (box) {
            activateRashifalItem(icon, box);
        }
    }

    // CASE B: User clicked Next/Prev Buttons
    const btn = e.target.closest('.nav-btn');
    if (btn) {
        const box = btn.closest('.rashifal-box');
        if (box) {
            const slider = box.querySelector('.rashifal-slider');
            const direction = btn.classList.contains('next') ? 'next' : 'prev';
            handleRashifalNavigation(slider, direction, box);
        }
    }
});

// Helper Function: Update Text & Highlight Icon
function activateRashifalItem(imgElement, container) {
    if (!imgElement || !container) return;

    // 1. Highlight the Active Image
    const slider = container.querySelector('.rashifal-slider');
    if (slider) {
        slider.querySelectorAll('img').forEach(img => img.classList.remove('active'));
        imgElement.classList.add('active');
        
        // 2. Center the image in the slider
        centerRashifalItem(imgElement, slider);
    }

    // 3. Update the Title and Text below
    // Note: We use container.querySelector to make sure we find the right text box
    const titleEl = container.querySelector('#rashifal-title');
    const textEl = container.querySelector('#rashifal-text');

    if (titleEl) titleEl.textContent = "आपके तारे - दैनिक: " + (imgElement.dataset.title || "");
    if (textEl) textEl.textContent = imgElement.dataset.description || "";
}

// Helper Function: Scroll the Slider
function centerRashifalItem(imgElement, slider) {
    const itemWrapper = imgElement.closest('.rashifal-item');
    if (!itemWrapper || !slider) return;

    const sliderRect = slider.getBoundingClientRect();
    const itemRect = itemWrapper.getBoundingClientRect();
    
    // Calculate center position
    const offset = itemRect.left - sliderRect.left;
    const centerPos = (sliderRect.width / 2) - (itemRect.width / 2);
    
    slider.scrollBy({
        left: offset - centerPos,
        behavior: 'smooth'
    });
}

// Helper Function: Handle Next/Prev Logic
function handleRashifalNavigation(slider, direction, container) {
    if (!slider) return;
    
    const images = Array.from(slider.querySelectorAll('.rashifal-item img'));
    let currentIndex = images.findIndex(img => img.classList.contains('active'));
    
    if (currentIndex === -1) currentIndex = 0;

    let newIndex;
    if (direction === 'next') {
        newIndex = currentIndex + 1;
        if (newIndex >= images.length) newIndex = 0; // Loop back to start
    } else {
        newIndex = currentIndex - 1;
        if (newIndex < 0) newIndex = images.length - 1; // Loop back to end
    }

    activateRashifalItem(images[newIndex], container);
}

document.addEventListener("click", function (e) {

    const tab = e.target.closest(".js-tab-button");
    if (!tab) return;

    const container = tab.closest(".js-tabs-container");
    if (!container) return;

    const tabId = tab.dataset.tab;

    // Remove active from buttons
    container.querySelectorAll(".js-tab-button")
        .forEach(btn => btn.classList.remove("active"));

    // Remove active from contents
    container.querySelectorAll(".js-tab-content")
        .forEach(content => content.classList.remove("active"));

    // Activate current
    tab.classList.add("active");

    const activeContent = container.querySelector(
        `.js-tab-content[data-content="${tabId}"]`
    );

    if (activeContent) {
        activeContent.classList.add("active");
    }
});

