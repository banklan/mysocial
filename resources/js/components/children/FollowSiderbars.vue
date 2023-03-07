<template>
    <div class="follows">
        <v-card light raised elevation="12" class="mx-auto" min-height="20">
            <v-card-title class="primary white--text justify-center subtitle-1">Follows</v-card-title>
            <v-card-text class="mt-5">
                <template v-if="userIsLoggedIn">
                    <UsersAuthIsFollowing />
                    <SuggestedFollows />
                </template>
                <template v-else>
                    <p class="mt-5 mx-2"><router-link to="/login">Login</router-link> to create posts and follow other users.</p>
                </template>
            </v-card-text>
        </v-card>
        <v-card light raised elevation="12" class="mx-auto mt-8" min-height="20">
            <v-card-title class="primary white--text justify-center subtitle-1">Activities</v-card-title>
            <v-card-text class="mt-5">
                <p>To show the activities of user and friends...coming shortly</p>
            </v-card-text>
        </v-card>
    </div>
</template>


<script>
export default {
    data() {
        return {
            users: []
        }
    },
    computed:{
        api(){
            return this.$store.getters.api
        },
        userIsLoggedIn(){
            return this.$store.getters.userIsLoggedIn
        },
        authUser(){
            return this.$store.getters.authUser
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


