<div class="container">
	<div class="alert alert-info">
		Please write bug/feature reports only in <strong>English</strong> or <strong>German</strong>.
	</div>

	<form method="post">
		<dl>
			<dt>Title</dt>
			<dd>
				<input type="text" class="form-control" name="title" />
				<?php
				if ( isset($this->formErrors['title']) ) {
					echo $this->formErrors['title']->getHtml();
				}
				?>
			</dd>
			<dt>Description (optional)</dt>
			<dd>
				<textarea id="description" name="description"></textarea>
			</dd>
		</dl>

		<div class="text-center">
			<input type="submit" class="btn btn-primary" value="Save" />
		</div>
	</form>
</div>

<script>
	$(document).ready(function () {
		CKEDITOR.replace('description');
	});
</script>
