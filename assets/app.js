import './styles/app.css';
import './bootstrap';
import { createApp } from 'vue';
import { createVuetify } from 'vuetify';
import { createPinia } from 'pinia';
import 'vuetify/styles'
import '@mdi/font/css/materialdesignicons.css'
import LoginPage from './views/LoginPage'
import CategoryListPage from './views/Category/CategoryListPage'
import QuestionListPage from './views/Question/QuestionListPage'
import QuizzPage from './views/Quizz/QuizzPage'
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
    createApp(CategoryListPage)
        .use(vuetify)
        .use(createPinia())
        .mount('#app-category');
}

if (document.getElementById('app-question')){
    createApp(QuestionListPage)
        .use(vuetify)
        .use(createPinia())
        .mount('#app-question');
}

if (document.getElementById('app-quizz')){
    createApp(QuizzPage)
        .use(vuetify)
        .use(createPinia())
        .mount('#app-quizz');
}