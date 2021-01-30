<template>
	<div></div>
</template>

<script>
export default {
	name: 'AuthView',
	data() {
		return {
			vueInstance: opener && opener.document.getElementById('pageContainer').__vue__.$root,
		};
	},
	created() {
		if (!this.vueInstance) {
			this.$router.push({ name: 'home' });
			return;
		}

		let store = this.vueInstance.$store;
		store.commit('authentication/SET_USER', this.$store.state.authentication.user);
		window.close();
	},
};
</script>