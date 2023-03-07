import Home from '../components/Home.vue'
import Login from '../components/Login.vue'
import Register from '../components/Register.vue'
import UserProfile from '../components/UserProfile.vue'
import ShowPost from '../components/ShowPost.vue'
import UpdatePost from '../components/UpdatePost.vue'


export default [
    // { path: '*', name: 'NotFound', component: NotFound },
    { path: '/', name: 'Home', component: Home },
    { path: '/login', name: 'Login', component: Login },
    { path: '/register', name: 'Register', component: Register },
    { path: '/user-profile/:id', name: 'UserProfile', component: UserProfile },
    { path: '/post/:id/update', name: 'UpdatePost', component: UpdatePost },
    { path: '/post/:id', name: 'ShowPost', component: ShowPost },
]
