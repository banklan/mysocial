import Vue from 'vue'
import Vuex from 'vuex'
import createPersistedState from 'vuex-persistedstate'
import router from '../router/routes'
import axios from 'axios';
// import auth from './auth'

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
        unfollowedUser: null,
        UserWasFollowed: false,
        followedUser: null,
        postUpdated: false,
        postDeleted: false,
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
        postUpdated(state)
        {
            return state.postUpdated
        },
        postDeleted(state)
        {
            return state.postDeleted
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
        // resetPostAlerts(state)
        // {
        //     state.postDeleted = false
        //     state.postUpdated = false
        // }
    }
})
