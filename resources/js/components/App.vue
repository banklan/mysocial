<template>
    <v-app :style="{background: $vuetify.theme.themes.light.background}">
        <template v-if="userIsLoggedIn">
            <Navbar />
            <transition mode="out-in" enter-active-class="animate__animated animate__fadeIn" leave-active-class="animate__animated animate__fadeOut">
                <router-view></router-view>
            </transition>
        </template>
        <template v-if="!userIsLoggedIn">
            <Navbar />
            <transition mode="out-in" enter-active-class="animate__animated animate__fadeIn" leave-active-class="animate__animated animate__fadeOut">
                <router-view></router-view>
            </transition>
        </template>
    </v-app>
</template>

<script>
import Navbar from './includes/Navbar'

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
    },
}
</script>



