<template>
	<div class="card panel-shadow">
		<div style="display:flex;">
			<div>
				<img :src="getSteamAvatar(comment.avatarHash)" alt="avatar profile" class="img-circle avatar">
			</div>
			<div style="margin-left: 15px;">
				<a :href="'https://steamcommunity.com/profiles/' + comment.steamID" target="_blank">{{comment.steamName}}</a><br>
				{{$t('')}} <!-- TODO workaround for locale switching -->
				<small class="text-muted time">{{formatDate(comment.date)}}</small>
			</div>
		</div>
		<div class="marginTop">
			<p v-html="comment.description" />
			<div class="marginTop">
				<button :class="{disabled: !canLike}" :disabled="!canLike" class="btn btn-default" @click="vote(like)">
					<i class="fa fa-thumbs-up icon" /> {{comment.likes}}
				</button>
				<button :class="{disabled: !canLike}" :disabled="!canLike" class="btn btn-default" @click="vote(dislike)">
					<i class="fa fa-thumbs-down icon" /> {{comment.dislikes}}
				</button>
			</div>
		</div>
	</div>
</template>

<style>
.panel-shadow {
	border: 1px solid #DDDDDD;
	box-shadow: rgba(0, 0, 0, 0.3) 7px 7px 7px;
	padding: 15px;
}
</style>

<script>
import {getSteamAvatar} from '../../utils/build';
import formatDate from '../../utils/date';
import {DISLIKE, LIKE, like} from '../../utils/like';

export default {
	name: 'BuildComment',
	props: {
		comment: {
			type: Object,
			required: true,
		},
	},
	data() {
		return {
			like: LIKE,
			dislike: DISLIKE,
		};
	},
	computed: {
		canLike() {
			if ( !this.$store.state.authentication.user.ID ) {
				return false;
			}

			return this.comment.steamID !== this.$store.state.authentication.user.ID;
		},
	},
	methods: {
		getSteamAvatar,
		formatDate,
		vote(likeValue) {
			if (!this.canLike) {
				return;
			}

			like('comment', this.comment, likeValue);
		},
	},
};
</script>