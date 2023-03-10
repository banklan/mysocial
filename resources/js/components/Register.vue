<template>
    <v-container>
        <v-row align="center" justify="center" class="mt-10">
            <template v-if="createFail">
                <v-col cols="12" md="10">
                    <v-alert type="error" class="mt-5 mb-1 pa-2">
                        <p class="font-italic mb-2 text-center">Error in registering user! </p>
                        <p>{{ createError }}</p>
                    </v-alert>
                </v-col>
            </template>
            <template v-if="!userCreated">
                <v-col cols="12" md="6">
                    <v-card light raised elevation="12" min-height="350" class="mx-auto my-10">
                        <v-card-title class="primary white--text justify-center title font-weight-regular">Register User</v-card-title>
                        <v-card-text class="body-1 mt-4 px-7">
                            <v-text-field label="First Name" v-model="user.first_name" required placeholder="First Name/Given name" v-validate="'required|min:2|max:30'" :error-messages="errors.collect('first_name')" name="first_name" data-vv-as="first name"></v-text-field>
                            <v-text-field label="Last Name" v-model="user.last_name" required placeholder="Surname/Family Name" v-validate="'required|min:2|max:30'" :error-messages="errors.collect('last_name')" name="last_name" data-vv-as="last name"></v-text-field>
                            <v-text-field label="Email" type="text" v-model="user.email" required v-validate="'required|email'" :error-messages="errors.collect('email')" name="email"></v-text-field>
                            <v-text-field label="Phone No" type="text" v-model="user.phone" required v-validate="'required|numeric|max:16'" :error-messages="errors.collect('phone')" name="phone"></v-text-field>
                            <div class="my-4 subtitle-2 justify-center info lighten-2 light_text--text pa-3 font-weight-bold">Create a password of length between 5 & 20 alphanumeric characters.</div>
                            <v-text-field type="password" label="Password" v-model="user.password" required ref="pswd" v-validate="'required|min:5|max:20'" :error-messages="errors.collect('password')" name="password"></v-text-field>
                            <v-text-field type="password" label="Confirm Password" v-model="user.password_confirmation" required v-validate="'required|confirmed:pswd'" :error-messages="errors.collect('password_confirmation')" name="password_confirmation" data-vv-as="password confirmation"></v-text-field>
                        </v-card-text>
                        <v-checkbox v-model="agree">
                            <template v-slot:label>
                                <div class="pt-2">I agree to
                                    <router-link to="/terms-conditions">all the terms, conditions and code of conducts</router-link>.
                                </div>
                            </template>
                        </v-checkbox>
                        <v-card-actions class="justify-center">
                            <v-btn color="primary" width="60%" class="mb-5" large @click="register" :loading="isLoading">Register</v-btn>
                        </v-card-actions>
                        <v-card-actions class="justify-center mt-n4 pb-5 login_link">
                            Already a member? <router-link to="/login" class="primary--text">&nbsp; Login</router-link>
                        </v-card-actions>
                    </v-card>
                </v-col>
            </template>
            <template v-else>
                <v-col cols="12" md="8">
                    <v-alert type="success" border="left" class="mt-10 pa-4">
                        Thank you for registering on mySocial.com. We have sent a mail to your email address <strong>{{ user.email }}</strong> for email verification.
                        You can now <router-link to="/login">log in</router-link>
                    </v-alert>
                </v-col>
            </template>
        </v-row>
        <v-snackbar v-model="agreeToTerms" :timeout="6000" top color="info darken-1 white--text">
            Kindly read our <router-link to="/terms-conditions">terms & conditions</router-link> and agree before registering.
            <v-btn text color="white--text" @click="agreeToTerms = false">Close</v-btn>
        </v-snackbar>
    </v-container>
</template>

<script>
export default {
    data(){
        return{
            user: {
                first_name: '',
                last_name: '',
                email: '',
                password: '',
                password_confirmation: '',
                phone: ''
            },
            isLoading: false,
            createFail: false,
            createError: null,
            userCreated: false,
            agree: false,
            agreeToTerms: false
        }
    },
    computed: {
        api(){
            return this.$store.getters.api
        },
    },
    methods: {
        register(){
            this.$validator.validateAll().then((isValid) => {
                if (isValid) {
                    if(this.agree){
                        this.isLoading = true
                        axios.post(this.api + `/register`, {
                            user: this.user
                        }).then((res) => {
                            this.isLoading = false
                            this.createFail = false
                            // this.$router.push({name: 'UserEmailVerificationRequired', query:{q: res.data.email}})
                            this.userCreated = true
                        }).catch((err) =>{
                            this.isLoading = false
                            this.createFail = true
                            this.createError = 'There was an error while trying to register. Please ensure you are connected to the internet and try again.'
                        })
                    }else{
                        this.agreeToTerms = true
                    }
                }
            })
        }
    }
}
</script>

<style lang="scss" scoped>
    .login_link a{
        text-decoration: none;
    }
    a{
        text-decoration: none !important;
    }
</style>
