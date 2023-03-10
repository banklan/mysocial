<template>
    <div>
        <v-skeleton-loader type="card" v-if="isBusy" elevation="12" min-width="50" min-height="100"></v-skeleton-loader>
        <v-card v-else light min-height="100" elevation="12" raised class="mx-auto">
            <v-card-title class="primary white--text justify-center title">{{ user.first_name }}'s Friends</v-card-title>
            <v-card-text class="mt-5">
                <div v-if="followings.length > 0">
                    <div v-for="(user, index) in followings.slice(0, 5)" :key="user.id">
                        <p>
                            <router-link :to="{name:'UserProfile', params:{id: user.id}}">{{ user.fullname }}</router-link>
                        </p>
                        <v-divider v-if="index < followings.length - 1"></v-divider>
                    </div>
                </div>
                <div v-else>
                    <p>{{ user.first_name }} has no followers yet.</p>
                </div>
            </v-card-text>
        </v-card>
    </div>
</template>

<script>
export default {
    props: ['user'],
    data() {
        return {
            followings: [],
            isBusy: false,
        }
    },
    computed:{
        authUser(){
            return this.$store.getters.authUser
        },
        api(){
            return this.$store.getters.api
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
    methods: {
        getUserFollowings(){ //users this user is following
            this.isBusy = true
            axios.get(this.api + `/get_user_followings/${this.user.id}`, this.authHeaders)
            .then((res) => {
                this.isBusy = false
                this.followings = res.data
            })
        }
    },
    created() {
        this.getUserFollowings()
    },
}
</script>