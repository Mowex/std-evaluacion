// require('./bootstrap');

import Vue from "vue";
import Vuelidate from 'vuelidate'
import router from "./router";
import store from "./store";
import App from "./views/App";
import axios from 'axios'

// If you don't need the styles, do not connect
// import 'sweetalert2/dist/sweetalert2.min.css';


Vue.use(Vuelidate)
Vue.component('Navbar', require('./components/partials/Navbar').default);
Vue.component('Aside', require('./components/partials/Aside').default);
Vue.component('Dashboard', require('./views/Dashboard').default);

// interceptor token
axios.interceptors.request.use( (config) => {
    let token = store.state.token || null
    if (token) {
        config.headers.Authorization = `Bearer ${token}`
    }
    return config;
}, function (error) {
    return Promise.reject(error);
});

router.beforeEach((to, from, next) => {
    if (to.matched.some(record => record.meta.requiresAuth)) {
        if (!store.getters.loggedIn) {
            next({
                name: 'login',
            })
        } else {
            next()
        }
    } else if (to.matched.some(record => record.meta.requiresOffAuth)){
        if (store.getters.loggedIn) {
            next({
                name: 'dashboard.index',
            })
        } else {
            next()
        }
    } else {
        next()
    }
})

const app = new Vue({
    el: '#app',
    components: {
        App
    },
    router,
    store,
    axios
});
