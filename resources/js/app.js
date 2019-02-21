
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
require('chart.js');

window.Vue = require('vue');
window.swal = require('sweetalert');
window.moment = require('moment');
window.toastr = require('toastr');


/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

// Vue.component('example-component', require('./components/ExampleComponent.vue').default);
import Reports from './components/Reports.vue';
import Dashboard from './components/Dashboard.vue';
import AddReport from './components/AddReport.vue';
import VueRouter from 'vue-router';
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
Vue.use(VueRouter);
const router = new VueRouter({
    routes: [
        { path: '/', component: Dashboard, props: true },
        { path: '/p/:type', component: Reports, props: true },
        { path: '/addReport', component: AddReport, props: true },
    ]
});

Vue.filter('standard_date',(value) => {
    return moment(value).format('D MMM, YYYY h:mm A');
});

Vue.filter('url',(value) => {
    return __root_url+"/"+value;
});
const app = new Vue({
    router,
    el: '#app',
    data(){
        return {
            current_page: "Reports",
        }
    },
    mounted(){
        let self = this;
        $(".navbar-page-name").removeClass("hidden");
        toastr.options = {
            "closeButton": true,
            "positionClass": "toast-bottom-right",
            "showMethod": "slideDown",
            "hideMethod": "fadeOut",
            "newestOnTop": false,
            "progressBar": true
        };
        $(".tg-menu .list-group-item[href='"+self.$route.path+"']").addClass("active");
        self.current_page = $(".tg-menu .list-group-item[href='"+self.$route.path+"']").attr("data-name");
        if(self.current_page == 'Dashboard'){
            $("#nav-add-report").addClass("d-none");
        } else {
            $("#nav-add-report").removeClass("d-none");
        }
        $(".tg-menu .list-group-item").on("click",(event) => {
            event.preventDefault();
            let url =  $(event.target).attr("href");
            let name = $(event.target).attr("data-name");
            $(".tg-menu .list-group-item").removeClass("active");
            $(event.target).addClass("active");
            self.current_page = name;
            if(self.current_page == 'Dashboard'){
                $("#nav-add-report").addClass("d-none");
            } else {
                $("#nav-add-report").removeClass("d-none");
            }
            router.push({ path: url });
        })
    }
});
