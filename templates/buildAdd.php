<?php

use data\build\stats\BuildStats;
use data\comment\Comment;
use data\gamemode\Gamemode;
use data\heroClass\HeroClass;
use system\Core;
use system\request\LinkHandler;

$isView = $this->action === 'view';
$tabTemplate = '<li class="customwave waveTab pointer" data-target="#buildTab"><a data-wave="%id%"><span>%name%</span>'
               .(!$isView ? ' <i class="fa fa-pencil edit-wave pointer"></i> <i class="fa fa-trash delete-wave pointer"></i>' : '')
               .'</a></li>';

/** @var \data\build\Build $build */
$build = $this->build;

?>
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-2 text-center">
				<h3>Map: <b><?php echo $this->map->name; ?></b></h3>
			</div>
			<div class="col-md-<?php echo $isView ? 7 : 5; ?> text-center">
				<?php if ( $isView ) { ?>
					<h3><?php echo $this->escapeHtml($this->buildName); ?></h3>
				<?php } else { ?>
					<label>Build Name:</label>
					<input type="text" id="buildName" placeholder="Build Name" class="form-control" maxlength="128" value="<?php echo $this->escapeHtml($this->buildName); ?>" />
				<?php } ?>
			</div>
			<div class="col-md-<?php echo $isView ? 2 : 4; ?> text-center">
				<?php if ( $isView ) { ?>
					<h3>Author: <a href="https://steamcommunity.com/profiles/<?php echo $build->fk_user; ?>" target="_blank"><?php echo $this->escapeHtml($this->author); ?></a>
					</h3>
				<?php } else { ?>
					<label>Author:</label>
					<input type="text" id="authorName" placeholder="Author" class="form-control" maxlength="20" value="<?php echo $this->escapeHtml($this->author); ?>" />
				<?php } ?>
			</div>
			<div class="col-md-1 text-center">
				<h3>
					DU: <b><span id="currentDefenseUnits">0</span>/<span id="maxDefenseUnits"><?php echo $this->map->units ?></span></b>
					<?php if ( $this->showMU ) { ?>
						MU: <b><span id="currentMinionUnits">0</span>/<span><?php echo $this->map->units ?></span></b>
					<?php } ?>
				</h3>
			</div>
		</div>

		<ul class="nav nav-tabs" role="tablist" id="waveTabList">
			<li class="active pointer waveTab" data-target="#buildTab"><a href="#buildTab" data-wave="0">Build</a></li>
			<?php
			if ( $build ) {
				foreach ( $build->getCustomWaves() as $id => $wave ) {
					echo str_replace(['%name%', '%id%'], [$this->escapeHtml($wave['name']), $id + 1], $tabTemplate);
				}
			}

			if ( !$isView ) {
				echo '<li class="pointer" id="newWave"><a href="#">+</a></li>';
			}

			if ( $this->action !== 'add' ) { ?>
				<li class="pointer" data-target="#comments">
		        <a role="tab" data-toggle="tab" href="#comments">Comments (<span><?php echo $build->comments ?></span>)</a>
		    </li>
			<?php } ?>
		</ul>
		<div class="tab-content">
			<?php if ( $this->action !== 'add' ) { ?>
				<div id="comments" class="tab-pane">
					<div class="marginTop container">
						<?php if ( Core::getUser()->steamID ) { ?>
							<div class="panel panel-default">
								<div class="panel-heading text-center"><b>Write a comment:</b></div>
								<div class="panel-body">
									<textarea class="form-control" rows="2" id="commentMain"></textarea>
									<br>
									<div class="text-center">
										<button type="button" class="btn btn-primary btn-comment">Send</button>
									</div>
								</div>
							</div>
							<script>
								$(document).ready(function () {
									CKEDITOR.replace('commentMain');

									$('.btn-comment').on('click', function () {
										let text = CKEDITOR.instances.commentMain.getData().trim();
										if (text.length) {
											$('.btn-comment').prop('disabled', true);
											Core.AjaxStatus.show();
											$.post(
												'?ajax', {
													className: '\\data\\comment\\CommentAction',
													actionName: 'add',
													parameters: {
														buildID: window.__DEFENSE_OBJECT_IDS[0],
														text
													}
												},
												function (data) {
													CKEDITOR.instances.commentMain.setData('');
													$('#commentList').prepend(data.returnValues);
													$('.btn-comment').prop('disabled', false);
													Core.AjaxStatus.hide();
												}
											).fail(function (jqXHR, textStatus, errorThrown) {
												try {
													alert('Error while saving: ' + JSON.parse(jqXHR.responseText).message);
												}
												catch (e) {
													alert('Unknown error while saving...');
												}
												Core.AjaxStatus.hide();
											});
										}
										else {
											alert('comment text is empty :(');
										}
									});
								});
							</script>
						<?php } ?>
						<div id="commentList">
							<?php
							/** @var \data\comment\Comment $comment */
							$comments = $build->getComments();
							if ( count($comments) > Comment::COMMENTS_PER_PAGE ) {
								$comments = array_slice($comments, 0, -1);
							}
							foreach ( $comments as $comment ) {
								echo Core::getTPL()->render('comment', ['comment' => $comment]);
							}
							?>
						</div>
						<?php
						$lastCommentID = 0;
						if ( count($build->getComments()) > Comment::COMMENTS_PER_PAGE ) {
							$lastComment = array_slice($build->getComments(), -2, 1)[0];
							$lastCommentID = $lastComment->getObjectID();
							echo '<div class="text-center" id="moreComments"><button class="btn btn-primary">More comments</button></div>';
						}
						?>
						<script>
							$(document).ready(function () {
								let lastCommentID = <?php echo $lastCommentID; ?>;
								$('#moreComments .btn').on('click', function () {
									Core.AjaxStatus.show();
									$.post(
										'?ajax', {
											className: '\\data\\comment\\CommentAction',
											actionName: 'loadMore',
											parameters: {
												buildID: window.__DEFENSE_OBJECT_IDS[0],
												lastID: lastCommentID
											}
										},
										function (data) {
											$('#commentList').append(data.returnValues.html);
											lastCommentID = data.returnValues.lastID;
											if (!data.returnValues.hasMore) {
												$('#moreComments').html('');
											}
											Core.AjaxStatus.hide();
										}
									).fail(function (jqXHR, textStatus, errorThrown) {
										try {
											alert('Error while saving: ' + JSON.parse(jqXHR.responseText).message);
										}
										catch (e) {
											alert('Unknown error while saving...');
										}
										Core.AjaxStatus.hide();
									});
								});
							});
						</script>
					</div>
				</div>
			<?php } ?>
			<div id="buildTab" class="tab-pane active jsObject" data-id="<?php echo $build ? $build->getObjectID() : 0; ?>" data-type="build">
				<div class="row ">
					<div class="col-lg-9">
						<div class="canvas">
							<img class="ddmap" src="<?php echo $this->map->getImage(); ?>">
							<?php
							$usedClasses = [];
							if ( $build !== null ) {
								foreach ( $build->getPlacedTowers() as $placed ) {
									/** @var \data\tower\Tower $tower */
									$tower = $this->availableTowers[$placed['fk_tower']];
									echo $tower->getHtml($placed, false, $this->action === 'view');
									if ( !in_array($tower->fk_class, $usedClasses) ) {
										$usedClasses[] = $tower->fk_class;
									}
								}
							}
							if ( $this->action !== 'view' ) {
								$usedClasses = $this->heroClasses->getObjectIDs();
							}
							?>
						</div>
					</div>
					<div class="col-lg-3" id="towerControlPanel">
						<div class="row marginTop">
							<div class="col-sm-12">
								<div class="panel panel-default">
									<div class="panel-heading">
										<i class="fa fa-question-circle" data-toggle="tooltip" title="click the image to enable or disable the type of towers"></i> Disable Tower
									</div>
									<div class="panel-body">
										<?php

										/** @var HeroClass $heroClass */
										foreach ( $this->heroClasses as $heroClass ) {
											if ( in_array($heroClass->getObjectID(), $usedClasses) ) {
												echo '<img src="'.$heroClass->getImage().'" title="'.$this->escapeHtml($heroClass->name).'" class="disableTowerCheckbox" data-class="'.$heroClass->getObjectID().'" />';
											}
										}
										?>
									</div>
								</div>
							</div>
							<?php
							if ( !$isView ) {
								/** @var HeroClass[] $heros */
								/** @var \data\tower\Tower[] $towers */
								$heros = $this->heroClasses->getObjects();
								foreach ( $this->towers as $classID => $towers ) {
									$class = $heros[$classID];
									$size = count($towers) > 5 ? 12 : 6;
									?>
									<div class="col-sm-<?php echo $size; ?>">
										<div class="panel panel-default">
											<div class="panel-heading"><?php echo $this->escapeHtml($class->name); ?>
												<!-- TODO re_add, these has currently no effects :( -->
												<!--<br>
												<button class="front-tower" value="squire">Front</button>
												<button class="back-tower" value="squire">Back</button>-->
											</div>
											<div class="panel-body">
												<?php
												foreach ( $towers as $tower ) {
													echo $tower->getHtml();
												}
												?>
											</div>
										</div>
									</div>
									<?php
								}
							} ?>

							<div class="col-sm-12">
								<div class="panel panel-default">
									<div class="panel-heading">Details</div>
									<div class="panel-body">
										<?php if ( !$isView ) { ?>
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
														if ( $build && $build->getObjectID() && $buildStatus->getObjectID() == $build->fk_buildstatus ) {
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
														if ( $build && $build->difficulty === $difficultyId ) {
															$selected = ' selected="selected"';
														}
														echo '<option value="'.$difficultyId.'"'.$selected.'>'.$difficultyName.'</option>';
													}
													?>
												</select>
											</div>
											<div class="form-group">
												<?php
												echo '<label>Game Mode:</label><br>';
												$first = true;
												/** @var Gamemode $mode */
												foreach ( $this->gamemodes as $mode ) {
													$checked = '';
													if ( ($this->action === 'add' && $first) || $build && $build->gamemodeID === $mode->getObjectID() ) {
														$checked = ' checked';
														$first = false;
													}
													echo '<label class="radio-inline"><input type="radio" name="gamemodeID" value="'.$mode->getObjectID().'"'.$checked.'>'.$this->escapeHtml($mode->name).'</label>';
												}
												?>
											</div>

											<div class="form-group">
												<div class="checkbox">
													<label>
														<input type="checkbox" id="hardcore" value="1"<?php echo $build && $build->hardcore ? ' checked' : ''; ?>> Hardcore
													</label>
												</div>
											</div>
											<div class="form-group">
												<div class="checkbox">
													<label>
														<input type="checkbox" id="afkAble" value="1"<?php echo $build && $build->afkable ? ' checked' : ''; ?>> AFK Able
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

											<button class="btn btn-primary btn-save">Save</button>
											<?php if ( $build ) { ?>
												<a href="<?php echo LinkHandler::getInstance()->getLink('Build', ['object' => $build], 'view') ?>" class="btn btn-info btn-viewer-mode">Viewer Mode</a>
											<?php }
										}
										else {
											$heroBuildStats = $build->getStats();
											if ( !empty($heroBuildStats) ) { ?>
												<table class="table table-responsive table-hover">
													<caption class="text-center">Required Hero Stats</caption>
													<thead>
													<tr>
														<th>Hero</th>
														<th>HP</th>
														<th>Damage</th>
														<th>Range</th>
														<th>Rate</th>
													</tr>
													</thead>
													<tbody>
													<?php
													/** @var BuildStats $buildStats */
													foreach ( $heroBuildStats as $buildStats ) {
														echo '<tr>
														<td>'.$this->escapeHtml($buildStats->getClass()->name).'</td>
														<td>'.$this->number($buildStats->hp).'</td>
														<td>'.$this->number($buildStats->damage).'</td>
														<td>'.$this->number($buildStats->range).'</td>
														<td>'.$this->number($buildStats->rate).'</td>
													</tr>';
													}
													?>
													</tbody>
												</table>
											<?php } ?>

											<h4>Build Status: <strong><?php echo $this->escapeHtml($build->getBuildStatus()->name) ?></strong></h4>
											<h4>Difficulty: <strong><?php echo $this->escapeHtml($build->getDifficulty()->name) ?></strong></h4>
											<h4>Game Mode: <strong><?php echo $this->escapeHtml($build->getGamemodeName()); ?></strong></h4>
											<h4>Hardcore: <strong><?php echo $build->hardcore ? 'Yes' : 'No' ?></strong></h4>
											<h4>AFK Able: <strong><?php echo $build->afkable ? 'Yes' : 'No' ?></strong></h4>
											<h4>XP Per Run: <strong><?php echo $this->escapeHtml($build->expPerRun) ?></strong></h4>
											<h4>Time Per Run: <strong><?php echo $this->escapeHtml($build->timePerRun) ?></strong></h4>
											<h4>Mana Used: <strong id="manaUsed"></strong></h4>
											<h4>Mana to Upgrade: <strong id="manaUpgrade"></strong></h4>
											<br />
											More Builds from
											<a href="<?php echo LinkHandler::getInstance()->getLink('BuildList', ['author' => $this->author]) ?>"><?php echo $this->escapeHtml($this->author); ?></a>
											<br /><br />
											<?php if ( $isView ) { ?>
												<button class="btn btn-<?php echo $build->getLikeValue() === 1 ? 'success' : 'default'; ?> jsVote" data-type="like" data-count="<?php echo $build->likes ?>"<?php echo !Core::getUser()->steamID || $build->fk_user === Core::getUser()->steamID ? ' disabled' : ''; ?>>
													<i class="fa fa-thumbs-up icon"></i> <span class="likeValue"><?php echo $this->number($build->likes) ?></span>
												</button>
											<?php } ?>
											<?php if ( $build->isCreator() ) { ?>
												<a href="<?php echo $build->getLink() ?>" class="btn btn-info">Editor Mode</a>
											<?php } ?>
										<?php } ?>
										<?php if ( $build->isCreator() ) { ?>
											<a class="btn btn-danger btn-delete">Delete Build</a>
										<?php } ?>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<?php if ( $this->action !== 'view' || $this->description ) { ?>
					<div class="container build-description-container">
						<div class="panel panel-default">
							<div class="panel-heading text-center"><strong>Description</strong></div>
							<div class="panel-body">
								<?php
								if ( $this->action !== 'view' ) {
									echo '<textarea class="form-control" rows="20" id="builddescription">';
									echo $this->description;
									echo '</textarea>';
								}
								else {
									echo $this->description;
								}
								?>
							</div>
						</div>
					</div>
				<?php } ?>
			</div>
		</div>
	</div>

<?php
if ( $this->action !== 'view' ) {
	/** @var BuildStats[] $buildStats */
	$stats = $build ? $build->getStats() : [];
	$buildStats = [];
	/** @var HeroClass $heroClass */
	foreach ( $this->heroClasses as $heroClass ) {
		if ( !$heroClass->isHero ) {
			continue;
		}

		$heroID = $heroClass->getObjectID();
		$buildStats[$heroID] = isset($stats[$heroID]) ? $stats[$heroID]->getStats() : [
			'hp'     => 0,
			'damage' => 0,
			'range'  => 0,
			'rate'   => 0,
		];
	}

	?>
	<script>
		window.__DEFENSE_STATS = <?php echo json_encode($buildStats); ?>;
		window.__DEFENSE_WAVE_TEMPLATE = '<?php echo $tabTemplate; ?>';
		window.__DEFENSE_MAP_ID = <?php echo $this->map->getObjectID(); ?>;
		window.__DEFENSE_OBJECT_IDS = [<?php echo $this->action !== 'add' ? $build->getObjectID() : ''; ?>];
	</script>
	<?php
}
?>
	<script>
		'use strict';

		let currentWave = 0;
		window.__DEFENSE_OBJECT_IDS = [<?php echo $this->action !== 'add' ? $build->getObjectID() : ''; ?>];

		function calculateDefenseUnits() {
			var defenseUnits = 0;
			var minionUnits = 0;
			let mana = 0;
			let manaUpgrade = 0;
			var maxUnits = parseInt($('#maxDefenseUnits').text());
			$('.canvas .tower-container:visible').each(function (_, el) {
				var du = el.getAttribute('data-du');
				var requiredMana = parseInt(el.getAttribute('data-mana'));
				if (el.hasAttribute('data-mu')) {
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

		function getWaveTowers(waveID) {
			return $('.canvas .tower-container[data-wave=' + waveID + ']');
		}

		function showWave(waveID) {
			$('.canvas .tower-container').hide();
			getWaveTowers(waveID).show();
			$('.disableTowerCheckbox.disabled').each(function (_, el) {
				let classID = $(el).attr('data-class');
				$('.canvas .tower-container[data-class=' + classID + ']').hide();
			});
		}

		$(document).ready(function () {
			// update tab menu
			$(document).on('shown.bs.tab', '#waveTabList', function (e) {
				var waveID = $(e.target).find('a').attr('data-wave');
				$('#towerControlPanel .tower-container').attr('data-wave', waveID);
				currentWave = waveID;
				showWave(waveID);
				calculateDefenseUnits();
			});

			$('.disableTowerCheckbox').on('click', function () {
				$(this).toggleClass('disabled');
				showWave(currentWave);
			});

			$(document)
				.on('click', '#waveTabList li', function (event) {
					if ($(this).attr('id') === 'newWave' || event.target.classList.contains('fa')) {
						return;
					}

					event.preventDefault();
					$(this).tab('show');
				})
				.on('contextmenu', '.canvas .tower-container', function () {
					$(this).remove();
					calculateDefenseUnits();
					return false;
				});

			var recoupLeft;
			var recoupTop;
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

			// initialize
			showWave(0);
			calculateDefenseUnits();
			$('[data-toggle="tooltip"]').tooltip();
		});
	</script>
<?php
if ( $this->action !== 'view' ) {
	?>
	<script>
		'use strict';

		$(document).ready(function () {
			let waves = $('#waveTabList .waveTab').length - 1;

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
				var gamemodeID = $('input[name=gamemodeID]:checked').val();

				Core.AjaxStatus.show();
				$('.btn-save').prop('disabled', true);
				// prevent crash on xss for firefox
				$('#authorName,#buildName').hide();
				// create a thumbnail from wave 0
				showWave(0);
				html2canvas($('.canvas'), {
					onrendered: function (canvas) {
						$('#authorName,#buildName').show();
						showWave(currentWave);
						$.post(
							'?ajax', {
								className: '\\data\\build\\BuildAction',
								actionName: 'save',
								objectIDs: window.__DEFENSE_OBJECT_IDS,
								parameters: {
									mapID: window.__DEFENSE_MAP_ID,
									author,
									buildName,
									gamemodeID,
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
								}
							},
							function (data) {
								if (data.returnValues !== null) {
									window.location.href = data.returnValues;
								}
								else {
									$('.btn-save').prop('disabled', false);
									Core.AjaxStatus.hide();
								}
							}
						).fail(function (jqXHR, textStatus, errorThrown) {
							try {
								alert('Error while saving: ' + JSON.parse(jqXHR.responseText).message);
							}
							catch (e) {
								alert('Unknown error while saving...');
							}
							$('.btn-save').prop('disabled', false);
							Core.AjaxStatus.hide();
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
				var nextWave = ++waves;
				var newTab = $(window.__DEFENSE_WAVE_TEMPLATE.replace('%id%', nextWave).replace('%name%', 'custom wave ' + nextWave));
				$('#newWave').before(newTab);
				newTab.tab('show');
			});

			$('.btn-viewer-mode').on('click', function (event) {
				if (!confirm('All unsaved changes will be Lost.\n\nStill Continue to Viewer Mode?')) {
					event.preventDefault();
				}
			});

			// edit mode
			$(document)
				.on('click', '.btn-save', save)
				.on('mousedown', '.menu', function (e) {
					var rotating_defense = $(this).parent();
					var offset = rotating_defense.offset();
					$(document).on('mousemove', function (e) {
						var mouse_x = e.pageX - offset.left - rotating_defense.width() / 2;
						var mouse_y = e.pageY - offset.top - rotating_defense.height() / 2;
						var mouse_cur_angle = Math.atan2(mouse_y, mouse_x);
						var rotate_angle = mouse_cur_angle * (180 / Math.PI) + 90;
						rotating_defense.css('transform', 'rotate(' + rotate_angle + 'deg)');
					});
				})
				.on('mouseover', '.canvas .tower-container', function (e) {
					var rotating_defense = $(this);
					rotating_defense.on('wheel', function (e) {
						e.preventDefault();

						let delta = 3;
						let scrollSpeed = 4 * (e.originalEvent.deltaY <= 0 ? -1 : 1);
						if (e.shiftKey) {
							delta /= 2;
						}
						else if (e.ctrlKey) {
							delta *= 2;
						}

						var rotate_angle = getRotationDegrees(rotating_defense) + (scrollSpeed * delta);
						rotating_defense.css('transform', 'rotate(' + rotate_angle + 'deg)');
					});
				})
				.on('mouseout', '.canvas .tower-container', function (e) {
					$(document).unbind('wheel');
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
					let deleteWave = $(this).closest('a[data-wave]');
					let waveID = deleteWave.attr('data-wave');
					let towers = getWaveTowers(waveID);
					if (towers.length === 0 || confirm('Want delete this wave?')) {
						let currentWave = $('#waveTabList li.active');
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
						towers.remove();
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
<?php }
if ( $build->isCreator() ) { ?>
	<script>
		$(document).ready(function () {
			$('.btn-delete').on('click', function () {
				if (window.confirm('Do you really want to Delete your Build?')) {
					$.post('?ajax', {
						className: '\\data\\build\\BuildAction',
						actionName: 'trash',
						objectIDs: window.__DEFENSE_OBJECT_IDS
					}).then(function (data) {
						window.location = data.returnValues;
					});
				}
			});
		});
	</script>
<?php } ?>