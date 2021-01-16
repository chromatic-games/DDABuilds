import store from '../store';
import AuthView from '../views/AuthView';
import router from './index';

const NotFound = () => import('../views/NotFound');
const IndexView = () => import('../views/IndexView');
const BuildListView = () => import('../views/BuildListView');
const BuildView = () => import('../views/BuildView');
const ChangelogView = () => import('../views/ChangelogView');
const IssueListView = () => import('../views/Issue/IssueListView');
const IssueView = () => import('../views/Issue/IssueView');
const MyIssueListView = () => import('../views/MyIssueListView');
const IssueAddView = () => import('../views/Issue/IssueAddView');

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
		path: '/build-list',
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
	{
		name: 'myIssueList',
		path: '/my-issues/:page?',
		component: MyIssueListView,
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