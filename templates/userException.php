<div class="container">
	<p id="errorMessage">
		<?php echo $this->message; ?>
	</p>
</div>

<?php
if ( DEBUG_MODE ) {
	echo '<!-- '.$this->name.' thrown in '.$this->file.' ('.$this->line.')
	Stacktrace:
	'.$this->stacktrace.'
	-->';
}
?>