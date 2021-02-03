<template>
	<div class="container">
		<table v-if="issues.length" :class="{'table-dark': $store.state.darkMode}" class="table table-bordered table-striped">
			<thead>
				<tr>
					<th v-if="showActionColumn" class="columnStatus">{{$t('issueList.action')}}</th>
					<th class="columnDate">{{$t('issueList.created')}}</th>
					<th class="columnText">{{$t('issueList.title')}}</th>
					<th style="width:15%;">{{$t('issueList.status')}}</th>
				</tr>
			</thead>
			<tbody>
				<tr v-for="issue in issues" :key="issue.ID">
					<td v-if="showActionColumn" class="columnStatus">
						<button v-if="issue.status !== 2" class="btn btn-primary" @click="closeIssue(issue)">
							<i class="fa fa-lock" />
						</button>
					</td>
					<td class="columnDate">{{formatDate(issue.time)}}</td>
					<td class="columnText">
						<router-link :to="issue.link">{{issue.title}}</router-link>
					</td>
					<td>{{$t('issue.status.' + (issue.status === 2 ? 'closed' : 'open'))}}</td>
				</tr>
			</tbody>
		</table>
		<div v-else class="alert alert-info">
			{{$t('issueList.noEntries')}}
		</div>

		<app-pagination :current-page="page" :pages="pages" />
	</div>
</template>

<script>
import axios from 'axios';
import AppPagination from '../../components/AppPagination';
import {hidePageLoader, showPageLoader} from '../../store';
import formatDate from '../../utils/date';
import {closeIssue} from '../../utils/issue';
import {formatSEOTitle} from '../../utils/string';

export default {
	name: 'IssueListView',
	components: { AppPagination },
	props: {
		mineList: {
			type: Boolean,
			default: false,
		},
	},
	data() {
		return {
			pages: 0,
			page: 0,
			issues: [],
		};
	},
	computed: {
		showActionColumn() {
			return !this.mineList;
		},
	},
	watch: {
		'$route.params.page'() {
			this.fetchList();
		},
	},
	created() {
		this.fetchList();
	},
	methods: {
		formatDate,
		closeIssue(issue) {
			closeIssue(issue);
		},
		fetchList() {
			showPageLoader();

			let mineList = this.mineList || false;
			let page = this.$route.params.page || 0;

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
				})
				.catch(() => {
					// TODO error handling
				})
				.finally(hidePageLoader);
		},
	},
};
</script>