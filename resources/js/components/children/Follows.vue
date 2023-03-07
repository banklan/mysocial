<template>
    <div class="stats_bar sidebar">
        <div class="subject">Your followers</div>
        <div>
            <div v-if="userIsLoggedIn">
                <div class="wrap mx-auto">
                    <div class="follow_list" v-for="user in followers" :key="user.id">
                        <SIngleUser />
                    </div>
                </div>
            </div>
            <div v-else>
                User is not logged in
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            follow: [],
            followers: [],
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
        getFollowers(){
            axios.get(this.api + '/auth/get_followers', this.authHeaders)
            .then((res) => {
                this.suggestions = res.data
                console.log(res.data)
            })
        }
    },
    created() {
        this.getFollowers()
    },
}
</script>


<style lang="scss" scoped>
    .stats_bar{
        .subject{
            text-align: center;
            font-size: 1rem;
            margin-bottom: 1.2rem;
        }
    }
    .wrap{
        // height: 20rem;
        width: 100%;
        margin: 0 auto;
        padding: 8px 5px ;
        // display: flex;
        // justify-content: space-around;
        .follow_list{
            display: flex;
            flex-direction: column;
            justify-content: space-around;
            align-items: space-around;
            height: 100%;
            width: 100%;
        }
    }
</style>
