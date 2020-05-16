<div class="form-group">
	<label for="difficultySelect">Difficulty:</label>
	<select class="form-control" id="difficultySelect" name="difficulty">
		<?php
		if ( $this->showAny ) {
			echo '<option value="0">Any</option>';
		}

		$difficulties = Difficulties::getAllDifficulties();
		foreach ( $difficulties as $difficulty ) {
			$difficultyId = $difficulty->getID();
			$difficultyName = $difficulty->getData('name');
			$selected = '';
			if ( $this->selectedDifficulty === $difficultyId ) {
				$selected = ' selected="selected"';
			}
			echo '<option value="'.$difficultyId.'"'.$selected.'>'.$difficultyName.'</option>';
		}
		?>
	</select>
</div>