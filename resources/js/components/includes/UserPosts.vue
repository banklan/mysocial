<template> 
     <div>
        <v-skeleton-loader type="card" v-if="isBusy" elevation="12" min-width="50" min-height="100"></v-skeleton-loader>
        <v-card v-else light min-height="100" elevation="12" raised class="mx-auto mt-6">
            <v-card-title class="primary white--text justify-center title">{{ user.first_name }}'s Posts</v-card-title>
            <v-card-text class="mt-5">
                <div class="posts" v-if="posts.length > 0">
                    <div class="post" v-for="(post, index) in posts" :key="post.id">
                        <p>
                            <router-link :to="{name:'ShowPost', params:{id: post.id}}">{{ post.content | truncate(45) }}</router-link>
                        </p>
                        <v-divider v-if="index < posts.length - 1"></v-divider>
                    </div>
                </div>
                <div class="mt-5" v-else>
                    <p>{{ user.first_name }} has no posts yet.</p>
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
            posts: [],
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
        getUserPosts(){ //post of this user
            this.isBusy = true
            axios.get(this.api + `/get_user_posts/${this.user.id}`, this.authHeaders)
            .then((res) =>{
                this.isBusy = false
                this.posts = res.data
            })
        },
    },
    created() {
        this.getUserPosts()
    },
}
</script>