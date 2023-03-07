<template>
    <div class="post">
        <div class="post_wrap">
            <div class="post_content" @click="showPost">
                <div class="post_author">
                    <div class="avatar">
                        <v-avatar>
                            <img src="https://cdn.vuetifyjs.com/images/john.jpg" alt="John">
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
                            <small v-if="post.likes.length > 0" class="likes_count">{{ post.likes.length }}</small>
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
                        <small v-if="post.likes.length > 0" class="likes_count">{{ post.likes.length }}</small>
                    </template>
                </div>
                <div class="edit" v-if="isAuthor">
                    <v-btn small icon color="transparent">
                        <i class="uil uil-edit-alt"></i>
                    </v-btn>
                </div>
                <div class="delete" v-if="isAuthor">
                    <v-btn small icon color="transparent">
                        <i class="uil uil-trash-alt"></i>
                    </v-btn>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['post'],
    data() {
        return {
            miniContent: true,
            isLiked: false
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
        isAuthor(){
            if(this.post.user_id === this.authUser && this.authUser.id){
                return true
            }
            return false
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
            console.log(res)
        },
        checkIfLiked(){
            if(this.userIsLoggedIn){
                axios.get(this.api + `/auth/check_if_post_is_liked/${this.post.id}`, this.authHeaders)
                .then((res) => {
                    console.log(res.data)
                    if(res.data.message === 1){
                        this.isLiked = true
                    }else{
                        this.isLiked = false
                    }
                })
            }
        },
        showPost(){
            this.$router.push({name: 'ShowPost', params: {id: this.post.id}})
        }
    },
    created() {
        this.checkIfLiked()
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
                gap: .6rem;
                padding: 12px 8px 5px;
                cursor: pointer;
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
</style>
