<template>
    <div class="wrap">
        <div class="name">
            <router-link :to="{name: 'UserProfile', params:{id: user.id}}">{{ user.fullname }}</router-link>
        </div>
        <div class="action">
            <v-btn dark class="primary" raised small rounded @click="follow">Follow</v-btn>
        </div>
    </div>
</template>

<script>
export default {
    props: ['user', 'index'],
    data() {
        return {
            users: []
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
        follow(){
            axios.post(this.api + `/auth/follow/${this.user.id}`, {}, this.authHeaders)
            .then((res) => {
                // console.log(res.data)
                if(res.data.message == 'success'){
                    this.$store.commit('UserWasFollowed')
                    this.$store.commit('followedUserIndex', this.index)
                    this.$store.commit('followedUser', this.user)
                }
            })
        }
    }
}
</script>



<style lang="scss" scoped>
    .wrap{
        width: 100%;
        margin: 0 auto;
        padding: 5px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
        
        .name a{
            font-size: 1rem;
        }  
    }
</style>
