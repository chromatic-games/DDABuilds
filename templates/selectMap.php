<div class="form-group">
	<label for="mapSelect">Map:</label>
	<input class="form-control" id="mapSelect" list="maps" name="map" value="<?php echo $this->selectedMap ? $this->selectedMap : ''; ?>">
	<datalist id="maps">
		<?php

		use data\map\MapList;

		if ( $this->showAny ) {
			echo '<option value="0">Any</option>';
		}

		$maps = new MapList();
		$maps->readObjects();
		$maps = $maps->getObjects();

		/** @var \data\map\Map $map */
		foreach ( $maps as $map ) {
			echo '<option value="'.$map->getObjectID().'">'.$this->escapeHtml($map->name).'</option>';
		}
		?>
	</datalist>
</div>

<script>
	$(document).ready(function () {
		$('#mapSelect').flexdatalist({
			minLength: 1,
			searchContain: true,
			maxShownResults: 10,
			valueProperty: 'value'
		});
	});
</script>