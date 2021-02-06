<template>
	<div class="container marginTop">
		<div class="card">
			<div class="card-header text-center">
				{{$t('comment.write')}}
			</div>
			<div class="card-body">
				<div class="card-text">
					<classic-ckeditor v-model="text" />

					<div class="text-center marginTop">
						<button class="btn btn-primary" @click="create">
							{{$t('comment.send')}}
						</button>
					</div>
				</div>
			</div>
		</div>

		<build-comment v-for="comment in comments" :key="comment.ID" :comment="comment" />
		<infinite-loading :identifier="identifier" spinner="waveDots" type="comment" @infinite="infiniteHandler" />
	</div>
</template>

<script>
import axios from 'axios';
import ClassicCkeditor from '../../components/ClassicCkeditor';
import {hideAjaxLoader, showAjaxLoader} from '../../store';
import BuildComment from './BuildComment';

export default {
	name: 'BuildCommentList',
	components: { ClassicCkeditor, BuildComment },
	props: {
		buildId: {
			type: Number,
			required: true,
			default: 0,
		},
		currentPage: {
			type: Number,
			default: 1,
		},
		commentList: {
			type: Array,
			default() {
				return [];
			}
		},
	},
	data() {
		return {
			text: '',
			comments: [],
			page: 1,
			lastPage: 0,
			identifier: 0,
		};
	},
	watch: {
		buildId() {
			this.fetch();
		},
	},
	created() {
		this.fetch();
	},
	methods: {
		infiniteHandler(state) {
			if (this.lastPage > 0 && this.page >= this.lastPage) {
				return state.complete();
			}

			axios
				.get('/builds/' + this.buildId + '/comments?page=' + this.page)
				.then(({ data: { data, currentPage, lastPage } }) => {
					this.page = currentPage + 1;
					this.lastPage = lastPage;
					this.comments.push(...data);
					this.$emit('comments', { comments: data, currentPage: this.page });
					state.loaded();
				})
				.catch(() => {
					state.error();
				});
		},
		create() {
			showAjaxLoader();

			axios
				.post('/builds/' + this.buildId + '/comments', {
					description: this.text,
				})
				.then(({ data }) => {
					this.comments.unshift(data);
					this.text = '';
					this.$emit('new-comment', data);
				})
				.catch(() => {

				})
				.finally(hideAjaxLoader);
		},
		fetch() {
			if (!this.buildId) {
				return;
			}

			this.lastPage = 0;
			this.page = 1;
			this.identifier = Date.now();
		},
	},
};
</script>