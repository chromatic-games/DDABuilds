<template>
	<ckeditor v-model="text" :editor="ckeditor" />
</template>

<script>
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';

export default {
	name: 'ClassicCkeditor',
	props: ['value'],
	data() {
		return {
			text: this.value || '',
			ckeditor: ClassicEditor,
			ckeditorConfig: {
				wordCount: {
					displayCharacters: true,
					displayWords: true,
				},
			},
		};
	},
	watch: {
		value(newValue) {
			this.text = newValue;
		},
		text(newValue) {
			this.$emit('input', newValue);
		},
	},
};
</script>