<template>
    <div class="wrap">
        <div class="new_post">
            <div class="avatar">
                <template v-if="authUser.picture">
                    <v-avatar>
                        <v-img v-if="authUser.picture" :src="authUser.picture" alt="profile picture" transition="scale-transition"></v-img>
                    </v-avatar>
                </template>
                <template v-else>
                    <v-avatar color="primary" class="headline white--text">
                     {{ authUser.fullname | initials }}
                    </v-avatar>
                </template>
            </div>
            <v-textarea v-model="post" solo rows="3" auto-grow clearable placeholder="What's on your mind" required v-validate="'required|min:1|max:1000'" :error-messages="errors.collect('post')" name="post"></v-textarea>
        </div>
        <div class="submit">
            <v-btn large dark class="primary" @click="submit" :loading="isLoading">Submit</v-btn>
        </div>
        <v-snackbar v-model="createFail" :timeout="4500" top color="red darken-1 white--text">
            Your post was not created. Please try again later.
            <v-btn text color="white--text" @click="createFail = false">Close</v-btn>
        </v-snackbar>
        <v-snackbar v-model="createSuccess" :timeout="4000" top color="green darken-1 white--text">
            Your post has been created.
            <v-btn text color="white--text" @click="createSuccess = false">Close</v-btn>
        </v-snackbar>
    </div>
</template>

<script>
export default {
    data() {
        return {
            post: '',
            isLoading: false,
            createFail: false,
            createSuccess: false,
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
        submit(){
             this.$validator.validateAll().then((isValid) => {
                if (isValid) {
                    if(this.post.trim() !== ''){
                        this.isLoading = true
                        axios.post(this.api + '/auth/save_post', {
                            post: this.post.trim()
                        }, this.authHeaders).then((res) => { //if posted successfully
                            this.post = ''
                            this.isLoading = false
                            // show success message and clear the form
                            this.createSuccess = true
                            this.$store.commit('newPostAdded', res.data)
                            this.$validator.pause()
                            this.$validator.fields.items.forEach(field => field.reset())
                            this.$validator.errors.clear()
                        }).catch(() => { //if post fails to save return a snackbar msg
                            this.isLoading = false
                            this.createFail = true
                        })
                    }
                }
             })
        }
    },
}
</script>

<style lang="scss" scoped>
    .wrap{
        display: flex;
        flex-direction: column;
        justify-content: space-around;
        align-items: center;
        // gap: 1.5rem;
        background: #fff;
        width: 100%;
        border: 1px solid rgb(239 239 239);
        border-radius: 6px;
        box-sizing: border-box;
        margin: 0 auto;
        padding: 16px 10px;
        margin-bottom: 2rem;
        .new_post{
            padding: 0px 8px;
            display: flex;
            min-height: 0;
            width: 100%;
            justify-content: space-around;
            gap: 1.5rem;
        }
    }
</style>
