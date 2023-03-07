/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

import App from './components/App'

import vuetify from './plugins/vuetify';

import VueRouter from 'vue-router';

import { store } from './store/index';

import './filters';

import VeeValidate from 'vee-validate';

import Moment from 'vue-moment'


import Routes from './router/routes';

import 'animate.css'

import 'material-design-icons-iconfont/dist/material-design-icons.css'
import '@mdi/font/css/materialdesignicons.css'



Vue.use(VueRouter)
Vue.use(VeeValidate)
Vue.use(Moment)

const router = new VueRouter({
    routes: Routes,
    mode: 'history',
    linkActiveClass: 'font-semibold'
})


// guard for auth user so they are not routed to login and register
router.beforeEach((to, from, next) => {
    const requiresAuth = to.matched.some(rec => rec.meta.requiresAuth);
    const authUser = store.state.authUser
    if (requiresAuth && authUser == null) {
        store.commit('redirectOnLogin', to.path)
        next('/login')
    }else if(to.path == '/login' && authUser){
        next('/')
    }else if(to.path == '/register' && authUser){
        next('/')
    }else {
        next()
    }
});


// axios interceptor for expired token
// axios.interceptors.response.use(null, (err) =>
// {
//     if (err.response.status == 401) {
//         store.commit('logOutAuthUser')
//         // store.commit('logOutAdmin')
//         if (router.currentRoute.name !== 'Login') {
//             router.push('/')
//         }
//         router.push('/')
//     }
//     return Promise.reject(err)
// })


//Rgister vue components here
Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('IndexSidebar', require('./components/children/IndexSidebar.vue').default);
// Vue.component('IndexStatsBar', require('./components/children/IndexStatsBar.vue').default);
Vue.component('NewPost', require('./components/children/NewPost.vue').default);
Vue.component('Posts', require('./components/children/Posts.vue').default);
Vue.component('Post', require('./components/children/Post.vue').default);
// Vue.component('SuggestedUser', require('./components/children/SuggestedUser.vue').default);
// Vue.component('Follows', require('./components/children/Follows.vue').default);
// Vue.component('SIngleUser', require('./components/children/SIngleUser.vue').default);
// Vue.component('UserFollowers', require('./components/children/UserFollowers.vue').default);
// Vue.component('FriendsUserFollow', require('./components/children/FriendsUserFollow.vue').default);
// Vue.component('Friends', require('./components/children/Friends.vue').default);
Vue.component('UserFollowers', require('./components/children/UserFollowers.vue').default);
Vue.component('FollowSiderbars', require('./components/children/FollowSiderbars.vue').default);
// Vue.component('UserAction', require('./components/children/UserAction.vue').default);
Vue.component('UsersAuthIsFollowing', require('./components/children/UsersAuthIsFollowing.vue').default);
Vue.component('UserAuthIsFollowing', require('./components/children/UserAuthIsFollowing.vue').default);
Vue.component('SuggestedFollows', require('./components/children/SuggestedFollows.vue').default);
Vue.component('SuggestedFollow', require('./components/children/SuggestedFollow.vue').default);



/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
// var corsOptions = {
//     origin: "http://127.0.0.1:8000"
//   };

// app.use(cors(corsOptions));

const app = new Vue({
    el: '#app',
    components: { App },
    router,
    vuetify,
    store
});
