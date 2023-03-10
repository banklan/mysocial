<template>
    <div class="post">
        <div class="post_wrap">
            <div class="post_content" @click="showPost">
                <div class="post_author">
                    <div class="avatar">
                        <v-avatar v-if="post.user && post.user.picture">
                            <v-img :src="post.user && post.user.picture" alt="profile picture" transition="scale-transition"></v-img>
                        </v-avatar>
                        <v-avatar v-else color="primary" class="title white--text">
                            {{ post.user && post.user.fullname | initials }}
                        </v-avatar>
                    </div>
                </div>
                <div class="content">
                    <div class="user">
                        <div class="name"><router-link :to="{name: 'UserProfile', params: {id: post.user.id}}">{{ post.user && post.user.fullname }}</router-link></div>
                        <div class="published">{{ post.created_at | moment('Do MMM, YYYY @ HH:mma')}}</div>
                    </div>
                    <div class="body">
                        <p v-if="miniContent">{{ post.content | truncate(200) }} <span v-if="post.content && post.content.length > 200" style="text-style: italics; color: blue"><a class="more" @click="expandContent">...More</a></span></p>
                        <p v-else>{{ post.content }}</p>
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
                            <small v-if="post.likes.length > 0" class="likes_count">{{ post.likes.length }}</small>
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
        <v-dialog v-model="confirmDelDialogue" max-width="480">
            <v-card min-height="150" class="mx-auto">
                <v-card-title class="subtitle-1 primary white--text justify-center">Delete Post</v-card-title>
                <v-card-text class="mt-5 subtitle-1">
                    Are you sure you want to delete this post?
                </v-card-text>
                <v-card-actions class="pb-8 mt-2 justify-space-around">
                    <v-btn text color="red darken--2" @click="confirmDelDialogue = false" width="40%">Cancel</v-btn>
                    <v-btn dark color="primary" :loading="isDeleting" @click="delPost" width="40%">Yes, Delete</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </div>
</template>

<script>
export default {
    props: ['post', 'index'],
    data() {
        return {
            miniContent: true,
            isLiked: false,
            confirmDelDialogue: false,
            isDeleting: false,
            isAuthor: false,
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
    methods: {
        expandContent(){
            this.miniContent = !this.miniContent
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
            let res = []
            const status = this.post.likes.find(x => x.user_id === this.authUser && this.authUser.id)
            this.res.push(status)
        },
        checkIfLiked(){ //check if auth user has liked this post
            if(this.userIsLoggedIn){
                axios.get(this.api + `/auth/check_if_post_is_liked/${this.post.id}`, this.authHeaders)
                .then((res) => {
                    if(res.data.message === 1){
                        this.isLiked = true
                    }else{
                        this.isLiked = false
                    }
                })
            }
        },
        showPost(){ //route to show post
            this.$router.push({name: 'ShowPost', params: {id: this.post.id}})
        },
        checkIfAuthor(){
            if(this.post.user_id === this.authUser.id){
                this.isAuthor = true
            }
        },
        delPost(){ //delete a post
            this.isDeleting = true
            axios.post(this.api + `/auth/delete_post/${this.post.id}`, {}, this.authHeaders)
            .then((res) => {
                this.isDeleting = false
                this.$store.commit('delPost', this.index)
            })
        }
    },
    created() {
        this.checkIfLiked()
        this.checkIfAuthor()
    },
}
</script>

<style lang="scss" scoped>
    .post{
        background: #fff;
        padding: 15px 12px;
        margin: 1rem .8rem;
        border: 1px solid #e9e9e9;
        border-radius: 4px;

        .post_wrap{
            display: flex;
            flex-direction: column;
            justify-content: space-around;
            align-items: flex-start;

            .post_content{
                display: flex;
                justify-content: space-around;
                gap: .8rem;
                padding: 12px 8px 5px;
                cursor: pointer;
                overflow: hidden;

                .content{
                    display: flex;
                    flex-direction: column;
                    gap: 3px;

                    .user{
                        .name{
                            font-size: 1.1rem;
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

                        p{
                            font-size: 1.1rem !important;
                            line-height: 1.6 !important;
                        }
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
                    font-size: 1.1rem;
                    color: #004EFF;
                }

                .likes .v-btn{
                    transition: all .3s ease;

                    &:hover{
                        scale:calc(1.15);
                    }
                    &:active{
                        transform: translateY(6%);
                    }
                }
            }
        }
    }
    .likes_count{
        font-size: .9rem;
        color: #3371FF;
    }
    .delete .v-btn i{
        color: #ef0d0d !important;
    }
</style>
