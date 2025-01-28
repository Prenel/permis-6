import './styles/app.css';
import './bootstrap';
import { createApp } from 'vue';
import {  createVuetify } from 'vuetify';
import 'vuetify/styles'
import '@mdi/font/css/materialdesignicons.css'
import LoginPage from './views/LoginPage.vue'
import Menu from './components/partial/Menu'
const vuetify = createVuetify();

if (document.getElementById('app-login')){
    const elementLogin = document.getElementById('app-login');
    const propsLogin = JSON.parse(elementLogin.getAttribute('data-login-props'));
    createApp(LoginPage, propsLogin).use(vuetify).mount('#app-login');
}
if (document.getElementById('app-menu')){
    const elementMenu = document.getElementById('app-menu');
    const propsMenu = JSON.parse(elementMenu.getAttribute('data-menu-props'));
    const propsSubMenu = JSON.parse(elementMenu.getAttribute('data-sub-menu-props'));
    createApp(Menu, { 
        menuData:propsMenu,
        subMenuData: propsSubMenu,
    }).use(vuetify).mount('#app-menu');
}
