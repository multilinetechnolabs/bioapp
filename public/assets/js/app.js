window.$ = window.jQuery = require('jquery');
require('jquery-ui/ui/widgets/tabs.js'); 
require('jquery-ui/ui/widgets/datepicker'); 

window.angular = require('angular');
require('../js/app/angular/module');

window.Swiper = require('swiper/dist/js/swiper'); 

$.ajaxSetup({
    beforeSend: function (xhr) {
        if (document.querySelector("meta[name='api-token']")) {
            var apiToken = document.querySelector("meta[name='api-token']").content;
            xhr.setRequestHeader("Authorization", "Bearer " + apiToken);    
        }  

        if (document.querySelector("meta[name='csrf-token']")) {
            var csrfToken = document.querySelector("meta[name='csrf-token']").content;
            xhr.setRequestHeader("X-CSRF-TOKEN", csrfToken);    
        }
    }
});
