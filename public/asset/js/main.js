
// Tag==================================
document.addEventListener("DOMContentLoaded", function() {
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
            1024: { spaceBetween: 12 }
        }
    });
});
// ==============================
window.onscroll = function() {
  myFunction();
};

var header = document.getElementById("myHeader");
var liLogo = document.getElementById("navLogo");
var sticky = 100; // Adjust scroll threshold (px)

function myFunction() {
  let scrollTop = window.scrollY || document.documentElement.scrollTop;

  if (scrollTop > sticky) {
    header.classList.add("psticky");
    liLogo.classList.add("showLogo");
  } else {
    header.classList.remove("psticky");
    liLogo.classList.remove("showLogo");
  }
}
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
    
    tabs.forEach(tab => {
        tab.addEventListener("click", function () {
            tabs.forEach(t => t.classList.remove("active"));
            this.classList.add("active");
            
            contents.forEach(content => content.classList.remove("active"));
            document.getElementById(this.dataset.tab).classList.add("active");
        });
    });
});
    //    dharm gyan tab -----------------------------------------------------
    $(document).ready(function(){
						   

        //----------Select the first tab and div by default
        
        $('#vertical_tab_nav > ul > li > a').eq(0).addClass( "selected" );
        $('#vertical_tab_nav > div > article').eq(0).css('display','block');
      
      
        //---------- This assigns an onclick event to each tab link("a" tag) and passes a parameter to the showHideTab() function
            
          $('#vertical_tab_nav > ul').click(function(e){
            
            if($(e.target).is("a")){
            
              /*Handle Tab Nav*/
              $('#vertical_tab_nav > ul > li > a').removeClass( "selected");
              $(e.target).addClass( "selected");
              
              /*Handles Tab Content*/
              var clicked_index = $("a",this).index(e.target);
              $('#vertical_tab_nav > div > article').css('display','none');
              $('#vertical_tab_nav > div > article').eq(clicked_index).fadeIn();
              
            }
            
              $(this).blur();
              return false;
            
          });
          
         
      });

    //   tag swiper
    document.addEventListener('DOMContentLoaded', function () {
      const swiperTags = new Swiper('.swiper-tags-main', {
        loop: true,
        slidesPerView: 'auto',
        centeredSlides: false, 
        slidesPerGroup: 1,
        spaceBetween: 1,
        speed: 500,
        navigation: {
          nextEl: '.swiper-tags-button-next',
          prevEl: '.swiper-tags-button-prev',
        },
        breakpoints: {
          0: {
            centeredSlides: true,
          },
          481: {
            centeredSlides: false,
          }
        }
      });
    });
    
    // related news slider
            document.addEventListener('DOMContentLoaded', function() {
            new Swiper('.rel-swiper', {
                slidesPerView: 3, // show 3 cards at a time
                spaceBetween: 16, // 16 px gap between cards
                navigation: {
                    nextEl: '.rel-nav-next',
                    prevEl: '.rel-nav-prev'
                },
                breakpoints: { // responsiveness (optional)
                    0: {
                        slidesPerView: 1.2,
                        spaceBetween: 12
                    },
                    480: {
                        slidesPerView: 2,
                        spaceBetween: 14
                    },
                    768: {
                        slidesPerView: 4,
                        spaceBetween: 12
                    }
                }
            });
        });
    // sort video slider
    document.addEventListener("DOMContentLoaded", () => {
    const swiperContainer = document.querySelector(".storySwiper");
    if (!swiperContainer) return;

    // Count only original (non-duplicate) slides
    const totalSlides = swiperContainer.querySelectorAll(".swiper-slide:not(.swiper-slide-duplicate)").length;

    // Init Swiper
    const swiper = new Swiper(swiperContainer, {
        slidesPerView: '6',
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
            1440: { slidesPerView: 5, spaceBetween: 20 }
        }
    });

    // Navigation button enable/disable logic
    const navButtons = document.querySelectorAll(".story-nav-prev, .story-nav-next");

    function updateNavigationState() {
        const currentSlidesPerView =
            swiper.params.slidesPerView === 'auto'
                ? swiper.slidesPerViewDynamic()
                : swiper.params.slidesPerView;

        const disableNav = totalSlides <= currentSlidesPerView;
        navButtons.forEach(btn =>
            btn.classList.toggle('swiper-button-disabled', disableNav)
        );
    }

    // Run on init and whenever Swiper resizes
    swiper.on('resize', updateNavigationState);
    updateNavigationState();
});



    //  app download modal
   function isMobile() {
    return /Android|iPhone|iPad|iPod|Opera Mini|IEMobile|WPDesktop/i.test(navigator.userAgent) || window.innerWidth <= 768;
}

function isAndroid() {
    return /Android/i.test(navigator.userAgent);
}

function updateStatus(message) {
    const status = document.getElementById('status');
    if (status) {
        status.textContent = message;
        setTimeout(() => {
            status.style.display = 'none';
        }, 3000);
    }
}

// Modal functions
function showModal() {
    const modal = document.getElementById('appDownloadModal');
    if (modal) {
        modal.style.display = 'flex';
        updateStatus('Showing install modal');
    }
}

function closeModal() {
    const modal = document.getElementById('appDownloadModal');
    if (modal) {
        modal.style.display = 'none';
        localStorage.setItem('appModalShown', 'true');
    }
}

// App detection using modern Web API
async function checkAppInstalledModernAPI() {
    if ('getInstalledRelatedApps' in navigator) {
        try {
            const relatedApps = await navigator.getInstalledRelatedApps();
            console.log('Related apps found:', relatedApps);
            
            // Check if our specific app is installed
            const appInstalled = relatedApps.find(app => 
                app.platform === 'play' && 
                (app.id === 'com.kmcliv.nmfnews' || app.url?.includes('com.kmcliv.nmfnews'))
            );
            
            if (appInstalled) {
                updateStatus('App detected as installed - modal hidden');
                return true; // App is installed
            }
        } catch (error) {
            console.log('Modern API detection failed:', error);
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

        const appScheme = 'nmfnews://check'; // Your app's deep link scheme
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
            document.removeEventListener('visibilitychange', handleVisibilityChange);
            if (testFrame && testFrame.parentNode) {
                testFrame.parentNode.removeChild(testFrame);
            }
        };
        
        document.addEventListener('visibilitychange', handleVisibilityChange);
        
        // Create hidden iframe to test deep link
        const testFrame = document.createElement('iframe');
        testFrame.style.display = 'none';
        testFrame.style.width = '1px';
        testFrame.style.height = '1px';
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
    updateStatus('Checking for app installation...');
    
    // Try modern API first
    if (await checkAppInstalledModernAPI()) {
        return false; // App installed, don't show modal
    }
    
    // Fallback for Android devices
    if (isAndroid()) {
        const appInstalled = await checkAppInstalledFallback();
        if (appInstalled) {
            updateStatus('App detected via fallback - modal hidden');
            return false;
        }
    }
    
    updateStatus('App not detected - showing modal');
    return true; // Show modal
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', async function () {
    updateStatus('Page loaded, checking device...');
    
    // Only run on mobile devices
    if (!isMobile()) {
        updateStatus('Desktop detected - no modal needed');
        return;
    }
    
    // Check if modal was already shown
    const modalShown = localStorage.getItem('appModalShown');
    if (modalShown) {
        updateStatus('Modal previously shown - skipping');
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
document.addEventListener('click', function(event) {
    const modal = document.getElementById('appDownloadModal');
    if (event.target === modal) {
        closeModal();
    }
});