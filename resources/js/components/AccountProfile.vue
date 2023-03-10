<template>
    <v-container>
        <v-progress-circular indeterminate color="primary" :width="4" :size="40" v-if="isLoading" justify="center" class="mx-auto"></v-progress-circular>
        <v-row v-else class="mt-4" justify="space-around">
            <v-col cols="12" md="6">
                <v-card light raised outlined elevation="4" min-height="400" class="scroll" width="100%">
                    <v-card-title class="primary white--text subtitle-1 justify-center"><span v-if="authUser">{{ authUser.first_name }}'s</span>&nbsp; Profile</v-card-title>
                    <template v-if="!changePic">
                        <v-img v-if="authUser.picture" :src="authUser.picture" alt="profile picture" max-height="500" transition="scale-transition"></v-img>
                        <v-img v-else src="assets/shared/no-image.png" width="100%" height="450" transition="scale-transition"></v-img>
                        <v-card-actions class="justify-center">
                            <v-btn text color="blue darken-3" class="mt-2 mb-n4" @click="changePic = true">Change Picture</v-btn>
                        </v-card-actions>
                    </template>
                    <template v-if="changePic">
                        <v-card-actions class="justify-center mt-5" v-if="!prvImg">
                            <v-btn outlined color="primary lighten--2" class="mb-5" @click="openUpload">Choose Picture</v-btn>
                            <input type="file" ref="image" style="display:none" @change.prevent="pickImg" accept="image/*">
                        </v-card-actions>
                        <div v-else class="mt-3">
                            <v-img :src="prvImgUrl" height="150" contain alt="profile photo" aspect-ratio="1"></v-img>
                            <v-card-actions class="justify-center mt-5">
                                <v-btn dark color="primary" @click="uploadImg" :loading="isUpdating"><v-icon left>cloud</v-icon>Upload</v-btn>
                                <v-btn text small @click="removeImg"><v-icon color="red darken--2">delete</v-icon></v-btn>
                            </v-card-actions>
                        </div>
                    </template>
                    <template v-if="!openEdit">
                        <v-card-text class="mb-n4 subtitle-1">
                            <table class="table table-hover table-striped">
                                <thead></thead>
                                <tbody>
                                    <tr>
                                        <th width="30%">Name: </th>
                                        <td>{{ authUser.fullname }}</td>
                                    </tr>
                                    <tr>
                                        <th>Email: </th>
                                        <td>{{ authUser.email }}</td>
                                    </tr>
                                    <tr>
                                        <th>Phone Number: </th>
                                        <td>{{ authUser.phone }}</td>
                                    </tr>
                                    <tr>
                                        <th>Joined: </th>
                                        <td>{{ authUser.created_at | moment('Do MMM, YYYY') }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </v-card-text>
                        <v-card-actions class="justify-center mt-n10 pb-6">
                            <v-btn icon color="blue darken-2" @click="openEditForm"><v-icon>edit</v-icon></v-btn>
                        </v-card-actions>
                    </template>
                    <template v-else>
                        <v-card-text class="mt-5">
                            <v-text-field label="First Name" required v-model="edit.first_name" v-validate="'required|min:3|max:30'" :error-messages="errors.collect('f_name')" name="f_name" data-vv-as="first name"></v-text-field>
                            <v-text-field label="Last Name" required v-model="edit.last_name" v-validate="'required|min:3|max:30'" :error-messages="errors.collect('l_name')" name="l_name" data-vv-as="last name"></v-text-field>
                            <v-text-field label="Phone" required v-model="edit.phone" v-validate="'required|min:8|max:14'" :error-messages="errors.collect('phone')" name="phone"></v-text-field>
                        </v-card-text>
                        <v-card-actions class="justify-space-around pb-6">
                            <v-btn text color="red darken-2" width="40%" @click="openEdit = false">Cancel</v-btn>
                            <v-btn dark color="primary" width="40%" @click="updateProfile" :loading="isUpdating">Update</v-btn>
                        </v-card-actions>
                    </template>
                </v-card>
            </v-col>
            <v-col cols="12" md="5">
                <UserFollowings :user="authUser" />
                <UserPosts :user="authUser" />
                <v-card light raised outlined elevation="4" min-height="150" class="mt-5">
                    <v-card-title class="justify-center subtitle-1 primary white--text">
                        Change Account Password
                    </v-card-title>
                    <v-card-text class="mt-5">
                        <v-btn text color="primary" @click="openChangePswd = !openChangePswd"><v-icon left>lock</v-icon>Change Password</v-btn>
                        <template v-if="openChangePswd">
                            <v-text-field label="Current Password" type="password" v-model="changePassword.current_pswd" @blur="checkCurrentPswd" v-validate="'required'" :error-messages="errors.collect('pswd.current_pswd')" name="current_pswd" data-vv-scope="pswd" data-vv-as="current password"></v-text-field>
                            <div class="red--text darken-2 mb-3 font-italic subtitle-2" v-if="pswdChngErr">{{ oldPswdError }}</div>
                            <v-text-field label="New Password" type="password" v-model="changePassword.new_pswd" ref="new_pswd" v-validate="'required|min:5|max:20'" :error-messages="errors.collect('pswd.new_pswd')" name="new_pswd" data-vv-scope="pswd" data-vv-as="new password"></v-text-field>
                            <div class="red--text darken-2 mt-n3 mb-3 font-italic subtitle-2" v-if="newPswdChngErr">{{ newPswdErr }}</div>
                            <v-text-field label="Confirm Password" type="password" v-model="changePassword.confirm" v-validate="'required|confirmed:new_pswd'" :error-messages="errors.collect('pswd.confirm')" name="confirm" data-vv-scope="pswd" data-vv-as="confirm password"></v-text-field>
                            <v-card-actions class="justify-center pb-8 mt-3">
                                <v-btn color="red darken-2" large width="50%" text @click="clearPswdFields">Clear</v-btn>
                                <v-btn v-if="!pswdChngErr" dark color="primary" large width="50%" :loading="isUpdatingPswd" @click="updatePswd">Update Password</v-btn>
                            </v-card-actions>
                        </template>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
        <v-snackbar :value="authProfileUpdated" :timeout="3000" top color="green darken-1 white--text">
            Your profile was updated successfully.
            <v-btn text color="white--text" @click="authProfileUpdated = false">Close</v-btn>
        </v-snackbar>
        <v-snackbar v-model="updateProfileFailed" :timeout="6000" top color="red darken-1 white--text">
            There was an error while trying to update your profile picture. Please try again while ensuring there is network on your device.
            <v-btn text color="white--text" @click="updateProfileFailed = false">Close</v-btn>
        </v-snackbar>
        <v-snackbar v-model="profileUpdateFail" :timeout="6000" top dark color="red darken-1">
            There was an error while trying to update profile. Please fill all the fields validly and try again.
            <v-btn text color="white--text" @click="profileUpdateFail = false">Close</v-btn>
        </v-snackbar>
        <v-snackbar v-model="passwordChangeFailed" :timeout="8000" top color="red darken-1 white--text">
            There was an error while trying to update your profile password. Please ensure you choose a new password between 5 and 30 characters and you have mobile network before trying again.
            <v-btn text color="white--text" @click="passwordChangeFailed = false">Close</v-btn>
        </v-snackbar>
        <v-snackbar v-model="passwordChanged" :timeout="4000" top color="green darken-1 white--text">
            Your profile password was updated successfully.
            <v-btn text color="white--text" @click="passwordChanged = false">Close</v-btn>
        </v-snackbar>
        <v-snackbar v-model="currentPswdError" :timeout="6000" top color="red darken-1 white--text">
            Password update failed. Please ensure that you enter your correct current password.
            <v-btn text color="white--text" @click="currentPswdError = false">Close</v-btn>
        </v-snackbar>
    </v-container>
</template>

<script>
export default {
    data() {
        return {
            isLoading: false,
            openEdit: false,
            edit: {
                first_name: '',
                last_name: '',
                phone: ''
            },
            isUpdating: false,
            profileUpdateFail: false,
            changePic: false,
            prvImg: false,
            prvImgUrl: '',
            image: '',
            updateProfileFailed: false,
            openChangePswd: false,
            changePassword: {
                current_pswd: '',
                new_pswd: '',
                confirm: '',
            },
            pswdChngErr: false,
            oldPswdError: null,
            newPswdChngErr: false,
            newPswdErr: null,
            passwordChanged: false,
            passwordChangeFailed: false,
            isUpdatingPswd: false,
            currentPswdError: false,
            profileImg: null
        }
    },
    computed: {
        authUser(){
            return this.$store.getters.authUser
        },
        api(){
            return this.$store.getters.api
        },
        authHeaders(){
            let headers = {
                headers: {
                    "Authorization": `Bearer ${this.authUser.token}`
                }
            }
            return headers
        },
        authProfileUpdated(){
            return this.$store.getters.authProfileUpdated
        }
    },
    beforeRouteLeave (to, from, next) {
        this.$store.commit('resetAuthFlashMsg')
        next()
    },
    methods: {
        openEditForm(){
            this.openEdit = true
            this.edit.first_name = this.authUser.first_name
            this.edit.last_name = this.authUser.last_name
            this.edit.phone = this.authUser.phone
        },
        updateProfile(){
            this.$validator.validateAll().then((isValid) => {
                if(isValid) {
                    this.isUpdating = true
                    axios.post(this.api + '/auth/update_auth_profile', {
                        user: this.edit
                    }, this.authHeaders).then((res) => {
                        this.isUpdating = false
                        this.openEdit = false
                        this.$store.commit('updatedAuthProfile', res.data)
                        // this.profileUpdated = true
                    }).catch(() => {
                        this.isUpdating = true
                        this.profileUpdateFail = true
                    })
                }
            })
        },
        checkCurrentPswd(){
            if(this.changePassword.current_pswd !== ''){
                this.pswdChngErr = false
                axios.post(this.api + '/auth/confirm_current_pswd', {
                    password: this.changePassword.current_pswd
                }, this.authHeaders).then((res) => {
                    if(res.data == false){
                        this.pswdChngErr = true
                        this.oldPswdError = 'Invalid password.'
                    }
                })
            }
        },
        updatePswd(){
            this.$validator.validateAll('pswd').then((isValid) => {
                if (isValid) {
                    if(this.pswdChngErr === false){
                        if(this.changePassword.current_pswd !== this.changePassword.new_pswd){
                            this.isUpdatingPswd = true
                            axios.post(this.api + '/auth/update_auth_profile_password', {
                                password: this.changePassword.new_pswd.trim(),
                                password_confirmation: this.changePassword.confirm.trim()
                            }, this.authHeaders)
                            .then((res) => {
                                this.isUpdatingPswd = false
                                this.passwordChanged = true
                                this.openChangePswd = false
                                this.clearPswdFields()
                                this.newPswdChngErr = false
                                this.newPswdErr = ''
                            }).catch(()=>{
                                this.isUpdatingPswd = false
                                this.passwordChangeFailed = true
                            })
                        }else{
                            this.newPswdChngErr = true
                            this.newPswdErr = 'Invalid action. New password cannot be the same as the one you are trying to change.'
                        }
                    }else{
                        this.currentPswdError = true
                    }
                }
            })
        },
        clearPswdFields(){
            this.pswdChngErr = false
            this.changePassword.current_pswd = ''
            this.changePassword.new_pswd = ''
            this.changePassword.confirm = ''
            this.$validator.pause()
            this.$validator.fields.items.forEach(field => field.reset())
            this.$validator.errors.clear()
        },
        openUpload(){
            this.$refs.image.click()
        },
        pickImg(e){
            const file = e.target.files[0]
            this.image = file
            this.prvImg = true
            this.prvImgUrl = URL.createObjectURL(file)
        },
        uploadImg(){
            if(this.image !== null){
                this.isUpdating = true
                let form = new FormData();
                form.append('image', this.image)

                axios.post(this.api + `/auth/update_auth_profile_picture`, form, this.authHeaders)
                .then((res) => {
                    this.isUpdating = false
                    this.changePic = false
                    this.getS3Picture()
                    this.$store.commit('updatedAuthProfile', res.data)
                    this.removeImg()
                }).catch(() => {
                    this.isUpdating = false
                    this.updateProfileFailed = true
                })
            }
        },
        removeImg(){
            this.image = null
            this.prvImg = false
            this.prvImgUrl = ''
        },
        getS3Picture(){
            axios.get(this.api + '/auth/get_s3_profile_pic', this.authHeaders)
            .then((res) => {
                this.profileImg = res.data
            })
        }
    },
    created(){
        this.getS3Picture()
    }
}
</script>

<style lang="css" scoped>
    table tbody tr td{
        white-space: nowrap !important;
    }
</style>
