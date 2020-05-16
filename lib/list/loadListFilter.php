<?php

use system\request\LinkHandler;

?>
<div class="panel panel-default">
	<div class="panel-heading text-center"><b>Filter:</b></div>
	<div class="panel-body">
		<form method="post" action="<?php echo LinkHandler::getInstance()->getLink('List'); ?>">
			<div class="row">
				<div class="col-md-2">
					<div class="form-group">
						<label for="bname">Build Name:</label>
						<input type="text" placeholder="Build Name" class="form-control" id="bname" name="bname" value="<?php echo $this->escapeHtml($this->bname); ?>">
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
						<label for="author">Author:</label>
						<input type="text" placeholder="Author" class="form-control" id="author" name="author" value="<?php echo $this->escapeHtml($this->author); ?>">
					</div>
				</div>
				<div class="col-md-3">
					<?php
					$default = true;
					include "loadDifficultySelect.php";
					?>
				</div>
				<div class="col-md-3">
					<?php
					$default = true;
					include "loadMapSelect.php";
					?>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12 text-center">
					<button type="submit" id="search" class="btn btn-primary">Search</button>
				</div>
			</div>
		</form>
	</div>
</div>

<style>
	.checkbox label:after {
		content: '';
		display: table;
		clear: both;
	}

	.checkbox .cr {
		position: relative;
		display: inline-block;
		border: 1px solid #a9a9a9;
		border-radius: .25em;
		width: 1.3em;
		height: 1.3em;
		float: left;
		margin-right: .5em;
	}

	.checkbox .cr .cr-icon {
		position: absolute;
		font-size: .8em;
		line-height: 0;
		top: 50%;
		left: 20%;
	}

	.checkbox label input[type="checkbox"] {
		display: none;
	}

	.checkbox label input[type="checkbox"] + .cr > .cr-icon {
		transform: scale(3) rotateZ(-20deg);
		opacity: 0;
		transition: all .3s ease-in;
	}

	.checkbox label input[type="checkbox"]:checked + .cr > .cr-icon {
		transform: scale(1) rotateZ(0deg);
		opacity: 1;
	}

	.checkbox label input[type="checkbox"]:disabled + .cr {
		opacity: .5;
	}
</style>