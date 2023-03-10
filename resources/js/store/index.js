import Vue from 'vue'
import Vuex from 'vuex'
import createPersistedState from 'vuex-persistedstate'
import router from '../router/routes'
import axios from 'axios';

Vue.use(Vuex, axios)

const auth_user = window.localStorage.getItem('authUser')
const authUser = auth_user ? JSON.parse(auth_user) : null

const user_loggedin = window.localStorage.getItem('userIsLoggedIn')
const userIsLoggedIn = user_loggedin ? true : false

const redirect = window.localStorage.getItem('redirOnlogin')
const redirectOnLogin = redirect ? redirect : null

export const store = new Vuex.Store({
    state: {
        api: 'http://localhost:8000/api',
        userIsLoggedIn: userIsLoggedIn,
        authUser: authUser,
        redirectOnLogin: redirectOnLogin,
        newPost: null,
        UserWasUnfollowed: false,
        unfollowedUserIndex: null,
        unfollowedUser: null,
        UserWasFollowed: false,
        followedUser: null,
        followedUserIndex: null,
        postUpdated: false,
        postDeleted: false,
        deletedPost: null,
        authProfileUpdated: false,
    },
    getters: {
        api(state){
            return state.api
        },
        authUser(state)
        {
            return state.authUser
        },
        userIsLoggedIn(state){
            return state.userIsLoggedIn
        },
        redirectOnLogin(state)
        {
            return state.redirectOnLogin
        },
        newPost(state)
        {
            return state.newPost
        },
        unfollowedUser(state)
        {
            return state.unfollowedUser
        },
        unfollowedUserIndex(state)
        {
            return state.unfollowedUserIndex
        },
        UserWasUnfollowed(state)
        {
            return state.UserWasUnfollowed
        },
        UserWasFollowed(state)
        {
            return state.UserWasFollowed
        },
        followedUser(state)
        {
            return state.followedUser
        },
        followedUserIndex(state)
        {
            return state.followedUserIndex
        },
        postUpdated(state)
        {
            return state.postUpdated
        },
        postDeleted(state)
        {
            return state.postDeleted
        },
        deletedPost(state){
            return state.deletedPost
        },
        authProfileUpdated(state){
            return state.authProfileUpdated
        }
    },
    actions: {

    },
    mutations:{
        userLoginSuccess(state, payload)
        {
            state.userIsLoggedIn = true
            state.authUser = Object.assign({}, payload.user, {token: payload.access_token})
            window.localStorage.setItem('authUser', JSON.stringify(state.authUser))
            window.localStorage.setItem('userIsLoggedIn', true)
        },
        redirectOnLogin(state, payload)
        {
            window.localStorage.setItem('redirOnLogin', payload)
            state.redirectOnLogin = payload
        },
        logOutAuthUser(state)
        {
            localStorage.removeItem('authUser')
            localStorage.removeItem('userIsLoggedIn')
            state.userIsLoggedIn = false
            state.authUser = null
        },
        newPostAdded(state, payload)
        {
            state.newPost = payload
        },
        UserWasUnfollowed(state)
        {
            state.UserWasUnfollowed = true
        },
        unfollowedUserIndex(state, payload)
        {
            state.unfollowedUserIndex = payload
        },
        unfollowedUser(state, payload)
        {
            state.unfollowedUser = payload
        },
        resetFollow(state)
        {
            state.UserWasUnfollowed = false
            state.UserWasFollowed = false
        },
        UserWasFollowed(state)
        {
            state.UserWasFollowed = true
        },
        followedUserIndex(state, payload)
        {
            state.followedUserIndex = payload
        },
        followedUser(state, payload)
        {
            state.followedUser = payload
        },
        postUpdated(state){
            state.postUpdated = true
        },
        postDeleted(state)
        {
            state.postDeleted = true
        },
        delPost(state, payload){
            state.postDeleted = true
            state.deletedPost = payload
        },
        resetPostAlerts(state)
        {
            state.postDeleted = false
            state.postUpdated = false
        },
        updatedAuthProfile(state, payload)
        {
            state.authUser.first_name = payload.first_name
            state.authUser.last_name = payload.last_name
            state.authUser.phone = payload.phone
            state.authUser.picture = payload.picture
            state.authUser.fullname = payload.first_name + ' '+ payload.last_name
            window.localStorage.setItem('authUser', JSON.stringify(state.authUser))
            state.authProfileUpdated = true
        },
        resetAuthFlashMsg(state, payload)
        {
            state.authProfileUpdated = false
        },
    }
})
