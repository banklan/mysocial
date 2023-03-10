<template>
    <v-container>
        <v-row justify="center" class="mt-10">
            <v-col cols="12" md="6">
                <v-card raised elevation="12" class="mx-auto mt-10">
                    <v-card-title class="primary white--text title justify-center">Login</v-card-title>
                    <v-card-text class="mt-5">
                        <v-text-field v-model="auth.email" prepend-icon="mdi-account-circle" type="text" label="Email" required name="email" v-validate="'required|email'" :error-messages="errors.collect('email')" data-vv-as="email"></v-text-field>
                        <v-text-field v-model="auth.password" prepend-icon="mdi-lock" :append-icon="showPassword ? 'mdi-eye' : 'mdi-eye-off'" @click:append="showPassword = !showPassword" :type="showPassword ? 'text' : 'password'"  label="Password" required name="password" v-validate="'required'" :error-messages="errors.collect('password')" data-vv-as="password"></v-text-field>
                    </v-card-text>
                    <v-card-actions class="justify-center pb-8">
                        <v-btn large class="primary" dark width="60%" :loading="isBusy" @click="login">Login</v-btn>
                    </v-card-actions>
                    <v-card-actions class="justify-center pb-6 px-3 links">
                        <router-link to="/forgot-password">Forgot Password</router-link>
                        <router-link to="/register">Not yet a user? Register</router-link>
                    </v-card-actions>
                    <div v-if="authError" class="pb-5">
                        <div class="error white--text pa-4 mx-3">
                            {{ errorMsg }}
                        </div>
                    </div>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
export default {
    name:"login",
    data(){
        return {
            showPassword: false,
            auth:{
                email:"",
                password:""
            },
            isBusy:false,
            authError: false,
            errorMsg: ''
        }
    },
    computed: {
        api(){
            return this.$store.getters.api
        },
        redirectOnLogin(){
            return this.$store.getters.redirectOnLogin
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
    methods:{
        login(){
            this.$validator.validateAll().then((isValid) => {
                if (isValid) {
                    this.isBusy = true
                    axios.post(this.api + '/login', {
                        email: this.auth.email,
                        password: this.auth.password,
                    }).then((res) => {
                        this.isLoading = false
                        this.authError = false
                        this.$store.commit('userLoginSuccess', res.data)
                        
                        // check if there is redirect on login
                        if(this.redirectOnLogin){
                            this.$router.push(this.redirectOnLogin)
                            localStorage.removeItem('redirOnLogin')
                        }else{
                            this.$router.push({name: 'Home'})
                        }
                    }).catch((err) => {//if login fails
                        this.isBusy = false
                        this.authError = true
                        if(err.response.status === 441){
                            this.errorMsg = 'Error! Invalid credentials.'
                        }else if(err.response.status === 551){
                            this.errorMsg = 'You cannot login into your account now. Kindly contact the admin.'
                        }else if(err.response.status === 561){
                            this.errorMsg = 'Kindly verify your email address before you can log in.'
                        }else{
                            this.errorMsg = 'Error! Invalid credentials.'
                        }
                    })
                }
            })
        },
    }
}
</script>

<style lang="scss" scoped>
    .v-card__actions.links{
        a{
            color: #004EFF;
            text-decoration: none;
            padding: 0 15px 10px;
            transition: all .4s ease;

            &:hover{
                color: #3371FF;
            }
        }
    }
</style>
