<template>
    <v-container>
        <v-row justify="space-around" class="mt-10">
            <v-col cols="12" md="8">
                <NewPost v-if="userIsLoggedIn"/>
                <Posts />
            </v-col>
            <v-col cols="12" md="4">
                <FollowSiderbars />
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
export default {
    computed:{
        api(){
            return this.$store.getters.api
        },
        userIsLoggedIn(){
            return this.$store.getters.userIsLoggedIn
        },
    },
    // beforeRouteLeave (to, from, next) {
    //     this.$store.commit('resetPostAlerts')
    //     next()
    // },
}
</script>
