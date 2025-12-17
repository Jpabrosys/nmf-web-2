function initRashifal() {
    const slider = document.querySelector(".rashifal-slider");
    if (!slider) return;

    const titleEl = document.getElementById("rashifal-title");
    const textEl = document.getElementById("rashifal-text");
    const prevBtn = document.querySelector(".nav-btn.prev");
    const nextBtn = document.querySelector(".nav-btn.next");

    function activateItem(imgElement) {
        if (!imgElement) return;

        slider
            .querySelectorAll("img")
            .forEach((img) => img.classList.remove("active"));
        imgElement.classList.add("active");

        if (titleEl)
            titleEl.textContent =
                "आपके तारे - दैनिक: " + (imgElement.dataset.title || "");
        if (textEl) textEl.textContent = imgElement.dataset.description || "";

        setTimeout(() => centerItem(imgElement), 50);
    }

    function centerItem(imgElement) {
        const itemWrapper = imgElement.closest(".rashifal-item");
        if (!itemWrapper) return;

        const sliderRect = slider.getBoundingClientRect();
        const itemRect = itemWrapper.getBoundingClientRect();

        const offset = itemRect.left - sliderRect.left;
        const centerPos = sliderRect.width / 2 - itemRect.width / 2;

        slider.scrollBy({
            left: offset - centerPos,
            behavior: "smooth",
        });
    }

    slider.addEventListener("click", (e) => {
        const icon = e.target.closest("img");
        if (icon) {
            activateItem(icon);
        }
    });

    function handleNavigation(direction) {
        const images = Array.from(
            slider.querySelectorAll(".rashifal-item img")
        );
        let currentIndex = images.findIndex((img) =>
            img.classList.contains("active")
        );

        if (currentIndex === -1) currentIndex = 0;

        let newIndex;
        if (direction === "next") {
            newIndex = currentIndex + 1;
            if (newIndex >= images.length) newIndex = 0;
        } else {
            newIndex = currentIndex - 1;
            if (newIndex < 0) newIndex = images.length - 1;
        }

        activateItem(images[newIndex]);
    }

    if (prevBtn)
        prevBtn.addEventListener("click", () => handleNavigation("prev"));
    if (nextBtn)
        nextBtn.addEventListener("click", () => handleNavigation("next"));

    let startIcon = slider.querySelector("img.active");

    if (!startIcon) {
        const allIcons = Array.from(
            slider.querySelectorAll(".rashifal-item img")
        );
        const mithunIcon = allIcons.find(
            (img) => img.dataset.title && img.dataset.title.trim() === "मिथुन"
        );

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

window.addEventListener("load", initRashifal);
// Tag==================================
document.addEventListener("DOMContentLoaded", function () {
    const swiperTags = new Swiper(".swiper-tags-main", {
        slidesPerView: "auto",
        spaceBetween: 10,
        freeMode: true,
        grabCursor: true,
        navigation: {
            nextEl: ".swiper-tags-button-next",
            prevEl: ".swiper-tags-button-prev",
        },
        breakpoints: {
            320: { spaceBetween: 8 },
            768: { spaceBetween: 10 },
            1024: { spaceBetween: 12 },
        },
    });
});
// ==============================
window.onscroll = function () {
    myFunction();
};
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

//    for toggle tabs -----------------------------------------------------
document.addEventListener("DOMContentLoaded", function () {
    const tabs = document.querySelectorAll(".tab-btn");
    const contents = document.querySelectorAll(".tab-content");

    tabs.forEach((tab) => {
        tab.addEventListener("click", function () {
            tabs.forEach((t) => t.classList.remove("active"));
            this.classList.add("active");

            contents.forEach((content) => content.classList.remove("active"));
            document.getElementById(this.dataset.tab).classList.add("active");
        });
    });
});
//    dharm gyan tab -----------------------------------------------------
document.addEventListener("DOMContentLoaded", () => {
    const nav = document.querySelector("#vertical_tab_nav");
    if (!nav) return;

    const activeTab = nav.querySelector("ul li a.selected");
    if (activeTab) return; // ✅ already active → do nothing

    const firstTab = nav.querySelector("ul li a");
    const firstArticle = nav.querySelector(".vt_content > article");

    if (firstTab) firstTab.classList.add("selected");
    if (firstArticle) firstArticle.style.display = "block";
});

document.addEventListener("click", function (e) {
    const link = e.target.closest("#vertical_tab_nav > ul > li > a");
    if (!link) return;

    e.preventDefault();

    const nav = link.closest("#vertical_tab_nav");
    const tabs = nav.querySelectorAll("ul li a");
    const articles = nav.querySelectorAll(".vt_content > article");

    const index = Array.from(tabs).indexOf(link);

    // Tabs
    tabs.forEach((t) => t.classList.remove("selected"));
    link.classList.add("selected");

    // Content
    articles.forEach((a) => (a.style.display = "none"));
    if (articles[index]) {
        articles[index].style.display = "block";
    }
});

//   tag swiper
document.addEventListener("DOMContentLoaded", function () {
    const swiperTags = new Swiper(".swiper-tags-main", {
        loop: true,
        slidesPerView: "auto",
        centeredSlides: false,
        slidesPerGroup: 1,
        spaceBetween: 1,
        speed: 500,
        navigation: {
            nextEl: ".swiper-tags-button-next",
            prevEl: ".swiper-tags-button-prev",
        },
        breakpoints: {
            0: {
                centeredSlides: true,
            },
            481: {
                centeredSlides: false,
            },
        },
    });
});

// related news slider
document.addEventListener("DOMContentLoaded", function () {
    new Swiper(".rel-swiper", {
        slidesPerView: 3, // show 3 cards at a time
        spaceBetween: 16, // 16 px gap between cards
        navigation: {
            nextEl: ".rel-nav-next",
            prevEl: ".rel-nav-prev",
        },
        breakpoints: {
            // responsiveness (optional)
            0: {
                slidesPerView: 1.2,
                spaceBetween: 12,
            },
            480: {
                slidesPerView: 2,
                spaceBetween: 14,
            },
            768: {
                slidesPerView: 4,
                spaceBetween: 12,
            },
        },
    });
});
// sort video slider
document.addEventListener("DOMContentLoaded", () => {
    const swiperContainer = document.querySelector(".storySwiper");
    if (!swiperContainer) return;

    // Count only original (non-duplicate) slides
    const totalSlides = swiperContainer.querySelectorAll(
        ".swiper-slide:not(.swiper-slide-duplicate)"
    ).length;

    // Init Swiper
    const swiper = new Swiper(swiperContainer, {
        slidesPerView: "6",
        spaceBetween: 15,
        loop: totalSlides > 7,
        loopAdditionalSlides: 2,
        loopFillGroupWithBlank: false,
        navigation: {
            nextEl: ".story-nav-next",
            prevEl: ".story-nav-prev",
        },
        lazy: {
            loadOnTransitionStart: true,
            loadPrevNext: true,
            loadPrevNextAmount: 2,
        },
        watchSlidesProgress: true,
        breakpoints: {
            0: { slidesPerView: 2.2, spaceBetween: 10 },
            601: { slidesPerView: 2.2, spaceBetween: 12 },
            768: { slidesPerView: 4, spaceBetween: 20 },
            1024: { slidesPerView: 5, spaceBetween: 20 },
            1280: { slidesPerView: 5, spaceBetween: 20 },
            1440: { slidesPerView: 5, spaceBetween: 20 },
        },
    });

    // Navigation button enable/disable logic
    const navButtons = document.querySelectorAll(
        ".story-nav-prev, .story-nav-next"
    );

    function updateNavigationState() {
        const currentSlidesPerView =
            swiper.params.slidesPerView === "auto"
                ? swiper.slidesPerViewDynamic()
                : swiper.params.slidesPerView;

        const disableNav = totalSlides <= currentSlidesPerView;
        navButtons.forEach((btn) =>
            btn.classList.toggle("swiper-button-disabled", disableNav)
        );
    }

    // Run on init and whenever Swiper resizes
    swiper.on("resize", updateNavigationState);
    updateNavigationState();
});

//  app download modal
function isMobile() {
    return (
        /Android|iPhone|iPad|iPod|Opera Mini|IEMobile|WPDesktop/i.test(
            navigator.userAgent
        ) || window.innerWidth <= 768
    );
}

function isAndroid() {
    return /Android/i.test(navigator.userAgent);
}

function updateStatus(message) {
    const status = document.getElementById("status");
    if (status) {
        status.textContent = message;
        setTimeout(() => {
            status.style.display = "none";
        }, 3000);
    }
}

// Modal functions
function showModal() {
    const modal = document.getElementById("appDownloadModal");
    if (modal) {
        modal.style.display = "flex";
        updateStatus("Showing install modal");
    }
}

function closeModal() {
    const modal = document.getElementById("appDownloadModal");
    if (modal) {
        modal.style.display = "none";
        localStorage.setItem("appModalShown", "true");
    }
}

// App detection using modern Web API
async function checkAppInstalledModernAPI() {
    if ("getInstalledRelatedApps" in navigator) {
        try {
            const relatedApps = await navigator.getInstalledRelatedApps();
            console.log("Related apps found:", relatedApps);

            // Check if our specific app is installed
            const appInstalled = relatedApps.find(
                (app) =>
                    app.platform === "play" &&
                    (app.id === "com.kmcliv.nmfnews" ||
                        app.url?.includes("com.kmcliv.nmfnews"))
            );

            if (appInstalled) {
                updateStatus("App detected as installed - modal hidden");
                return true; // App is installed
            }
        } catch (error) {
            console.log("Modern API detection failed:", error);
        }
    }

    return false; // App not detected or API not supported
}

// Fallback detection using silent deep link test
function checkAppInstalledFallback() {
    return new Promise((resolve) => {
        if (!isAndroid()) {
            resolve(false); // Only works reliably on Android
            return;
        }

        const appScheme = "nmfnews://check"; // Your app's deep link scheme
        let appDetected = false;

        // Listen for page visibility changes
        const handleVisibilityChange = () => {
            if (document.hidden) {
                appDetected = true;
                cleanup();
                resolve(true);
            }
        };

        const cleanup = () => {
            document.removeEventListener(
                "visibilitychange",
                handleVisibilityChange
            );
            if (testFrame && testFrame.parentNode) {
                testFrame.parentNode.removeChild(testFrame);
            }
        };

        document.addEventListener("visibilitychange", handleVisibilityChange);

        // Create hidden iframe to test deep link
        const testFrame = document.createElement("iframe");
        testFrame.style.display = "none";
        testFrame.style.width = "1px";
        testFrame.style.height = "1px";
        testFrame.src = appScheme;
        document.body.appendChild(testFrame);

        // Timeout for detection
        setTimeout(() => {
            cleanup();
            resolve(appDetected);
        }, 1500);
    });
}

// Main detection logic
async function shouldShowInstallModal() {
    updateStatus("Checking for app installation...");

    // Try modern API first
    if (await checkAppInstalledModernAPI()) {
        return false; // App installed, don't show modal
    }

    // Fallback for Android devices
    if (isAndroid()) {
        const appInstalled = await checkAppInstalledFallback();
        if (appInstalled) {
            updateStatus("App detected via fallback - modal hidden");
            return false;
        }
    }

    updateStatus("App not detected - showing modal");
    return true; // Show modal
}

// Initialize on page load
document.addEventListener("DOMContentLoaded", async function () {
    updateStatus("Page loaded, checking device...");

    // Only run on mobile devices
    if (!isMobile()) {
        updateStatus("Desktop detected - no modal needed");
        return;
    }

    // Check if modal was already shown
    const modalShown = localStorage.getItem("appModalShown");
    if (modalShown) {
        updateStatus("Modal previously shown - skipping");
        return;
    }

    // Check if we should show the install modal
    if (await shouldShowInstallModal()) {
        setTimeout(() => {
            showModal();
        }, 5000); // Show modal after 5 seconds
    }
});

// Close modal when clicking outside
document.addEventListener("click", function (event) {
    const modal = document.getElementById("appDownloadModal");
    if (event.target === modal) {
        closeModal();
    }
});
