import './bootstrap';
import './rashifal';
import './main';

import $ from 'jquery';
window.$ = window.jQuery = $;

import 'owl.carousel';

/* ✅ Swiper – MUST be exposed globally */
import Swiper from 'swiper/bundle';
import 'swiper/css/bundle';

window.Swiper = Swiper;

console.log('Swiper loaded:', window.Swiper);
