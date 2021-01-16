<template>
	<div class="container">
		<div v-if="needWait > 0" class="alert alert-danger">
			Please wait {{needWait}} seconds for next issue report.
		</div>
		<div class="alert alert-danger">
			Issues reported here are community reviewed and the reviewers are not related to Chromatic Games or Dungeon Defenders: Awakened employees in anyway.
		</div>
		<div class="alert alert-info">
			Please write issue reports only in <strong>English</strong> or <strong>German</strong>.
		</div>

		<form @submit.prevent="submit">
			<div class="form-group">
				<label>Title</label>
				<input v-model.trim="form.title" :class="{'is-valid': form.title.length >= 3}" class="form-control" required type="text" />
				<small class="form-text text-muted">title requires 3 characters ore more</small>
			</div>

			<div class="form-group">
				<label>Description</label>
				<textarea v-model.trim="form.description" class="form-control"></textarea>
			</div>

			<label>
				<input v-model="checkbox" type="checkbox"> This is an issue about the DDA:Builder website and <strong>not related to anything in game</strong>.
			</label>

			<div class="text-center marginTop">
				<input :disabled="!checkbox || form.title.length < 3 || needWait > 0" class="btn btn-primary" type="submit" value="Save" />
			</div>
		</form>
	</div>
</template>

<script>
import axios from 'axios';
import {formatSEOTitle} from '../../utils/string';

export default {
	name: 'IssueAddView',
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
					// TODO add error handling
				});
		},
	},
	beforeDestroy() {
		this.destroyInterval();
	},
};
</script>