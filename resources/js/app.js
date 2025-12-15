import './bootstrap';

// 1. Import jQuery and make it global (Crucial for OwlCarousel & legacy scripts)
import $ from 'jquery';
window.$ = window.jQuery = $;

// 2. Import OwlCarousel (Required for the carousel in your footer)
import 'owl.carousel';

// 3. Import Swiper (Replacing local ./swiper-bundle.min.js)
import Swiper from 'swiper/bundle';
// If you need Swiper globally available in inline scripts:
window.Swiper = Swiper; 

// 4. Import Main Custom JS
import './main.js';

console.log('App.js loaded with localized dependencies');