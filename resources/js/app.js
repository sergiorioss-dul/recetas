import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';
import 'owl.carousel';
require('./bootstrap');

window.Vue = require('vue');

Vue.use(VueSweetalert2);
Vue.config.ignoredElements = ['trix-editor','trix-toolbar'];
Vue.component('fecha-receta',require('./components/FechaReceta.vue').default);
Vue.component('eliminar-receta',require('./components/EliminarReceta.vue').default);
Vue.component('like-button',require('./components/LikeButton.vue').default);


const app = new Vue({
    el: '#app',
});

 
 $('.clap-btn').on('click', function() {
    $(this).toggleClass('clap-active');
 });
 

 // Carousel
 jQuery(document).ready(function(){
    jQuery('.owl-carousel').owlCarousel({
        margin:10,
        loop: true,
        autoplay: true,
        autoplayHoverPause: true,
        responsive:{
            0 : {
                items:1
            },
            600:{
                items:2
            },
            1000:{
                items:3
            }
        }
    });
 });