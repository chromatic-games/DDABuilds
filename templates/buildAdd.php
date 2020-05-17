<?php

use data\build\stats\BuildStats;
use data\heroClass\HeroClass;

?>
<div class="container-fluid">
	<div class="row">
		<div class="col-md-3 text-center">
			<h3>Map: <b><?php echo $this->map->name; ?></b></h3>
		</div>
		<div class="col-md-3 text-center">
			<label>Build Name:</label>
			<input type="text" id="buildName" placeholder="Build Name" class="form-control" maxlength="128" value="<?php echo $this->escapeHtml($this->buildName); ?>" />
		</div>
		<div class="col-md-3 text-center">
			<label>Author:</label>
			<input type="text" id="authorName" placeholder="Author" class="form-control" maxlength="20" value="<?php echo $this->escapeHtml($this->author); ?>" />
		</div>
		<div class="col-md-3 text-center">
			<h3>DU: <b><span id="currentDefenseUnits" style="color: rgb(0, 0, 0);">0</span>/<span id="maxDefenseUnits"><?php echo $this->map->units ?></span></b> MU:
				<b><span id="currentMinionUnits" style="color: rgb(0, 0, 0);">0</span>/<span><?php echo $this->map->units ?></span></b>
			</h3>
		</div>
	</div>

	<ul class="nav nav-tabs" role="tablist" id="waveTabList">
		<li class="active" data-target="#buildTab"><a href="#buildTab" data-wave="0">Build</a></li>
		<li id="newWave"><a href="#">+</a></li>
		<?php if ( $this->action !== 'add' ) { ?>
			<li data-target="#comments">
		        <a role="tab" data-toggle="tab" href="#comments">Comments (<span><?php echo $this->build->comments ?></span>)</a>
		    </li>
		<?php } ?>
	</ul>
	<div class="tab-content">
		<?php if ( $this->action !== 'add' ) { ?>
			<div id="comments" class="tab-pane">
				test comments
			</div>
		<?php } ?>
		<div id="buildTab" class="tab-pane active">
			<div class="row ">
				<div class="col-lg-9">
					<div class="canvas">
						<img class="ddmap" src="<?php echo $this->map->getImage(); ?>">
						<?php
						if ( $this->build ) {
							foreach ( $this->build->getPlacedTowers() as $placed ) {
								/** @var \data\tower\Tower $tower */
								$tower = $this->availableTowers[$placed['fk_tower']];
								echo $tower->getHtml($placed);
							}
						}
						?>
					</div>
				</div>
				<div class="col-lg-3" id="towerControlPanel">
					<div class="row">
						<?php
						/** @var \data\tower\Tower[] $towers */
						foreach ( $this->towers as $classID => $towers ) {
							/** @var HeroClass $class */
							$class = $this->heroClasses[$classID];
							?>
							<div class="col-sm-6">
								<div class="panel panel-default">
									<div class="panel-heading"><?php echo $this->escapeHtml($class->name); ?>
										<label><input type="checkbox" class="disableckbx" value="squire" /> Disable View</label>
										<!-- TODO re_add, these has currently no effects :( -->
										<!--<br>
										<button class="front-tower" value="squire">Front</button>
										<button class="back-tower" value="squire">Back</button>-->
									</div>
									<div class="panel-body">
										<?php
										foreach ( $towers as $tower ) {
											$menu = '';
											if ( $tower->fk_class !== 4 && $tower->fk_class !== 3 /*&& $tower->fk_class !== ????*/ ) { // TODO replace with database column
												$menu = '<div class="menu"> <i class="fa fa-repeat"></i> </div>';
											}

											echo $tower->getHtml();
										}
										?>
									</div>
								</div>
							</div>
							<?php
						}
						?>

						<div class="col-sm-12">
							<div class="panel panel-default">
								<div class="panel-heading">Details</div>
								<div class="panel-body">
									<div class="form-group">
										<label for="requiredStatsClass">Required Attributes:</label>
										<select class="form-control" id="requiredStatsClass">
											<?php
											/** @var HeroClass $heroClass */
											foreach ( $this->heroClasses as $heroClass ) {
												if ( $heroClass->isHero ) {
													echo '<option value="'.$heroClass->getObjectID().'">'.$this->escapeHtml($heroClass->name).'</option>';
												}
											}
											?>
										</select>
										<div class="col-md-3">
											<label for="requiredStatsHp">Fortify:</label>
											<input class="form-control" id="requiredStatsHp" value="0">
										</div>
										<div class="col-md-3">
											<label for="requiredStatsDamage">Power:</label>
											<input class="form-control" id="requiredStatsDamage" value="0">
										</div>
										<div class="col-md-3">
											<label for="requiredStatsRange">Range:</label>
											<input class="form-control" id="requiredStatsRange" value="0">
										</div>
										<div class="col-md-3">
											<label for="requiredStatsRate">Def. Rate:</label>
											<input class="form-control" id="requiredStatsRate" value="0">
										</div>
									</div>

									<!-- build status -->
									<div class="form-group">
										<label for="buildStatus">Build Status:</label>
										<select class="form-control" id="buildStatus">
											<?php
											/** @var \data\build\status\BuildStatus $buildStatus */
											foreach ( $this->buildStatuses as $buildStatus ) {
												$selected = '';
												if ( $this->build && $this->build->getObjectID() && $buildStatus->getObjectID() == $this->build->fk_buildstatus ) {
													$selected = 'selected="selected"';
												}

												echo '<option '.$selected.' value="'.$buildStatus->getObjectID().'">'.$this->escapeHtml($buildStatus->name).'</option>';
											}
											?>
										</select>
									</div>

									<!-- difficulty -->
									<div class="form-group">
										<label for="difficulty">Difficulty:</label>
										<select class="form-control" id="difficulty">
											<?php
											/** @var \data\difficulty\Difficulty $difficulty */
											foreach ( $this->difficulties as $difficulty ) {
												$difficultyId = $difficulty->getObjectID();
												$difficultyName = $difficulty->name;
												$selected = '';
												if ( $this->build && $this->build->difficulty === $difficultyId ) {
													$selected = ' selected="selected"';
												}
												echo '<option value="'.$difficultyId.'"'.$selected.'>'.$difficultyName.'</option>';
											}
											?>
										</select>
									</div>
									<div class="form-group">
										<?php
										echo '<label for="campaign">Game Mode:</label><br>';
										$first = true;
										// TODO move to database
										foreach ( \data\build\Build::getGamemodes() as $mode ) {
											$checked = '';
											if ( ($this->action === 'add' && $first) || $this->build && $this->build->{$mode['key']} ) {
												$checked = ' checked';
												$first = false;
											}
											echo '<label class="radio-inline"><input type="radio" class="gamemode" name="gamemode" value="'.$mode['key'].'"'.$checked.'>'.$this->escapeHtml($mode['name']).'</label>';
										}
										?>
									</div>

									<div class="form-group">
										<div class="checkbox">
											<label>
												<input type="checkbox" id="hardcore" value="1"<?php echo $this->build && $this->build->hardcore ? ' checked' : ''; ?>> Hardcore
											</label>
										</div>
									</div>
									<div class="form-group">
										<div class="checkbox">
											<label>
												<input type="checkbox" id="afkAble" value="1"<?php echo $this->build && $this->build->afkable ? ' checked' : ''; ?>> AFK Able
											</label>
										</div>
									</div>

									<div class="form-group">
										<label>XP Per Run:</label>
										<input type="text" placeholder="XP Per Run" class="form-control" id="expPerRun" maxlength="20" value="<?php echo $this->escapeHtml($this->expPerRun); ?>" />
									</div>
									<div class="form-group">
										<label>Time Per Run:</label>
										<input type="text" placeholder="XP Per Run" class="form-control" id="timePerRun" maxlength="20" value="<?php echo $this->escapeHtml($this->timePerRun); ?>" />
									</div>

									<h4>Mana Used: <strong id="manaUsed">0</strong></h4>
									<h4>Mana to Upgrade: <strong id="manaUpgrade">0</strong></h4>

									<button class="btn btn-primary btn-save">save</button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="container">
				<div class="panel panel-default">
					<div class="panel-heading text-center"><strong>Description</strong></div>
					<div class="panel-body">
						<?php
						if ( $this->action !== 'view' ) {
							echo '<textarea class="form-control" rows="20" id="builddescription">';
							echo $this->description;
							echo '</textarea>';
						}
						elseif ( !$this->description ) {
							echo '<i>No description</i>';
						}
						?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php
if ( $this->action !== 'view' ) {
	/** @var BuildStats[] $buildStats */
	$buildStats = $this->build ? $this->build->getStats() : [];
	$stats = [];
	/** @var HeroClass $heroClass */
	foreach ( $this->heroClasses as $heroClass ) {
		if ( !$heroClass->isHero ) {
			continue;
		}

		$heroID = $heroClass->getObjectID();
		$stats[$heroID] = isset($buildStats[$heroID]) ? $buildStats[$heroID]->getStats() : [
			'hp'     => 0,
			'damage' => 0,
			'range'  => 0,
			'rate'   => 0,
		];
	}

	echo '<script>window.__DEFENSE_STATS = '.json_encode($stats).';</script>';
}
?>
<script>
	function calculateDefenseUnits() {
		var defenseUnits = 0;
		var minionUnits = 0;
		let mana = 0;
		let manaUpgrade = 0;
		var maxUnits = parseInt($('#maxDefenseUnits').text());
		$('.canvas .tower-container:visible').each(function (_, el) {
			var du = el.getAttribute('data-du');
			var requiredMana = parseInt(el.getAttribute('data-mana'));
			if (el.getAttribute('data-mu')) {
				minionUnits += parseInt(du);
			}
			else {
				defenseUnits += parseInt(du);
			}

			if (requiredMana) {
				mana += requiredMana;
				manaUpgrade += 2620;
			}
		});
		var currentDefenseUnits = $('#currentDefenseUnits');
		var minionDefenseUnits = $('#currentMinionUnits');

		// set colors
		currentDefenseUnits.css('color', defenseUnits > maxUnits ? 'red' : 'black');
		minionDefenseUnits.css('color', minionUnits > maxUnits ? 'red' : 'black');

		// set html values
		currentDefenseUnits.html(defenseUnits);
		minionDefenseUnits.html(minionUnits);
		$('#manaUsed').html(mana);
		$('#manaUpgrade').html(manaUpgrade);
	}

	$(document).ready(function () {
		let currentWave = 0;

		// update tab menu
		$(document).on('shown.bs.tab', '#waveTabList', function (e) {
			var waveID = $(e.target).find('a').attr('data-wave');
			$('#towerControlPanel .tower-container').attr('data-wave', waveID);
			currentWave = waveID;
			showWave(waveID);
			calculateDefenseUnits();
		});

		function showWave(waveID) {
			$('.canvas .tower-container').hide();
			$('.canvas .tower-container[data-wave=' + waveID + ']').show();
		}

		$(document).on('click', '#waveTabList li', function (event) {
			if ($(this).attr('id') === 'newWave' || event.target.classList.contains('fa')) {
				return;
			}

			event.preventDefault();
			$(this).tab('show');
		});

		$(document)
			.on('contextmenu', '.canvas .tower-container', function () {
				$(this).remove();
				calculateDefenseUnits();
				return false;
			});

		$('.canvas .tower-container').draggable({
			containment: 'parent',
			start: function (event, ui) {
				var left = parseInt($(this).css('left'), 10);
				left = isNaN(left) ? 0 : left;
				var top = parseInt($(this).css('top'), 10);
				top = isNaN(top) ? 0 : top;
				recoupLeft = left - ui.position.left;
				recoupTop = top - ui.position.top;
			},
			drag: function (event, ui) {
				ui.position.left += recoupLeft;
				ui.position.top += recoupTop;
			}
		});
		calculateDefenseUnits();
	});
</script>
<?php
if ( $this->action !== 'view' ) {
	?>
	<script>
		$(document).ready(function () {
			function getRotationDegrees(obj) {
				var matrix = obj.css('transform');
				var angle = 0;
				if (matrix !== 'none') {
					var values = matrix.split('(')[1].split(')')[0].split(',');
					var a = values[0];
					var b = values[1];
					angle = Math.round(Math.atan2(b, a) * (180 / Math.PI));
				}

				return (angle < 0) ? angle + 360 : angle;
			}

			// save
			function save(event) {
				event.preventDefault();
				var customWaves = [];
				$('.customwave').each(function (i, obj) {
					customWaves[i] = $(obj).find('span').text();
				});

				var towers = [];
				$('.canvas .tower-container').each(function (i, obj) {
					towers[i] = {
						x: $(obj).css('left'),
						y: $(obj).css('top'),
						towerID: obj.getAttribute('data-tower-id'),
						rotation: getRotationDegrees($(this)),
						wave: $(obj).attr('data-wave')
					};
				});

				var author = $('#authorName').val().trim();
				var buildName = $('#buildName').val().trim();

				if (author.length === 0) {
					alert('Please input an Author.');
					return;
				}

				if (buildName.length === 0) {
					alert('Please input an Build Name!');
					return;
				}

				if (towers.length === 0) {
					alert('You didn\'t placed any tower?');
					return;
				}

				var description = CKEDITOR.instances.builddescription.getData();
				var gamemode = $('input[name=gamemode]:checked').val();

				$('.btn-save').prop('disabled', true);
				showWave(0); // create a thumbnail from wave 0
				html2canvas($('.canvas'), {
					onrendered: function (canvas) {
						showWave(currentWave);
						var parameters = {
							mapID: <?php echo $this->map->getObjectID(); ?>,
							author,
							buildName,
							gamemode,
							customWaves,
							description: description,
							difficulty: $('#difficulty').val(),
							hardcore: $('#hardcore').prop('checked'),
							afkAble: $('#afkAble').prop('checked'),
							buildStatus: $('#buildStatus').val(),
							timePerRun: $('#timePerRun').val(),
							expPerRun: $('#expPerRun').val(),
							towers,
							stats: window.__DEFENSE_STATS,
							image: canvas.toDataURL('image/png')
						};

						console.log(parameters.image);

						$.post(
							'?save-map', parameters,
							function (data) {
								$('.btn-save').prop('disabled', false);
							}
						).fail(function (jqXHR, textStatus, errorThrown) {
							if (jqXHR.status == 404) {
								alert('Error while saving: ' + jqXHR.responseText);
								$('.btn-save').prop('disabled', false);
							}
						});
					}
				});
			};

			let canvas = $('.canvas');
			canvas.droppable({
				accept: '.tower-container',
				drop: function (event, ui) {
					if ($(ui.helper).hasClass('dummy')) {
						var clone = ui.helper.clone();
						var offset = canvas.offset();
						var canvasOffsetTop = offset.top;
						var canvasOffsetLeft = offset.left;
						var defenseOffset = 15;
						clone.css('top', ui.offset.top - canvasOffsetTop);
						clone.css('left', ui.offset.left - canvasOffsetLeft + defenseOffset);
						clone.removeClass('dummy');
						clone.draggable();
						clone.appendTo(canvas);
						calculateDefenseUnits();
					}
				}
			});

			for (let el of [$('#requiredStatsHp'), $('#requiredStatsRate'), $('#requiredStatsDamage'), $('#requiredStatsRange')]) {
				el.on('change', function () {
					var statsKey = el.attr('id').replace('requiredStats', '');
					var selectedClass = $('#requiredStatsClass').val();
					var newValue = parseInt(el.val());
					if (isNaN(newValue)) {
						newValue = 0;
					}
					statsKey = statsKey.charAt(0).toLowerCase() + statsKey.slice(1); //lcfirst
					window.__DEFENSE_STATS[selectedClass][statsKey] = newValue;
					$(this).val(newValue);
				});
			}

			$('#requiredStatsClass').on('change', function () {
				var selectedClass = $(this).val();
				$('#requiredStatsHp').val(window.__DEFENSE_STATS[selectedClass].hp);
				$('#requiredStatsRate').val(window.__DEFENSE_STATS[selectedClass].rate);
				$('#requiredStatsDamage').val(window.__DEFENSE_STATS[selectedClass].damage);
				$('#requiredStatsRange').val(window.__DEFENSE_STATS[selectedClass].range);
			}).trigger('change');

			// add a new wave
			$('#newWave').on('click', function () {
				var nextWave = $('.customwave').length + 1;
				var newTab = $('<li class="customwave" data-target="#buildTab"><a data-wave="' + nextWave + '"><span>custom wave ' + nextWave + '</span> <i class="fa fa-pencil pointer edit-wave"></i> <i class="fa fa-trash pointer delete-wave"></i></a></li>');
				$('#newWave').before(newTab);
				newTab.tab('show');
			});

			// edit mode
			$(document)
				.on('click', '.btn-save', save)
				.on('mousedown', '.menu', function (e) {
					var rotating_defense = $(this).parent();
					offset = rotating_defense.offset();
					$(document).on('mousemove', function (e) {
						var mouse_x = e.pageX - offset.left - rotating_defense.width() / 2;
						var mouse_y = e.pageY - offset.top - rotating_defense.height() / 2;
						var mouse_cur_angle = Math.atan2(mouse_y, mouse_x);
						var rotate_angle = mouse_cur_angle * (180 / Math.PI) + 90;
						rotating_defense.css('transform', 'rotate(' + rotate_angle + 'deg)');
					});
				})
				.on('mouseover', '.menu', function (e) {
					$(this).parent().draggable('disable');
				})
				.on('mouseout', '.menu', function (e) {
					$(this).parent().draggable('enable');
				})
				.on('mouseup', function (e) {
					$(document).unbind('mousemove');
				})
				.on('click', '.delete-wave', function () {
					if (confirm('Want delete this wave?')) {
						let deleteWave = $(this).closest('a[data-wave]');
						let currentWave = $('#waveTabList li.active');
						let waveID = deleteWave.attr('data-wave');
						// delete current active wave - select tab before
						if (currentWave.find('a[data-wave]').attr('data-wave') === waveID) {
							let before = null;
							for (let el of $('#waveTabList li').toArray()) {
								if (el === currentWave[0]) {
									$(before).tab('show');
									break;
								}
								before = el;
							}
						}

						deleteWave.closest('li').remove();
						$('.canvas .tower-container[data-wave=' + waveID + ']').remove();
					}
				})
				.on('click', '.edit-wave', function () {
					let waveID = $(this).parent().attr('data-wave');
					let waveName = $('#waveTabList a[data-wave=' + waveID + '] span');
					let newName = prompt('new wave name (max 24 characters):', waveName.text());
					if (newName === null) {
						return;
					}

					newName = newName.trim().substr(0, 24);
					if (newName) {
						waveName.text(newName);
					}
				});

			$('#towerControlPanel .tower-container').draggable({
				helper: 'clone',
				start(event, ui) {
					// center the icon on
					$(this).draggable('instance').offset.click = {
						left: Math.floor(ui.helper.width() / 2),
						top: Math.floor(ui.helper.height() / 2)
					};
				}
			});

			if ($('#builddescription').length) {
				CKEDITOR.replace('builddescription');
			}
		});
	</script>
<?php } else { ?>
	<script>
		// view mode
	</script>
<?php } ?>
