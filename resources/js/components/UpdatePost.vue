<template>
    <v-container>
        <v-row class="mt-10">
            <v-col cols="12" md="3">
                <v-btn rounded color="primary_variant" dark elevation="4" left @click.prevent="$router.go(-1)"><v-icon left>arrow_left</v-icon> Back</v-btn>
            </v-col>
        </v-row>
        <v-row justify="center" class="mt-4">
            <v-col cols="12" md="6">
                <v-skeleton-loader type="article" v-if="isLoading" elevation="12" min-width="50" min-height="200" class="mt-8"></v-skeleton-loader>
                <template v-else>
                    <v-card v-if="post" light min-height="200" elevation="12" raised class="mx-auto">
                        <v-card-title class="subtitle-1 primary white--text justify-center">Update Post</v-card-title>
                        <v-card-text class="mt-5">
                            <v-textarea rows="3" auto-grow v-model="post" required v-validate="'required|min:1|max:1000'" :error-messages="errors.collect('content')" name="content"></v-textarea>
                        </v-card-text>
                        <v-card-actions class="justify-center pb-5">
                            <v-btn width="50%" large dark class="primary" :loading="isBusy" @click="submit">Submit</v-btn>
                        </v-card-actions>
                    </v-card>
                </template>
            </v-col>
        </v-row>
    </v-container>
</template>


<script>
export default {
    data() {
        return {
            isLoading: false,
            post: null,
            isBusy: false,
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
            if(this.post.user_id == this.authUser.id){
                return true
            }
            return false
        },
    },
    methods: {
        submit(){
            this.isBusy = true
            axios.post(this.api + `/auth/update_post/${this.$route.params.id}`, {
                post: this.post
            }, this.authHeaders)
            .then((res) =>{
                this.$router.push({name: 'ShowPost', params: {id: this.$route.params.id}})
                this.$store.commit('postUpdated')
            })
        },
        getPost(){
            this.isLoading = true
            axios.get(this.api + `/get_post/${this.$route.params.id}`)
            .then((res) => {
                this.isLoading = false
                this.post = res.data.content
            })
        },
    },
    mounted() {
        this.getPost()
    },
}
</script>
