import store from '../store';
import AuthView from '../views/AuthView';
import router from './index';

const NotFound = () => import('../views/NotFound');
const IndexView = () => import('../views/IndexView');
const BuildListView = () => import('../views/Build/BuildListView');
const BuildView = () => import('../views/Build/BuildView');
const ChangelogView = () => import('../views/ChangelogView');
const IssueListView = () => import('../views/Issue/IssueListView');
const IssueView = () => import('../views/Issue/IssueView');
const IssueAddView = () => import('../views/Issue/IssueAddView');
const BuildAddSelectView = () => import('../views/Build/BuildAddSelectView');
const BuildAddView = () => import('../views/Build/BuildAddView');

const routes = [
	{
		name: 'home',
		path: '/',
		component: IndexView,
		meta: {
			ignoreSection: true,
		},
	},
	{
		name: 'buildList',
		path: '/builds/:page?',
		component: BuildListView,
	},
	{
		name: 'build',
		path: '/build/:id-:title',
		component: BuildView,
	},
	{
		name: 'changelog',
		path: '/changelog',
		component: ChangelogView,
	},
	// builds
	{
		name: 'buildAddSelect',
		path: '/build-add-select',
		component: BuildAddSelectView,
	},
	{
		name: 'buildAdd',
		path: '/build-add/:mapID-:name',
		component: BuildAddView,
	},
	// issues
	{
		name: 'issueList',
		path: '/issues/:page?',
		component: IssueListView,
	},
	{
		name: 'issueAdd',
		path: '/issue-add',
		component: IssueAddView,
	},
	{
		name: 'issue',
		path: '/issue/:id-:title/:page?',
		component: IssueView,
	},
	// user related pages
	{
		name: 'myIssueList',
		path: '/my-issues/:page?',
		component: IssueListView,
		props: {
			mineList: true
		}
	},
	{
		name: 'myBuildList',
		path: '/my-builds/:page?',
		component: BuildListView,
		props: {
			fetchParams: {mine: 1},
			hideFilter: true,
		}
	},
	{
		name: 'likedBuildList',
		path: '/liked-builds/:page?',
		component: BuildListView,
		props: {
			fetchParams: {liked: 1},
			hideFilter: true,
		}
	},
	{
		name: 'favoriteBuildList',
		path: '/favorite-builds/:page?',
		component: BuildListView,
		props: {
			fetchParams: {watch: 1},
			hideFilter: true,
		}
	},
	// auth
	{
		name: 'logout',
		path: '/logout',
		beforeEnter: (to, from, next) => {
			store
				.dispatch('authentication/logout')
				.then(() => {
					if (from.meta.requiredAuth) {
						router.push({ name: 'home' });
					}
					else {
						router.push(from);
					}
				})
				.catch(() => {
					console.error('failed logout');
					// TODO error handling
				})
				.finally(() => next());
		},
	},
	{
		name: 'auth',
		path: '/auth',
		component: AuthView,
	},
	// not found
	{
		name: 'notFound',
		path: '*',
		component: NotFound,
	},
];

export default routes;