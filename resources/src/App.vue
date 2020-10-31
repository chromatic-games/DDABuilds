<template>
    <div id="pageContainer">
        <b-navbar :variant="darkMode ? 'secondary' : 'dark'" sticky toggleable="lg" type="dark">
            <div class="container">
                <router-link :to="{name: 'home'}" class="navbar-brand">DD:A Builder</router-link>
                <b-navbar-toggle target="nav-collapse"></b-navbar-toggle>
                <b-collapse id="nav-collapse" is-nav>
                    <ul class="nav navbar-nav">
                        <router-link :to="{name: 'buildList'}" class="nav-item" tag="li"><a class="nav-link">{{$t('menu.buildList')}}</a></router-link>
                        <router-link :to="{name: 'buildAddSelect'}" class="nav-item" tag="li"><a class="nav-link">{{$t('menu.buildAddSelect')}}</a></router-link>
                        <router-link :to="{name: 'bugReportAdd'}" class="nav-item" tag="li"><a class="nav-link">{{$t('menu.bugReportAdd')}}</a></router-link>
                        <router-link :to="{name: 'bugReportList'}" class="nav-item" tag="li"><a class="nav-link">{{$t('menu.bugReportList')}}</a></router-link>
                    </ul>

                    <!-- Right aligned nav items -->
                    <b-navbar-nav class="ml-auto">
                        <li class="nav-item position-relative">
                            <a class="pointer nav-link" @click="toggleDarkMode">
                                <i :class="{'fa-moon-o': !darkMode, 'fa-sun-o': darkMode}" class="fa pointer"></i>
                                <span v-if="!darkMode" class="badge badge-danger badge-beta">Beta</span>
                            </a>
                        </li>
                        <b-nav-item-dropdown right>
                            <template #button-content>{{$t('locales.' + $i18n.i18next.language)}}</template>
                            <a v-for="language in languages" class="dropdown-item pointer" role="menuitem" @click="$changeLanguage(language)">{{$t('locales.' + language)}}</a>
                        </b-nav-item-dropdown>
                        <b-nav-item-dropdown v-if="$store.state.authentication.user.steamID" right>
                            <template #button-content>
                                {{$store.state.authentication.user.name}}
                            </template>
                            <a class="dropdown-item">My Builds</a> <a class="dropdown-item">My Issues</a> <a class="dropdown-item">Favorite Builds</a>
                            <a class="dropdown-item">Liked Builds</a>
                            <router-link :to="{name: 'logout'}" class="dropdown-item">Logout</router-link>
                        </b-nav-item-dropdown>
                        <div v-else class="navbar-right navbar-brand pointer">
                                <a :href="loginUrl" @click="startLogin" @click.prevent>
                                    <img alt="Login" src="https://steamcommunity-a.akamaihd.net/public/images/signinthroughsteam/sits_01.png">
                                </a>
                        </div>
                    </b-navbar-nav>
                </b-collapse>
            </div>
        </b-navbar>

        <section v-if="!$router.currentRoute.meta.ignoreSection" id="main" class="marginTop">
            <router-view class="container"></router-view>
        </section>
        <router-view v-else />

        <footer :class="{'bg-dark': !darkMode, 'bg-secondary': darkMode}" class="navbar navbar-dark navbar-footer navbar-expand-lg">
            <div class="container">
                <ul class="nav navbar-nav">
                    <router-link :to="{name: 'changelog'}" class="nav-item" tag="li"><a class="nav-link">Changelog</a></router-link>
                    <li class="nav-item"><a class="nav-link" href="https://github.com/RefreshingWater/DDABuilds" target="_blank">GitHub <i class="fa fa-external-link"></i></a></li>
                </ul>
            </div>
        </footer>
    </div>
</template>

<script>
import {supportedLanguages} from './i18n';

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
            supportedLanguages,
        };
    },
    computed: {
        languages() {
            let language = this.$i18n.i18next.language;
            let languages = [];
            for (let locale of Object.keys(this.$t('locales'))) {
                if (locale !== language) {
                    languages.push(locale);
                }
            }

            return languages;
        },
    },
    watch: {
        darkMode(newValue) {
            let classList = document.body.classList;
            if (newValue) {
                classList.add('bg-dark');
            }
            else {
                classList.remove('bg-dark');
            }
        },
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
            localStorage?.setItem('darkMode', this.darkMode ? '1' : '0');
        },
    },
};
</script>