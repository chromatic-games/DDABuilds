<div class="container">
    <div class="alert alert-danger">
        Issues reported here are community reviewed and the reviewers are not related to Chromatic Games or Dungeon Defenders: Awakened employees in anyway.
    </div>
    <div class="alert alert-info">
        Please write issue reports only in <strong>English</strong> or <strong>German</strong>.
    </div>

    <form method="post">
        <dl>
            <dt>Title</dt>
            <dd>
                <input type="text" class="form-control" name="title" value="<?php echo htmlentities($this->title); ?>" />
				<?php
				if ( isset($this->formErrors['title']) ) {
					echo $this->formErrors['title']->getHtml();
				}
				?>
            </dd>
            <dt>Description (optional)</dt>
            <dd>
                <textarea id="description" name="description"><?php echo htmlentities($this->description); ?></textarea>
            </dd>
        </dl>
        <dl>
            <dd>
                <label>
                    <input type="checkbox" name="checkbox" /> This is an issue about the DDA Builder website and not related to anything in game.
                </label>
				<?php
				if ( isset($this->formErrors['checkbox']) ) {
					echo $this->formErrors['checkbox']->getHtml();
				}
				?>
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
