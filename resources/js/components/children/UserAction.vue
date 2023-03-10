<template>
    <div class="wrap">
        <div class="name">
            {{ user.fullname }}
        </div>
        <div class="action">
            <v-btn dark class="primary" raised small rounded @click="follow">{{ user.isFolloing ? 'Follow' : 'Unfollow'}}</v-btn>
        </div>
    </div>
</template>

<script>
export default {
    props: ['user'],
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
                if(res.data.message == 'followed'){

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
    }
</style>
