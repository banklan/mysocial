<template>
    <div class="wrap">
        <v-progress-circular indeterminate color="primary" :width="4" :size="20" v-if="isLoading" justify="center" class="mx-auto"></v-progress-circular>
        <template v-else>
            <div class="sub-title mb-3">Users You are following</div>
            <template v-if="users.length > 0">
                <div v-if="viewLess">
                    <div v-for="(user, index) in users.slice(0, 5)" :key="index">
                        <UserAuthIsFollowing :user="user" :index="index" />
                    </div>
                </div>
                <div v-else>
                    <div v-for="(user, index) in users" :key="index">
                        <UserAuthIsFollowing :user="user" :index="index" />
                    </div>
                </div>
                <div class="text-center my-3" v-if="users.length > 5">
                    <v-btn dark class="primary" @click="viewLess = !viewLess">{{ viewLess ? 'View More' : 'View Less' }}</v-btn>
                </div>
            </template>
            <template v-else>
                <div>
                    You are not following any users at the moment
                </div>
            </template>
            <v-divider></v-divider>
        </template>
    </div>
</template>

<script>
export default {
    data() {
        return {
            users: [],
            isLoading: false,
            viewLess: true
        }
    },
    computed:{
        api(){
            return this.$store.getters.api
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
        userUnfollowed(){
            return this.$store.getters.UserWasUnfollowed
        },
        followedUser(){
            return this.$store.getters.followedUser
        }
    },
    watch:{
        'userUnfollowed':{
             handler(newVal){
                if(newVal == true){
                    let user = this.$store.getters.unfollowedUserIndex
                    this.users.splice(user, 1)
                    this.$store.commit('resetFollow')
                }
             }
        },
        'followedUser':{
            handler(newVal){
                if(newVal != null){
                    if(this.users.length < 5){
                        this.users.unshift(newVal)
                    }
                }
            }
        }
    },
    methods: {
        getUsers(){ //get users
            this.isLoading = true
            axios.get(this.api + '/auth/users_auth_is_following', this.authHeaders)
            .then((res) => {
                this.isLoading = false
                this.users = res.data
            })
        }
    },
    created() {
        this.getUsers()
    },
}
</script>
