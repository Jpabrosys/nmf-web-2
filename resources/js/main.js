// rashifal
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
