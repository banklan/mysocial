<template>
    <nav class="mb-5">
        <v-app-bar fixed light class="white dark_text--text mb-15">
            <span class="hidden-md-and-up">
                <v-app-bar-nav-icon class="primary--text hidden-md-and-up" @click="navDrawer = true"></v-app-bar-nav-icon>
            </span>
            <v-toolbar-title class="ml-5 hidden-sm-and-down">
                <router-link to="/" style="cursor:pointer" exact>
                    <span class="">
                        <span class="my">my</span><span class="social">Social</span>
                    </span>
                </router-link>
            </v-toolbar-title>
            <v-spacer></v-spacer>
            <v-toolbar-items class="hidden-sm-and-down align-center">
                <template v-if="userIsLoggedIn">
                    <v-btn class="white--text" text v-for="menu in authMenus" :key="menu.title">
                        <router-link class="dark_text--text" :to="menu.path">{{ menu.title }}</router-link>
                    </v-btn>
                    <v-btn text class="secondary--text"  @click="userLogout">Logout</v-btn>
                </template>
                <!-- <template v-if="adminIsLoggedIn">
                    <v-btn class="white--text" text v-for="menu in authAdminMenus" :key="menu.title">
                        <router-link class="white--text" :to="menu.path">{{ menu.title }}</router-link>
                    </v-btn>
                    <v-btn class="white--text" text @click="adminLogout">Logout</v-btn>
                </template> -->
                <!-- <template v-if="!userIsLoggedIn && !adminIsLoggedIn"> -->
                <template v-if="!userIsLoggedIn">
                    <v-btn class="white--text" text v-for="menu in menus" :key="menu.title">
                        <router-link class="dark_text--text" :to="menu.path">{{ menu.title }}</router-link>
                    </v-btn>
                </template>
            </v-toolbar-items>
        </v-app-bar>
        <v-navigation-drawer v-model="navDrawer" absolute hide-overlay color="white primary--text" class="pt-10" disable-resize-watcher>
            <v-toolbar-title class="primary--text ml-4 mt-10 pb-4">
                <router-link class="primary--text" to="/" style="cursor: pointer" exact>
                    <span>mySocial</span>
                 </router-link>
            </v-toolbar-title>
            <v-divider></v-divider>
            <v-list class="mt-7">
                <template v-if="userIsLoggedIn">
                    <v-list-item dark class="primary--text" v-for="item in authMenus" :key="item.title" link :to="item.path" exact>
                        <v-list-item-content class="pl-5">
                            <v-list-item-title>{{ item.title }}</v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>
                    <v-list-item  @click.prevent="userLogout" class="ml-5">
                        <v-list-item-title class="secondary--text">Logout</v-list-item-title>
                    </v-list-item>
                </template>
                <template v-else>
                    <v-list-item dark class="primary--text" v-for="item in menus" :key="item.title" link :to="item.path" exact>
                        <v-list-item-content class="pl-5">
                            <v-list-item-title>{{ item.title }}</v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>
                </template>
            </v-list>
        </v-navigation-drawer>
    </nav>
</template>

<script>
export default {
    data(){
        return{
            menus: [
                {title: 'Home', path: '/'},
                {title: "About Us", path: "/about-us"},
                {title: "Login", path: "/login"},
                {title: "Register", path: "/register"},
                {title: "Contact Us", path: "/contact-us"},
            ],
            authMenus: [
                {title: 'Posts', path: '/'},
                {title: 'MyPosts', path: '/my-posts'},
                {title: 'CreatePost', path: '/create-post'},
                {title: 'Profile', path: '/profile'},
            ],
            authAdminMenus: [
                {title: 'Home', path: '/'},
                {title: 'Admin Dashboard', path: '/admin'},
            ],
            navDrawer: false,
            bg: ''
        }
    },
    computed: {
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
        userLogout(){
            if(this.userIsLoggedIn){
                axios.post(this.api + `/auth/logout`, {}, this.authHeaders).then(() =>{
                    this.$store.commit('logOutAuthUser')
                    this.$router.push('/')
                })
            }
        }
    },
}
</script>

<style lang="scss" scoped>
    a{
        text-decoration: none !important;
        text-transform:capitalize !important;
        transition: all 0.4s ease !important;
        &:hover{
            color: #004EFF !important;
        }
    }
    a.router-link-exact-active{
        color: #004EFF !important;
    }
    .v-list a.v-list-item{
        &:hover .v-list-item__title{
            margin-left: 3px !important;
        }

        .v-list-item__title{
           transition: all 400ms ease;
        }
    }
    .v-list-item--active{
        .v-list-item__title{
            color: #004EFF !important;
        }
    }
    .v-toolbar__title{
        font-family: 'Cookie', cursive;
        font-size: 2.1rem !important;

        .my{
            color: #004EFF !important;
        }
        .social{
            color: #FFB100 !important;
        }
    }
</style>
