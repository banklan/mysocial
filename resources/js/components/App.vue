<template>
    <v-app :style="{background: $vuetify.theme.themes.light.background}">
        <template v-if="userIsLoggedIn">
            <Navbar />
            <transition mode="out-in" enter-active-class="animate__animated animate__fadeIn" leave-active-class="animate__animated animate__fadeOut">
                <router-view></router-view>
            </transition>
        </template>
        <!-- <template v-if="adminIsLoggedIn"></template> -->
        <template v-if="!userIsLoggedIn">
            <Navbar />
            <transition mode="out-in" enter-active-class="animate__animated animate__fadeIn" leave-active-class="animate__animated animate__fadeOut">
                <router-view></router-view>
            </transition>
        </template>
        <!-- <template v-if="$route.path == '/'">

            <WelcomeNavbar />
            <transition mode="out-in" enter-active-class="animate__animated animate__fadeIn" leave-active-class="animate__animated animate__fadeOut">
                <router-view></router-view>
            </transition>
        </template>
        <template v-else> -->

        <!-- </template> -->
    </v-app>
</template>

<script>
import Navbar from './includes/Navbar'
// import WelcomeNavbar from './includes/WelcomeNavbar'
// import TestNavbar from './includes/TestNavbar'
// import AdminAuthNavbar from './includes/AdminAuthNavbar'
// import UserAuthNavbar from './includes/UserAuthNavbar'

export default {
    components: {
        Navbar
    },
    computed: {
        api(){
            return this.$store.getters.api
        },
        userIsLoggedIn(){
            return this.$store.getters.userIsLoggedIn
        },
        adminIsLoggedIn(){
            // return this.$store.getters.adminIsLoggedIn
        },
        adminHeaders(){
            let headers = {
                headers: {
                    // "Authorization": `Bearer ${this.authAdmin.token}`
                }
            }
            return headers
        },
        authAdmin(){
            // return this.$store.getters.authAdmin
        },
        authUser(){
            // return this.$store.getters.authUser
        },
        authHeaders(){
            let headers = {
                headers: {
                    "Authorization": `Bearer ${this.authUser.token}`
                }
            }
            return headers
        },
    },
}
</script>



