import Swiper from 'swiper/bundle';
import 'swiper/css/bundle';
import { Navigation } from 'swiper/modules';
window.Swiper = Swiper;

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
// ======================================================================
// swiper (category)
function initWebStoriesSwiper() {
  const el = document.querySelector('.web_s_all_slider');
  if (!el) return;
  if (el.classList.contains('swiper-initialized')) return;

  const nextBtn = el.querySelector('.web_s_all_next');
  const prevBtn = el.querySelector('.web_s_all_prev');

  new Swiper(el, {
    slidesPerView: 'auto',
    spaceBetween: 10,
    grabCursor: true,
    allowTouchMove: true,

    navigation: {
      nextEl: nextBtn,
      prevEl: prevBtn,
    },

    observer: true,
    observeParents: true,
  });
}

document.addEventListener('DOMContentLoaded', initWebStoriesSwiper);


// ================ rashifal(dharmgyan)
document.addEventListener("click", function (e) {
    const nav = e.target.closest("#vertical_tab_nav > ul");
    const link = e.target.closest("#vertical_tab_nav > ul > li > a");

    if (!nav || !link) return;

    e.preventDefault();

    const tabs = nav.querySelectorAll("li > a");
    const container = nav.closest("#vertical_tab_nav");
    const articles = container.querySelectorAll(".vt_content > article");

    const index = Array.from(tabs).indexOf(link);

    // Tabs
    tabs.forEach(t => t.classList.remove("selected"));
    link.classList.add("selected");

    // Content
    articles.forEach(a => a.style.display = "none");
    if (articles[index]) {
        articles[index].style.display = "block";
    }
});

document.addEventListener("DOMContentLoaded", () => {
    const firstTab = document.querySelector("#vertical_tab_nav > ul > li > a");
    const firstArticle = document.querySelector(".vt_content > article");

    if (firstTab) firstTab.classList.add("selected");
    if (firstArticle) firstArticle.style.display = "block";
});

// ================================================
// Sticky Header
// ================================================
const header = document.getElementById("myHeader");
const liLogo = document.getElementById("navLogo");

const STICKY_POINT = 100;
let lastScrollY = 0;
let ticking = false;

function updateHeader(scrollY) {
    if (scrollY > STICKY_POINT) {
        header.classList.add("psticky");
        liLogo.classList.add("showLogo");
    } else {
        header.classList.remove("psticky");
        liLogo.classList.remove("showLogo");
    }
}

window.addEventListener(
    "scroll",
    () => {
        lastScrollY = window.scrollY || document.documentElement.scrollTop;

        if (!ticking) {
            window.requestAnimationFrame(() => {
                updateHeader(lastScrollY);
                ticking = false;
            });
            ticking = true;
        }
    },
    { passive: true }
);
// =========================================
// toggle tab
// =========================================

// JavaScript for toggle modal Toggle Modal ----------------------------
const toggleBtn = document.getElementById("toggle-btn");
const modalOverlay = document.getElementById("modal-overlay");
const closeBtn = document.getElementById("close-btn");

toggleBtn.addEventListener("click", () => {
    modalOverlay.classList.add("active");
});

closeBtn.addEventListener("click", () => {
    modalOverlay.classList.remove("active");
});

modalOverlay.addEventListener("click", (e) => {
    if (e.target === modalOverlay) {
        modalOverlay.classList.remove("active");
    }
});
// ===========================
// main webstory
// Function to initialize this specific slider
function initWebStoriesSlider() {
    const el = document.querySelector('.swp-main');

    // Prevent re-initialization or error if element missing
    if (!el || el.classList.contains('swiper-initialized')) return;

    new window.Swiper(el, {
        direction: "horizontal",
        loop: true,
        slidesPerView: 2.07, // Mobile view default
        spaceBetween: 10,
        allowTouchMove: true,

        // ✅ Connect to the new Unique Button Names
        navigation: {
            nextEl: ".ws-nav-next",
            prevEl: ".ws-nav-prev",
        },

        breakpoints: {
            0: {
                slidesPerView: 2.2, 
                spaceBetween: 10,
                navigation: false, 
            },
            600: {
                slidesPerView: 4,
                spaceBetween: 15,
            },
            1024: {
                slidesPerView: 5,
                spaceBetween: 10,
                navigation: {
                    nextEl: ".ws-nav-next",
                    prevEl: ".ws-nav-prev",
                },
            }
        }
    });
}
// reels/shorts================================================
document.addEventListener("DOMContentLoaded", function () {

    const track = document.querySelector(".swp-unique-shorts");
    const prevBtn = document.querySelector(".nav-unique-prev");
    const nextBtn = document.querySelector(".nav-unique-next");

    if (!track) return;

    const scrollStep = 240;

    /* Arrow scroll */
    nextBtn.addEventListener("click", () => {
        track.scrollLeft += scrollStep;
    });

    prevBtn.addEventListener("click", () => {
        track.scrollLeft -= scrollStep;
    });

    /* Mouse drag */
    let isDragging = false;
    let startX = 0;
    let startScroll = 0;

    track.addEventListener("mousedown", (e) => {
        isDragging = true;
        startX = e.pageX;
        startScroll = track.scrollLeft;
    });

    document.addEventListener("mouseup", () => {
        isDragging = false;
    });

    document.addEventListener("mousemove", (e) => {
        if (!isDragging) return;
        e.preventDefault();
        const move = e.pageX - startX;
        track.scrollLeft = startScroll - move;
    });

    /* Touch support (mobile) */
    let touchStartX = 0;
    let touchScroll = 0;

    track.addEventListener("touchstart", (e) => {
        touchStartX = e.touches[0].pageX;
        touchScroll = track.scrollLeft;
    }, { passive: true });

    track.addEventListener("touchmove", (e) => {
        const move = e.touches[0].pageX - touchStartX;
        track.scrollLeft = touchScroll - move;
    }, { passive: true });

});
// Run the function when page loads
document.addEventListener('DOMContentLoaded', initWebStoriesSlider);

document.addEventListener('DOMContentLoaded', () => {

    if (typeof Swiper === 'undefined') return;

    const el = document.querySelector('.storySwiper');
    if (!el || el.classList.contains('swiper-initialized')) return;

    const wrapper = el.closest('.story-strip');
    const prevBtn = wrapper.querySelector('.story-nav-prev');
    const nextBtn = wrapper.querySelector('.story-nav-next');

    const slidesCount = el.querySelectorAll('.swiper-slide').length;

    new Swiper(el, {
        slidesPerView: 'auto',
        spaceBetween: 14,
        grabCursor: true,
        allowTouchMove: true,

        loop: slidesCount > 4,
        loopAdditionalSlides: 2,

        navigation: {
            prevEl: prevBtn,
            nextEl: nextBtn,
        },

        observer: true,
        observeParents: true,
    });
});
