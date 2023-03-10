<template>
    <div class="user align-center fill-height">
        <p><router-link to="{name: 'UserProfile', params:{id: user.id}}">{{ user.fullname }}</router-link></p>
        <div align="center" class="d-flex align-center flex-column fill-height">
            <p v-if="isFollowing">Followed</p>
            <v-btn v-else small rounded outlined color="primary" @click.prevent="follow" :loading="isBusy">Follow</v-btn>
        </div>
    </div>
</template>

<script>
export default {
    props: ['user'],
    data() {
        return {
            isBusy: false,
            isFollowing: false,
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
            this.isBusy = true
            axios.post(this.api + `/auth/follow_user/${this.user.id}`, {}, this.authHeaders)
            .then((res) => {
                this.isBusy = false
                this.isFollowing = true
            }).catch(() => {
                this.isBusy = false
            })
        }
    },
}
</script>

<style lang="scss" scoped>
    .user{
        height: 3rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0 5px;

        p{
            font-size: .95rem;
        }

        div p{
            font-size: .8rem;
            color: rgb(95, 95, 95);
        }
    }
</style>
