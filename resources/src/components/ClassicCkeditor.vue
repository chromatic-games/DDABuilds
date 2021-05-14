<template>
	<ckeditor v-model="text" :config="config" :editor="editor" />
</template>

<script>
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
import CKEditor from '@ckeditor/ckeditor5-vue2';

export default {
	name: 'ClassicCkeditor',
	components: {
		ckeditor: CKEditor.component,
	},
	props: {
		value: {
			type: String,
			default: '',
		},
	},
	data() {
		return {
			text: this.value || '',
			editor: ClassicEditor,
			config: {
				toolbar: {
					items: [
						/*'code',*/
						'heading', '|',
						'bold', 'italic', /*'underline', 'strikethrough',*/ 'link', 'bulletedList', 'numberedList', '|',
						'indent', 'outdent', '|',
						'blockQuote', 'insertTable', 'undo', 'redo',
					],
				},
				table: { contentToolbar: ['tableColumn', 'tableRow', 'mergeTableCells'] },
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