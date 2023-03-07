<template>
    <v-container>
        <v-skeleton-loader type="article" v-if="isLoading" elevation="12" min-width="50" min-height="400"></v-skeleton-loader>
        <v-row justify="space-around" v-else class="mt-6">
            <v-col cols="12" md="4">
                <v-card light min-height="400" elevation="12" raised class="mx-auto">
                    <img src="/assets/images/profile1.jpg" alt="profile picture" height="350" width="100%">
                    <v-card-text class="mt-5">
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
                <v-card light min-height="100" elevation="12" raised class="mx-auto">
                    <v-card-title class="primary white--text justify-center title">{{ user.first_name }}'s Posts</v-card-title>
                    <v-card-text class="mt-5">
                        <div class="posts" v-if="posts.length > 0">
                            <div class="post" v-for="post in posts" :key="post.id">
                                <p>
                                    <router-link :to="{name:'ShowPost', params:{id: post.id}}">{{ post.content | truncate(45) }}</router-link>
                                </p>
                                <v-divider></v-divider>
                            </div>
                        </div>
                        <div class="mt-5" v-else>
                            <p>{{ user.first_name }} has no posts yet.</p>
                        </div>
                    </v-card-text>
                </v-card>
                <v-card light min-height="100" elevation="12" raised class="mx-auto mt-5">
                    <v-card-title class="primary white--text justify-center title">{{ user.first_name }}'s Friends</v-card-title>
                    <v-card-text class="mt-5">
                        <div v-if="follows.length > 0">
                            <div v-for="user in follows" :key="user.id">
                                <p>
                                    <router-link :to="{name:'UserProfile', params:{id: user.id}}">{{ user.fullname }}</router-link>
                                </p>
                                <v-divider></v-divider>
                            </div>
                        </div>
                        <div v-else>
                            <p>{{ user.first_name }} has no followers yet.</p>
                        </div>
                    </v-card-text>
                </v-card>
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
            follows: []
        }
    },
    watch:{
        '$route.params':{
            handler(newVal){
                let id = newVal
                this.getUser(id)
                this.getUserPosts(id)
                this.getUserFollows(id)
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
        getUser(){
            this.isLoading = true
            axios.get(this.api + `/get_user/${this.$route.params.id}`, this.authHeaders)
            .then((res) => {
                this.isLoading = false
                this.user = res.data
            })
        },
        getUserPosts(){
            axios.get(this.api + `/get_user_posts/${this.$route.params.id}`, this.authHeaders)
            .then((res) =>{
                this.posts = res.data
            })
        },
        getUserFollows(){
            axios.get(this.api + `/get_user_follows/${this.$route.params.id}`, this.authHeaders)
            .then((res) => {
                this.follows = res.data
            })
        }
    },
    created() {
        this.getUser()
        this.getUserPosts()
        this.getUserFollows()
    },
}
</script>
