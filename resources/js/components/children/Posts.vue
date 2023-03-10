<template>
    <div class="posts">
        <v-skeleton-loader type="list-item-avatar-three-line" v-if="isLoading" elevation="12" min-width="50" min-height="400"></v-skeleton-loader>
        <template v-else>
            <div v-for="(post, index) in posts" :key="post.id">
                <Post :post="post" :index="index" />
            </div>
        </template>
        <v-snackbar :value="postDeleted" :timeout="4000" top color="green darken-1 white--text">
            A post has been deleted.
            <v-btn text color="white--text" @click="postDeleted = false">Close</v-btn>
        </v-snackbar>
    </div>
</template>

<script>
export default {
    data() {
        return {
            posts: [],
            isLoading: false,
            cannotFetchPost: false
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
        newPost(){
            return this.$store.getters.newPost
        },
        postDeleted(){
            return this.$store.getters.postDeleted
        },
        deletedPost(){
            return this.$store.getters.deletedPost
        }
    },
    beforeRouteLeave (to, from, next) {
        this.$store.commit('resetPostAlerts')
        next()
    },
    watch: { //watch for change in NewPost
        newPost(newVal){
            this.posts.unshift(newVal)
        },
        deletedPost(newVal){
            if(newVal !== null){
                this.posts.splice(newVal, 1)
            }
        }
    },
    methods: {
        getPosts(){ //get all posts
            this.isLoading = true
            axios.get(this.api + '/get_posts').then((res) => { //get posts
                this.isLoading = false
                this.posts = res.data
            }).catch(() => { //if get post fails
                this.isLoading = false
                this.cannotFetchPost = true
            })
        }
    },
    mounted() {
        this.getPosts()
    },
}
</script>
