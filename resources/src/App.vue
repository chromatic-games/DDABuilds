<template>
	<div id="pageContainer">
		<b-navbar :variant="darkMode ? 'secondary' : 'dark'" fixed="top" toggleable="lg" type="dark">
			<div class="container">
				<router-link :to="{name: 'home'}" class="navbar-brand">DD:A Builder</router-link>
				<b-navbar-toggle target="nav-collapse" />
				<b-collapse id="nav-collapse" is-nav>
					<ul v-acceptance-selector:navigation class="nav navbar-nav">
						<router-link :to="{name: 'buildList'}" class="nav-item" tag="li"><a class="nav-link">{{$t('menu.buildList')}}</a></router-link>
						<template v-if="$store.state.authentication.user.ID">
							<router-link :to="{name: 'buildAddSelect'}" class="nav-item" tag="li">
								<a class="nav-link">{{$t('menu.buildAddSelect')}}</a>
							</router-link>
							<router-link :to="{name: 'issueAdd'}" class="nav-item" tag="li">
								<a class="nav-link">{{$t('menu.bugReportAdd')}}</a>
							</router-link>
							<router-link v-if="$store.state.authentication.user.isMaintainer" :to="{name: 'issueList'}" class="nav-item" tag="li">
								<a class="nav-link">{{$t('menu.issueList')}}</a>
							</router-link>
						</template>
					</ul>

					<!-- Right aligned nav items -->
					<b-navbar-nav class="ml-auto">
						<li class="nav-item position-relative">
							<a class="pointer nav-link" @click="toggleDarkMode">
								<i :class="{'fa-moon-o': !darkMode, 'fa-sun-o': darkMode}" class="fa pointer" />
							</a>
						</li>
						<b-nav-item-dropdown right>
							<template #button-content>
								{{$t('locales.' + $i18n.i18next.language)}}
							</template>
							<a v-for="language in languages" :key="language" class="dropdown-item pointer" role="menuitem"
								@click="$changeLanguage(language)">{{$t('locales.' + language)}}</a>
						</b-nav-item-dropdown>
						<b-nav-item-dropdown v-if="$store.state.authentication.user.ID" right>
							<template #button-content>
								<span v-acceptance-selector:user-dropdown>
									{{$store.state.authentication.user.name}}
								</span>
							</template>
							<router-link :to="{name: 'myBuildList'}" class="dropdown-item" tag="li"><a>{{$t('menu.myBuilds')}}</a></router-link>
							<router-link :to="{name: 'myIssueList'}" class="dropdown-item" tag="li"><a>{{$t('menu.myIssues')}}</a></router-link>
							<router-link :to="{name: 'likedBuildList'}" class="dropdown-item" tag="li"><a>{{$t('menu.likedBuilds')}}</a></router-link>
							<router-link :to="{name: 'favoriteBuildList'}" class="dropdown-item" tag="li"><a>{{$t('menu.favoriteBuilds')}}</a></router-link>
							<router-link :to="{name: 'logout'}" class="dropdown-item" tag="li"><a>{{$t('menu.logout')}}</a></router-link>
						</b-nav-item-dropdown>
						<div v-else class="navbar-right pointer">
							<a :href="loginUrl" @click="startLogin" @click.prevent>
								<img alt="Login" src="https://steamcommunity-a.akamaihd.net/public/images/signinthroughsteam/sits_01.png">
							</a>
						</div>
					</b-navbar-nav>
				</b-collapse>
			</div>
		</b-navbar>

		<loading-indicator v-if="$store.state.pageLoader" id="pageLoader" />
		<section v-if="!$router.currentRoute.meta.ignoreSection" id="main" class="marginTop">
			<router-view v-show="!$store.state.pageLoader" />
		</section>
		<router-view v-else v-show="!$store.state.pageLoader" />

		<footer :class="{'bg-dark': !darkMode, 'bg-secondary': darkMode}" class="navbar navbar-dark navbar-footer navbar-expand-lg">
			<div class="container">
				<ul class="nav navbar-nav">
					<router-link :to="{name: 'changelog'}" class="nav-item" tag="li"><a class="nav-link">Changelog</a></router-link>
					<li class="nav-item">
						<a class="nav-link" href="https://github.com/RefreshingWater/DDABuilds" target="_blank">GitHub <i class="fa fa-external-link" /></a>
					</li>
				</ul>
			</div>
		</footer>

		<div v-if="$store.state.ajaxLoader">
			<div class="loadingSpinner">
				<loading-indicator />
			</div>
			<div class="pageBackdrop" />
		</div>

		<notifications position="top center" />
	</div>
</template>

<script>
import LoadingIndicator from './components/LoadingIndicator';
import {supportedLanguages} from './i18n';

export default {
	name: 'App',
	components: { LoadingIndicator },
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

			this.$store.commit('SET_DARKMODE', newValue);
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
				window.location = this.loginUrl;
			}
		},
		toggleDarkMode() {
			this.darkMode = !this.darkMode;
			localStorage?.setItem('darkMode', this.darkMode ? '1' : '0');
		},
	},
};
</script>

<style lang="scss">
.pageBackdrop {
	background-color: rgba(0, 0, 0, .4);
	bottom: 0;
	left: 0;
	position: fixed;
	right: 0;
	top: 0;
	z-index: 1395;
}

.loadingSpinner {
	background-color: #FFFFFF;
	border: 1px solid #CCCCCC;
	color: #2C3E50;
	box-shadow: 2px 2px 5px 0 rgba(0, 0, 0, .2);
	left: 50%;
	padding: 10px;
	position: fixed;
	text-align: center;
	top: 200px;
	transform: translateX(-50%);
	transition: visibility 0s linear 0.12s, opacity 0.12s linear;
	z-index: 1401;

	> span:not(.fa) {
		display: block;
		margin-top: 5px;
	}
}

#pageLoader {
	flex: 1 0 auto; justify-content: center; display: flex; align-items: center;
}
</style>