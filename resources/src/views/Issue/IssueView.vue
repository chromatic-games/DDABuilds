<template>
	<div class="container">
		<div v-if="isMaintainer && issue.status !== 2" class="text-right">
			<button class="btn btn-primary btn-close">Close</button>
		</div>
		<table class="table table-bordered marginTop">
			<tbody>
			<tr>
				<td>Status</td>
				<td>Open</td>
			</tr>
			<tr>
				<td>Created</td>
				<td>{{issue.time}}</td> <!-- TODO -->
			</tr>
			<tr>
				<td>Created by</td>
				<td>{{issue.steamName}}</td>
			</tr>
			<tr>
				<td style="width:10%;">Title</td>
				<td>{{issue.title}}</td>
			</tr>
			<tr>
				<td>Description</td>
				<td v-html="issue.description"></td>
			</tr>
			</tbody>
		</table>

		<div v-if="needWait > 0" class="alert alert-danger">
			Please wait {{needWait}} seconds for the next comment.
		</div>
		<form @submit.prevent="addComment">
			<dl>
				<dt>Comment</dt>
				<dd>
					<textarea v-model="form.description" name="description"></textarea>
				</dd>
			</dl>

			<div class="text-center">
				<input :disabled="needWait > 0 || form.description.length < 3" class="btn btn-primary" type="submit" value="Save" />
			</div>
		</form>

		<template v-if="comments.length">
			<div v-for="comment of comments" :key="comment.ID" class="card">
				<div class="card-body">
					<h5 class="card-title">{{comment.steamName}} ({{comment.time}})</h5>
					<div class="card-text" v-html="comment.description"></div>
				</div>
			</div>

			<app-pagination :page="page" :pages="pages" :route-params="$route.params" route-name="issue" />
		</template>
	</div>
</template>

<script>
import axios from 'axios';
import AppPagination from '../../components/AppPagination';
import {formatSEOTitle} from '../../utils/string';

export default {
	name: 'IssueView',
	components: { AppPagination },
	data() {
		return {
			isMaintainer: false,
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
	created() {
		this.fetch();
	},
	watch: {
		'$route.params.id'() {
			this.fetch();
		},
		'$route.params.page'() {
			this.fetchComments();
		},
	},
	methods: {
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
					// TODO error handling
				});
		},
	},
	beforeDestroy() {
		this.destroyInterval();
	},
};
</script>