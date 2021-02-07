<template>
	<div class="container">
		<div v-if="needWait > 0" class="alert alert-danger" v-html="$t('issue.waiting', {count: needWait})" />
		<div class="alert alert-danger" v-html="$t('issue.notRelated')" />
		<div class="alert alert-info" v-html="$t('issue.info')" />

		<form @submit.prevent="submit">
			<div class="form-group">
				<label>{{$t('issueList.title')}}</label>
				<input v-model.trim="form.title" v-acceptance-selector:input="'title'" :class="{'is-valid': form.title.length >= 3}" class="form-control" required type="text">
				<small class="form-text text-muted">Title requires 3 characters ore more</small>
			</div>

			<div v-acceptance-selector:input="'description'" class="form-group">
				<label>{{$t('issue.description')}}</label>
				<classic-ckeditor v-model="form.description" />
			</div>

			<label>
				<input v-model="checkbox" v-acceptance-selector:input="'checkbox'" type="checkbox"> <span v-html="$t('issue.agreement')" />
			</label>

			<div class="text-center marginTop">
				<input v-acceptance-selector:input="'save'" :disabled="!checkbox || form.title.length < 3 || needWait > 0" :value="$t('words.save')" class="btn btn-primary" type="submit">
			</div>
		</form>
	</div>
</template>

<script>
import axios from 'axios';
import ClassicCkeditor from '../../components/ClassicCkeditor';
import {formatSEOTitle} from '../../utils/string';

export default {
	name: 'IssueAddView',
	components: { ClassicCkeditor },
	data() {
		return {
			checkbox: false,
			interval: null,
			needWait: 0,
			form: {
				title: '',
				description: '',
			},
		};
	},
	beforeDestroy() {
		this.destroyInterval();
	},
	methods: {
		destroyInterval() {
			if (this.interval) {
				window.clearInterval(this.interval);
				this.interval = null;
			}
		},
		submit() {
			if (!this.checkbox || this.form.title.length < 3 || this.needWait > 0) {
				return;
			}

			axios
				.post('/issues/', this.form)
				.then(({ data }) => {
					if (data.needWait) {
						this.needWait = data.needWait;
						this.destroyInterval();
						this.interval = window.setInterval(() => {
							this.needWait--;
							if (this.needWait <= 0) {
								this.destroyInterval();
							}
						}, 1_000);
					}
					else {
						this.$router.push({
							name: 'issue',
							params: {
								id: data.ID,
								title: formatSEOTitle(data.title),
							},
						});
					}
				})
				.catch(() => {
					this.$notify({
						type: 'error',
						text: this.$t('error.default'),
					});
				});
		},
	},
};
</script>