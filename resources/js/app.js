import './bootstrap'; // Laravel's Axios setup
import 'bootstrap';   // <--- ADD THIS if you use Bootstrap Modals/Dropdowns

import './custom';
import './main';

import $ from 'jquery';
window.$ = window.jQuery = $;

import 'owl.carousel';

/* ✅ Swiper Setup */
import Swiper from 'swiper/bundle';
import 'swiper/css/bundle';
window.Swiper = Swiper;
console.log('Swiper loaded:', window.Swiper);