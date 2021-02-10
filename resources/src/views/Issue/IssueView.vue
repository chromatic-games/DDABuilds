<template>
	<div v-acceptance-selector:page="'issueView'" class="container">
		<div v-if="isMaintainer && issue.status !== 2" v-acceptance-selector:action-menu class="text-right">
			<button class="btn btn-primary" @click="close">
				Close
			</button>
		</div>
		<table :class="{'table-dark': $store.state.darkMode}" class="table table-bordered marginTop">
			<tbody>
				<tr>
					<td>{{$t('issueList.status')}}</td>
					<td v-acceptance-selector:field="'status'">{{$t('issue.status.' + (issue.status === 2 ? 'closed' : 'open'))}}</td>
				</tr>
				<tr>
					<td>{{$t('issueList.created')}}</td>
					<td>{{formatDate(issue.time)}}</td>
				</tr>
				<tr>
					<td>{{$t('issue.createdBy')}}</td>
					<td>{{issue.steamName}}</td>
				</tr>
				<tr>
					<td style="width:10%;">{{$t('issueList.title')}}</td>
					<td v-acceptance-selector:field="'title'">{{issue.title}}</td>
				</tr>
				<tr>
					<td>{{$t('issue.description')}}</td>
					<td v-acceptance-selector:field="'description'" class="user-content" v-html="issue.description" />
				</tr>
			</tbody>
		</table>

		<div v-if="needWait > 0" class="alert alert-danger">
			Please wait {{needWait}} seconds for the next comment.
		</div>
		<form v-else-if="issue.status !== 2" @submit.prevent="addComment">
			<div class="card">
				<div class="card-header text-center">
					{{$t('comment.write')}}
				</div>
				<div class="card-body">
					<classic-ckeditor v-model="form.description" />

					<div class="text-center marginTop">
						<input :disabled="needWait > 0 || form.description.length < 3" :value="$t('comment.send')" class="btn btn-primary" type="submit">
					</div>
				</div>
			</div>
		</form>

		<template v-if="comments.length">
			<div v-for="comment of comments" :key="comment.ID" class="card">
				<h5 class="card-header">
					{{comment.steamName}} ({{formatDate(comment.time)}})
				</h5>
				<div class="card-body">
					<div class="card-text user-content" v-html="comment.description" />
				</div>
			</div>

			<app-pagination :current-page="page" :pages="pages" :route-params="$route.params" route-name="issue" />
		</template>
	</div>
</template>

<script>
import axios from 'axios';
import {mapState} from 'vuex';
import AppPagination from '../../components/AppPagination';
import ClassicCkeditor from '../../components/ClassicCkeditor';
import {hidePageLoader, showPageLoader} from '../../store';
import formatDate from '../../utils/date';
import {closeIssue} from '../../utils/issue';
import {formatSEOTitle} from '../../utils/string';

export default {
	name: 'IssueView',
	components: { ClassicCkeditor, AppPagination },
	data() {
		return {
			baseUrl: '/issues/' + this.$route.params.id,
			issue: {},
			comments: [],
			pages: 0,
			page: 0,
			// comment
			interval: null,
			needWait: 0,
			form: {
				description: '',
			},
		};
	},
	computed: {
		...mapState({
			isMaintainer: (state) => state.authentication.user.isMaintainer,
		}),
	},
	watch: {
		'$route.params.id'() {
			this.fetch();
		},
		'$route.params.page'() {
			this.fetchComments();
		},
	},
	created() {
		this.fetch();
	},
	beforeDestroy() {
		this.destroyInterval();
	},
	methods: {
		formatDate,
		destroyInterval() {
			if (this.interval) {
				window.clearInterval(this.interval);
				this.interval = null;
			}
		},
		addComment() {
			if (this.form.description.length < 3 || this.needWait > 0) {
				return;
			}

			axios.post(this.baseUrl + '/comments', this.form).then(({ data }) => {
				this.form.description = '';

				if (data.needWait) {
					this.needWait = data.needWait;
					this.interval = window.setInterval(() => {
						this.needWait--;
						if (this.needWait <= 0) {
							this.destroyInterval();
						}
					}, 1_000);
				}

				if (data.comment) {
					this.comments.unshift(data.comment);
				}
			});
		},
		fetchComments() {
			this.page = this.$route.params.page || 0;
			this.pages = 0;

			return axios.get(this.baseUrl + '/comments?page=' + this.page).then(({ data: { data: comments, currentPage, lastPage } }) => {
				this.comments = comments;
				this.page = currentPage;
				this.pages = lastPage;
			});
		},
		fetch() {
			if (!this.$route.params.id) {
				return this.$router.push({ name: 'home' });
			}

			showPageLoader();

			axios
				.get(this.baseUrl)
				.then(({ data }) => {
					this.issue = data;

					return this.fetchComments();
				})
				.then(() => {
					let title = formatSEOTitle(this.issue.title);

					// redirect to correct title url, if title not equal to the url title
					if (this.$route.params.title !== title) {
						this.$router.push({
							name: this.$route.name,
							params: Object.assign({}, this.$route.params, { title }),
						});
					}
				})
				.catch(() => {
					this.$notify({
						type: 'error',
						text: this.$t('error.default'),
					});
					this.$router.push({ name: 'home' });
				})
				.finally(hidePageLoader);
		},
		close() {
			closeIssue(this.issue);
		},
	},
};
</script>