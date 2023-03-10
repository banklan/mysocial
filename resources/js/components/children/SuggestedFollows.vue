<template>
    <div class="wrap">
        <v-progress-circular indeterminate color="primary" :width="4" :size="20" v-if="isLoading" justify="center" class="mx-auto"></v-progress-circular>
        <template v-else>
            <div class="sub-title mb-3">These users may interest you</div>
            <template v-if="users.length > 0">
                <div v-for="(user, index) in users" :key="index">
                    <SuggestedFollow :user="user" :index="index" />
                </div>
            </template>
            <template v-else>
                <div>
                    <p class="py-3">There are no suggested follows at the moment</p>
                </div>
            </template>
            <v-divider v-if="users.length > 0"></v-divider>
        </template>
    </div>
</template>

<script>
export default {
    data() {
        return {
            users: [],
            isLoading: false,
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
        UserWasFollowed(){
            return this.$store.getters.UserWasFollowed
        },
        unfollowedUser(){
            return this.$store.getters.unfollowedUser
        }
    },
    watch:{
        'UserWasFollowed':{
             handler(newVal){
                if(newVal == true){
                    let user = this.$store.getters.followedUser
                    this.users.splice(user, 1)
                    this.$store.commit('resetFollow')
                }
             }
        },
        'unfollowedUser':{
            handler(newVal){
                if(newVal !== null){
                    if(this.users.length < 10){
                        this.users.unshift(newVal)
                    }
                }
            }
        }
    },
    methods: {
        getUsers(){
            this.isLoading = true
            axios.get(this.api + '/auth/suggested_follows', this.authHeaders)
            .then((res) => {
                this.isLoading = false
                this.users = res.data.splice(0, 10)
            })
        }
    },
    created() {
        this.getUsers()
    },
}
</script>
