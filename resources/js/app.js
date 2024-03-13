import "./bootstrap";
import Vue from "vue";
import VueRouter from "vue-router";
import App from "./components/App.vue";
import RegistrationForm from "./components/RegistrationForm.vue";
import Vuetify from "vuetify";
import VueMask from "v-mask";

import "vuetify/dist/vuetify.min.css";
import "@mdi/font/css/materialdesignicons.css";
window.Vue = Vue;

import Home from "./components/Home.vue";
Vue.use(VueRouter);
Vue.use(VueMask);
Vue.use(Vuetify);

const router = new VueRouter({
    mode: "history",
    routes: [
        {
            path: "/",
            name: "Home",
            component: Home,
        },
        {
            path: "/registration",
            name: "Registration",
            component: RegistrationForm,
        },
    ],
});

const app = new Vue({
    el: "#app",
    router,
    render: (h) => h(App),
    vuetify: new Vuetify(),
});
