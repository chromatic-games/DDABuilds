<div class="container">
	<p class="alert alert-danger">
		<?php echo $this->message; ?>
	</p>

	<?php
	if ( DEBUG_MODE ) {
		echo $this->name.' thrown in '.$this->file.' ('.$this->line.') Stacktrace:<br />'.nl2br($this->stacktrace);
	}
	?>
</div>