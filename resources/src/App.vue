<template>
    <div id="pageContainer">
        <nav class="navbar navbar-inverse navbar-fixed-top">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle">
                        <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span>
                    </button>
                    <router-link class="navbar-brand" :to="{name: 'home'}">DD:A Builder</router-link>
                </div>
                <div class="collapse navbar-collapse">
                    <ul class="nav navbar-nav">
                        <router-link tag="li" :to="{name: 'buildList'}"><a>List</a></router-link>
                        <!--                        <router-link tag="li" :to="{name: 'buildAddSelect'}"><a>Create</a></router-link>-->
                        <!--                        <router-link tag="li" :to="{name: 'bugReportAdd'}"><a>Report Bug</a></router-link>-->
                        <!--                        <router-link tag="li" :to="{name: 'bugReportList'}"><a>Bug Reports</a></router-link>-->
                    </ul>

                    <div v-if="!$store.state.authentication.user.steamID" class="navbar-right navbar-brand pointer" style="margin-left:0;padding-top:8px;">
                        <a @click="startLogin" :href="loginUrl" @click.prevent>
                            <img alt="Login" src="https://steamcommunity-a.akamaihd.net/public/images/signinthroughsteam/sits_01.png">
                        </a>
                    </div>
                    <ul class="nav navbar-nav navbar-right">
                        <li>
                            <a class="pointer" @click="toggleDarkMode">
                                <i class="fa" :class="{'fa-moon-o': !darkMode, 'fa-sun-o': darkMode}"></i>
                                <span class="label label-danger betaLabel" v-if="!darkMode">Beta</span>
                            </a>
                        </li>
                        <li v-if="$store.state.authentication.user.steamID" class="dropdown open">
                            <a class="dropdown-toggle pointer">{{$store.state.authentication.user.name}}</a>
                            <ul class="dropdown-menu">
                                <li><a>My Builds</a></li>
                                <li><a>My Issues</a></li>
                                <li><a>Favorite Builds</a></li>
                                <li><a>Liked Builds</a></li>
                                <li><router-link :to="{name: 'logout'}">Logout</router-link></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <section id="main" class="marginTop" v-if="!$router.currentRoute.meta.ignoreSection">
            <router-view class="container"></router-view>
        </section>
        <router-view v-else />

        <footer id="footer" class="navbar navbar-inverse navbar-footer">
            <div class="container">
                <ul class="nav navbar-nav">
                    <router-link tag="li" :to="{name: 'changelog'}"><a>Changelog</a></router-link>
                    <li><a href="https://github.com/RefreshingWater/DDABuilds" target="_blank">GitHub</a></li>
                </ul>
            </div>
        </footer>

        <link href="assets/css/bootstrap_slate.min.css" rel="stylesheet" v-if="darkMode">
    </div>
</template>

<script>
export default {
    name: 'App',
    data() {
        return {
            darkMode: false,
            loginUrl: 'https://steamcommunity.com/openid/login?' + new URLSearchParams({
                'openid.return_to': window.location.origin + '/api/auth/steam/',
                'openid.realm': window.location.origin,
                'openid.mode': 'checkid_setup',
                'openid.ns': 'http://specs.openid.net/auth/2.0',
                'openid.identity': 'http://specs.openid.net/auth/2.0/identifier_select',
                'openid.claimed_id': 'http://specs.openid.net/auth/2.0/identifier_select',
            }),
        }
    },
    created() {
        this.darkMode = parseInt(localStorage?.getItem('darkMode')) || 0;
    },
    methods: {
        startLogin() {
            let newWindow = window.open(this.loginUrl, 'ddaBuildsSteamLogin', 'height=500,width=600');
            // for popup blocker user, redirect directly to steam
            if (!newWindow || typeof newWindow.closed === 'undefined' || newWindow.closed) {
                window.location = newWindow;
            }
        },
        toggleDarkMode() {
            this.darkMode = !this.darkMode;
            localStorage.setItem('darkMode', this.darkMode ? '1' : '0');
        },
    },
};
</script>