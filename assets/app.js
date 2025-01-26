/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';

// start the Stimulus application
import './bootstrap';
import { createApp } from 'vue';
import {  createVuetify } from 'vuetify';
import 'vuetify/styles'
import '@mdi/font/css/materialdesignicons.css'
import LoginPage from './views/LoginPage.vue'

const vuetify = createVuetify();

if (document.getElementById('app-login')){
    const element = document.getElementById('app-login');
    const props = JSON.parse(element.getAttribute('data-props'));
    createApp(LoginPage, props).use(vuetify).mount('#app-login');
} 
