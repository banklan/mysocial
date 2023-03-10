<template>
    <v-container>
        <v-skeleton-loader type="article" v-if="isLoading" elevation="12" min-width="50" min-height="400"></v-skeleton-loader>
        <v-row justify="space-around" v-else class="mt-6">
            <v-col cols="12" md="5">
                <v-card light min-height="400" elevation="12" raised class="mx-auto">
                    <v-img v-if="user.picture" :src="user.picture" alt="profile picture" max-height="500" transition="scale-transition"></v-img>
                    <v-img v-else src="/assets/shared/no-image.png" width="100%" height="450" transition="scale-transition"></v-img>
                    <v-card-text class="mt-5 subtitle-1">
                        <table class="table">
                            <thead></thead>
                            <tbody>
                                <tr>
                                    <th width="30%" style="border-top:none">Name</th>
                                    <td style="border-top:none">{{ user.fullname }}</td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td>{{ user.email }}</td>
                                </tr>
                                <tr>
                                    <th>Phone</th>
                                    <td>{{ user.phone }}</td>
                                </tr>
                                <tr>
                                    <th>Date Joined</th>
                                    <td>{{ user.created_at | moment('Do MMM, YYYY') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </v-card-text>
                </v-card>
            </v-col>
            <v-col cols="12" md="5">
                <UserFollowings :user="user" />
                <UserPosts :user="user" />
            </v-col>
        </v-row>
    </v-container>
</template>


<script>
export default {
    data() {
        return {
            user: null,
            posts: [],
            isLoading: false,
            follows: [],
            followings: []
        }
    },
    watch:{
        '$route.params':{
            handler(newVal){
                let id = newVal
                this.getUser(id)
                this.getUserPosts(id)
                this.getUserFollows(id)
                this.getUserFollowings(id)
            },
            immediate: true,
            deep: true
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
                    "Authorization": `Bearer ${this.authUser && this.authUser.token}`
                }
            }
            return headers
        },
    },
    methods: {
        getUser(){ //get user
            this.isLoading = true
            axios.get(this.api + `/get_user/${this.$route.params.id}`, this.authHeaders)
            .then((res) => {
                this.isLoading = false
                this.user = res.data
            })
        },
        getUserPosts(){ //post of this user
            axios.get(this.api + `/get_user_posts/${this.$route.params.id}`, this.authHeaders)
            .then((res) =>{
                this.posts = res.data
            })
        },
        getUserFollows(){ //users following this usee
            axios.get(this.api + `/get_user_follows/${this.$route.params.id}`, this.authHeaders)
            .then((res) => {
                this.follows = res.data
            })
        },
        getUserFollowings(){ //users this user is following
            axios.get(this.api + `/get_user_followings/${this.$route.params.id}`, this.authHeaders)
            .then((res) => {
                this.followings = res.data
            })
        }
    },
    created() {
        this.getUser()
        this.getUserPosts()
        this.getUserFollows()
        this.getUserFollowings()
    },
}
</script>
