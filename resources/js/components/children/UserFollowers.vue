<template>
    <div class="stats_bar sidebar">
        <div class="subject">Users following me</div>
        <div class="wrap">
            <div class="mx-auto" max-width="300" tile>
                <v-list rounded dense nav>
                    <v-list-item-group color="primary">
                        <v-list-item v-for="(user, i) in follows" :key="i">
                            <v-list-item-content>
                                <v-list-item-title>{{ user.fullname }}</v-list-item-title>
                            </v-list-item-content>
                        </v-list-item>
                    </v-list-item-group>
                </v-list>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            follows: [],
            isLoading: false,
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
        getFriendsUserFollow(){
            this.isLoading = true
            axios.get(this.api + '/auth/get_friends_following_user', this.authHeaders)
            .then((res) => {
                this.isLoading = false
                this.follows = res.data
            })
        }
    },
    created() {
        this.getFriendsUserFollow()
    },
}
</script>


<style lang="scss" scoped>
    .stats_bar{
        .subject{
            text-align: center;
            font-size: .85rem;
            margin-bottom: .6rem;
            margin-top: .9rem;
        }
    }
    .wrap{
        width: 100%;
        margin: 0 auto;
        padding: 5px 5px;
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

</template>
