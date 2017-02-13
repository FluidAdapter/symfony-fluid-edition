import Vue from 'vue';

import VueResource from 'vue-resource'
Vue.use(VueResource);

import VueRouter from 'vue-router';
Vue.use(VueRouter);

import VeeValidate from 'vee-validate';
Vue.use(VeeValidate);

var VueI18n = require('vue-i18n');
Vue.use(VueI18n);
Vue.config.lang = 'de';
Vue.locale('en', {
    'Username could not be found.': 'foobar!'
});
Vue.locale('de', {
    'Login': 'Anmelden',
    'Register': 'Registrieren',
    'Username could not be found.': 'Login fehlgeschlagen, bitte kontrollieren Sie die Anmeldedaten.',
    'Don\'t remember your password?': 'Passwort vergessen?',
    'The password field is required.': 'Bitte geben Sie ihr Passwort ein!',
    'The username field is required.': 'Bitte geben Sie ihren Benutzernamen ein!',
    'The email field is required.': 'Sie müssen ihre E-Mail angeben!',
    'Please enter your email address. We will send you an email to reset your password.': 'Bitte geben Sie ihre E-Mail Adresse ein. <br />Wir senden Ihnen eine E-Mail zu um das Passwort zurückzusetzen.'
});

import Tabs from './Components/Tabs.vue';
Vue.component(Tabs.name, Tabs);

import Tab from './Components/Tab.vue';
Vue.component(Tab.name, Tab);

import Entrance from './Components/Entrance.vue';
Vue.component(Entrance.name, Entrance);

import EntranceNavigation from './Components/EntranceNavigation.vue';
Vue.component(EntranceNavigation.name, EntranceNavigation);

import EntranceHeader from './Components/EntranceHeader.vue';
Vue.component(EntranceHeader.name, EntranceHeader);

import EntranceIndexView from './Views/EntranceIndexView.vue';
Vue.component(EntranceIndexView.name, EntranceIndexView);

import EntranceRegisterView from './Views/EntranceRegisterView.vue';
Vue.component(EntranceRegisterView.name, EntranceRegisterView);

import EntranceForgotPasswordView from './Views/EntranceForgotPasswordView.vue';
Vue.component(EntranceForgotPasswordView.name, EntranceForgotPasswordView);

const router = new VueRouter({
    routes: [
        {
            path: '/',
            component: EntranceIndexView
        },
        {
            path: '/register',
            component: EntranceRegisterView
        },
        {
            path: '/forgot-password',
            component: EntranceForgotPasswordView
        }
        // {
        //     path: '/:category/compare/:products',
        //     component: ComparisonView,
        //     props: function (route) {
        //         var products = [];
        //         var productSlugs = route.params.products.split(',');
        //         for (var productSlugIndex in productSlugs) {
        //             products.push(state.products[productSlugs[productSlugIndex]]);
        //         }
        //         return {
        //             category: state.categories[route.params.category],
        //             products: products
        //         };
        //     }
        // },
    ]
});

new Vue({
    router
}).$mount('.entrance-app');