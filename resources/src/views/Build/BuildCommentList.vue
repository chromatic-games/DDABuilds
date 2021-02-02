<template>
	<div class="container marginTop">
		<div class="card">
			<div class="card-header">
				Write a comment
			</div>
			<div class="card-body">
				<div class="card-text">
					<classic-ckeditor v-model="text" />

					<button @click="create">
						Send
					</button>
				</div>
			</div>
		</div>

		<build-comment v-for="comment in comments" :key="comment.ID" :comment="comment" />
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
	},
	data() {
		return {
			text: '',
			comments: [],
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

			axios.get('/builds/' + this.buildId + '/comments').then(({ data: { data } }) => {
				this.comments.push(...data);
			});
		},
	},
};
</script>