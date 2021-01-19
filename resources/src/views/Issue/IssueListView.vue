<template>
	<div class="container">
		<loading-indicator v-if="loading" />
		<template v-else>
			<table class="table table-bordered table-striped" :class="{'table-dark': $store.state.darkMode}">
				<thead>
				<tr>
					<!-- TODO i18n -->
					<th v-if="showActionColumn" style="width:1%;">Action</th>
					<th style="width:15%;">Created</th>
					<th>Title</th>
					<th style="width:15%;">Status</th>
				</tr>
				</thead>
				<tbody>
				<tr v-for="issue in issues" :key="issue.ID">
					<td v-if="showActionColumn">
						<button v-if="issue.status !== 2" class="btn btn-primary" @click="closeIssue(issue)">
							<i class="fa fa-lock"></i>
						</button>
					</td>
					<td>{{issue.time}}</td> <!-- TODO date -->
					<td>
						<router-link :to="issue.link">{{issue.title}}</router-link>
					</td>
					<td>{{$t('issue.status.' + (issue.status === 2 ? 'closed' : 'open'))}}</td>
				</tr>
				</tbody>
			</table>

			<app-pagination :page="page" :pages="pages" />
		</template>
	</div>
</template>

<script>
import axios from 'axios';
import AppPagination from '../../components/AppPagination';
import LoadingIndicator from '../../components/LoadingIndicator';
import {closeIssue} from '../../utils/issue';
import {formatSEOTitle} from '../../utils/string';

export default {
	name: 'IssueListView',
	components: { LoadingIndicator, AppPagination },
	data() {
		return {
			pages: 0,
			page: 0,
			issues: [],
			loading: true,
		};
	},
	props: {
		mineList: {
			type: Boolean,
			default: false,
		},
	},
	created() {
		this.fetchList();
	},
	watch: {
		'$route.params.page'() {
			this.fetchList();
		},
	},
	computed: {
		showActionColumn() {
			return !this.mineList;
		},
	},
	methods: {
		closeIssue(issue) {
			closeIssue(issue);
		},
		fetchList() {
			let mineList = this.mineList || false;
			let page = this.$route.params.page || 0;

			this.loading = true;

			axios
				.get('/issues/?page=' + page + (mineList ? '&mine=1' : ''))
				.then(({ data: { data, lastPage, currentPage } }) => {
					for (let issue of data) {
						issue.link = {
							name: 'issue',
							params: {
								id: issue.ID,
								title: formatSEOTitle(issue.title),
							},
						};
					}

					this.issues = data;
					this.pages = lastPage;
					this.page = currentPage;

					if (this.page > this.pages) {
						// this.pages = 0;
						// this.page = 0;
					}
				})
				.catch(() => {
					// TODO error handling
				})
				.finally(() => {
					this.loading = false;
				});
		},
	},
};
</script>