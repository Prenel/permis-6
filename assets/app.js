import './styles/app.css';
import './bootstrap';
import { createApp } from 'vue';
import {  createVuetify } from 'vuetify';
import 'vuetify/styles'
import '@mdi/font/css/materialdesignicons.css'
import LoginPage from './views/LoginPage'
import CategoryListPage from './views/Category/CategoryListPage'
import Menu from './components/partial/Menu'
import * as components from 'vuetify/components'
import * as directives from 'vuetify/directives'

const vuetify = createVuetify({
    components,
    directives
});

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
if (document.getElementById('app-category')){
    // const elementLogin = document.getElementById('app-login');
    // const propsLogin = JSON.parse(elementLogin.getAttribute('data-login-props'));
    createApp(CategoryListPage).use(vuetify).mount('#app-category');
}