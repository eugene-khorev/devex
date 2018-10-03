
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

Vue.prototype.$http = axios;

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
const { yandexMap, ymapMarker } = require('vue-yandex-maps');

Vue.component('yandex-map', yandexMap);
Vue.component('yandex-map-marker', ymapMarker);
Vue.component('autocomplete-form', require('./components/AutocompleteForm.vue'));
Vue.component('autocomplete-input', require('./components/AutocompleteInput.vue'));

const app = new Vue({
    el: '#app'
});
