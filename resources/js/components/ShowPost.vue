<template>
    <v-container>
        <v-row class="mt-10">
            <v-col cols="12" md="3">
                <v-btn rounded color="primary_variant" dark elevation="4" left @click.prevent="$router.go(-1)"><v-icon left>arrow_left</v-icon> Back</v-btn>
            </v-col>
        </v-row>
        <v-row justify="space-around" class="mt-4">
            <v-col cols="12" md="8">
                <v-skeleton-loader type="article" v-if="isLoading" elevation="12" min-width="50" min-height="200" class="mt-8"></v-skeleton-loader>
                <template v-else>
                    <v-card v-if="post" light min-height="200" elevation="12" raised class="mx-auto">
                        <v-card-text class="mt-5">
                            <div class="post_wrap">
                                <div class="post_content">
                                    <div class="post_author">
                                        <div class="avatar">
                                            <v-avatar>
                                                <img src="https://cdn.vuetifyjs.com/images/john.jpg" alt="John">
                                            </v-avatar>
                                        </div>
                                    </div>
                                    <div class="content">
                                        <div class="user">
                                            <div class="name"><router-link :to="{name: 'UserProfile', params: {id: post.user && post.user_id}}">{{ post.user && post.user.fullname }}</router-link></div>
                                            <div class="published">{{ post.created_at | moment('Do MMM, YYYY @ HH:mm')}}</div>
                                        </div>
                                        <div class="body">
                                            <p>{{ post.content }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="post_reactions">
                                    <div class="likes">
                                        <template v-if="userIsLoggedIn">
                                            <template v-if="isAuthor">
                                                <i class="uil uil-heart-alt"></i>
                                                <small v-if="post.likes && post.likes.length > 0" class="likes_count">{{ post.likes && post.likes.length }}</small>
                                            </template>
                                            <template v-else>
                                                <v-btn v-if="isLiked" small icon color="transparent" @click="likePost">
                                                    <v-icon>mdi-heart</v-icon>
                                                </v-btn>
                                                <v-btn v-else small icon color="transparent" @click="likePost">
                                                    <i class="uil uil-heart-alt"></i>
                                                </v-btn>
                                                <small v-if="post.likes && post.likes.length > 0" class="likes_count">{{ post.likes && post.likes.length }}</small>
                                            </template>
                                        </template>
                                        <template v-else>
                                            <i class="uil uil-heart-alt"></i>
                                            <small v-if="post.likes && post.likes.length > 0" class="likes_count">{{ post.likes && post.likes.length }}</small>
                                        </template>
                                    </div>
                                    <div class="edit" v-if="isAuthor">
                                        <v-btn small icon color="transparent" :to="{name: 'UpdatePost', params: {id: post.id}}">
                                            <i class="uil uil-edit-alt"></i>
                                        </v-btn>
                                    </div>
                                    <div class="delete" v-if="isAuthor">
                                        <v-btn small icon color="transparent" @click="confirmDelDialogue = true">
                                            <i class="uil uil-trash-alt"></i>
                                        </v-btn>
                                    </div>
                                </div>
                            </div>
                        </v-card-text>
                    </v-card>
                </template>
            </v-col>
            <v-col cols="12" md="4">
                <v-skeleton-loader type="article" v-if="isLoading" elevation="12" min-width="50" min-height="200"></v-skeleton-loader>
                <v-card v-else light min-height="100" elevation="12" raised class="mx-auto mt-5">
                    <v-card-title class="primary white--text justify-center subtitle-1">Other posts by {{ post.user && post.user.fullname }} <v-chip color="primary_variant white--text" class="ml-1">{{ otherPosts.length }}</v-chip></v-card-title>
                    <v-card-text class="mt-5">
                        <div class="other_posts">
                            <template v-if="otherPosts.length > 0">
                                <div class="other" v-for="post in otherPosts" :key="post.id">
                                    <p><router-link :to="{name: 'ShowPost', params:{id: post.id}}">{{ post.content | truncate(45) }}</router-link></p>
                                    <v-divider></v-divider>
                                </div>
                            </template>
                            <template v-else>
                                <div class="mt-5">
                                    No other post(s) exists for this user.
                                </div>
                            </template>
                        </div>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
        <v-dialog v-model="confirmDelDialogue" max-width="480">
            <v-card min-height="150">
                <v-card-title class="subtitle-1 primary white--text justify-center">Delete Post</v-card-title>
                <v-card-text class="mt-5 body_text">
                    Are you sure you want to delete this post?
                </v-card-text>
                <v-card-actions class="pb-8 mt-2 justify-center">
                    <v-btn text color="red darken--2" @click="confirmDelDialogue = false" width="40%">Cancel</v-btn>
                    <v-btn dark color="primary" :loading="isDeleting" @click="delPost" width="40%">Clear</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-snackbar v-model="postUpdated" :timeout="4000" top color="green darken-1 white--text">
            The post has been updated.
            <v-btn text color="white--text" @click="postUpdated = false">Close</v-btn>
        </v-snackbar>
    </v-container>
</template>

<script>
export default {
    data() {
        return {
            isLoading: false,
            id: this.$route.params.id,
            post: null,
            isLiked: false,
            otherPosts: [],
            confirmDelDialogue: false,
            isDeleting: false,
        }
    },
    computed:{
        api(){
            return this.$store.getters.api
        },
        authUser(){
            return this.$store.getters.authUser
        },
        userIsLoggedIn(){
            return this.$store.getters.userIsLoggedIn
        },
        authHeaders(){
            let headers = {
                headers: {
                    "Authorization": `Bearer ${this.authUser && this.authUser.token}`
                }
            }
            return headers
        },
        isAuthor(){
            if(this.post.user_id == this.authUser && this.authUser.id){
                return true
            }
            return false
        },
        postUpdated(){
            return this.$store.getters.postUpdated
        },
    },
    watch:{
        '$route.params':{
            handler(newVal){
                let id = newVal
                this.getPost(id)
                this.getOtherPosts(id)
            },
            immediate: true,
            deep: true
        }
    },
    methods: {
        getPost(){
            this.isLoading = true
            axios.get(this.api + `/get_post/${this.$route.params.id}`)
            .then((res) => {
                this.isLoading = false
                this.post = res.data
            })
        },
        likePost(){
            axios.post(this.api + `/auth/like_post/${this.post.id}`, {}, this.authHeaders)
            .then((res) => {
                if(res.data.message === 'liked'){
                    this.isLiked = true
                    this.post.likes.length++
                }else{
                    this.isLiked = false
                    this.post.likes.length--
                }
            })
        },
        likeStatus(){
            const status = this.post.likes.some(x => x.user_id === this.authUser && this.authUser.id)
            this.isLiked = status
        },
        getOtherPosts(){
            axios.get(this.api + `/get_other_posts/${this.$route.params.id}`)
            .then((res) => {
                this.otherPosts = res.data
            })
        },
        delPost(){
            this.isDeleting = true
            axios.post(this.api + `/auth/delete_post/${this.$route.params.id}`, {}, this.authHeaders)
            .then((res) => {
                this.isDeleting = false
                // this.$store.commit('postDeleted')
                // this.$router.push('/')
            })
        }
    },
    created() {
        this.getPost()
        this.getOtherPosts()
    },
}
</script>

<style lang="scss" scoped>
.post_wrap{
    display: flex;
    flex-direction: column;
    justify-content: space-around;
    align-items: flex-start;

    .post_content{
        display: flex;
        justify-content: space-around;
        gap: .6rem;
        padding: 12px 8px 5px;
        overflow: hidden;

        .content{
            display: flex;
            flex-direction: column;
            gap: 3px;

            .user{
                .name{
                    font-size: 1rem;
                    color: #004EFF;
                }
                .published{
                    font-size: .8rem;
                    text-decoration: italic;
                }
            }

            .body{
                margin-top: 6px;
                cursor: pointer;
            }
        }
    }

    .post_reactions{
        display: flex;
        justify-content: space-around;
        align-items: center;
        padding: 6px 15px 10px;
        // height: 2rem;
        width: 60%;
        margin-top: -8px;

        i{
            font-size: 1rem;
            color: #004EFF;
        }

        .likes .v-btn{
            transition: all .4s ease;

            &:hover{
                scale:calc(1.15);
            }
            &:active{
                transform: translateY(10%);
            }
        }
    }
}
.v-card__text{
    color: rgb(44, 44, 44) !important;
}
.other_posts p{
    font-size: .9rem;
}
.likes_count{
    font-size: .9rem;
    color: #3371FF;
}
</style>
