<?php

use data\difficulty\Difficulty;
use data\gamemode\Gamemode;
use system\request\LinkHandler;

$linkParameters = 'pageNo='.$this->pageNo.'&sortField='.$this->sortField.'&sortOrder='.$this->sortOrder;
$additionalParameters = '';
if ( $this->showFilter ) {
	if ( $this->author ) {
		$additionalParameters .= '&author='.$this->author;
	}
	if ( $this->name ) {
		$additionalParameters .= '&name='.$this->name;
	}
	if ( $this->difficulty ) {
		$additionalParameters .= '&difficulty='.$this->difficulty;
	}
	if ( $this->map ) {
		$additionalParameters .= '&map='.$this->map;
	}
	if ( $this->gamemode ) {
		$additionalParameters .= '&gamemode='.$this->gamemode;
	}
}

?>
<div class="container">
	<?php if ( $this->showFilter ) { ?>
		<div class="panel panel-default">
			<div class="panel-heading text-center"><b>Filter:</b></div>
			<div class="panel-body">
				<form method="post" action="<?php echo LinkHandler::getInstance()->getLink('BuildList'); ?>">
					<div class="row">
						<div class="col-md-3 col-sm-6">
							<div class="form-group">
								<label for="bname">Build Name:</label>
								<input type="text" placeholder="Build Name" class="form-control" name="name" value="<?php echo $this->escapeHtml($this->name); ?>">
							</div>
						</div>
						<div class="col-md-2 col-sm-6">
							<div class="form-group">
								<label for="author">Author:</label>
								<input type="text" placeholder="Author" class="form-control" name="author" value="<?php echo $this->escapeHtml($this->author); ?>">
							</div>
						</div>
						<div class="col-md-2 col-sm-6">
							<div class="form-group">
								<label for="difficultySelect">Difficulty:</label>
								<select class="form-control" id="difficultySelect" name="difficulty">
									<option value="0">Any</option>
									<?php
									/** @var Difficulty $difficulty */
									foreach ( $this->difficulties as $difficulty ) {
										$selected = '';
										if ( $this->difficultyID === $difficulty->getObjectID() ) {
											$selected = ' selected="selected"';
										}

										echo '<option value="'.$difficulty->getObjectID().'"'.$selected.'>'.$this->escapeHtml($difficulty->name).'</option>';
									}
									?>
								</select>
							</div>
						</div>
						<div class="col-md-2 col-sm-6">
							<div class="form-group">
								<label>Game Mode:</label>
								<select class="form-control" name="gamemode">
									<option value="0">Any</option>
									<?php
									/** @var Gamemode $mode */
									foreach ( $this->gamemodes as $mode ) {
										$selected = '';
										if ( $this->gamemodeID === $mode->getObjectID() ) {
											$selected = ' selected="selected"';
										}

										echo '<option value="'.$mode->getObjectID().'"'.$selected.'>'.$this->escapeHtml($mode->name).'</option>';
									}
									?>
								</select>
							</div>
						</div>
						<div class="col-md-3 col-sm-6">
							<div class="form-group">
								<label for="mapSelect">Map:</label>
								<input class="form-control" id="mapSelect" list="maps" name="map" value="<?php echo $this->mapID ? $this->mapID : ''; ?>">
								<datalist id="maps">
									<option value="0">Any</option>
									<?php
									/** @var \data\map\Map $map */
									foreach ( $this->maps as $map ) {
										echo '<option value="'.$map->getObjectID().'">'.$this->escapeHtml($map->name).'</option>';
									}
									?>
								</datalist>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12 text-center">
							<input type="hidden" name="sortField" value="<?php echo $this->escapeHtml($this->sortField); ?>">
							<input type="hidden" name="sortOrder" value="<?php echo $this->escapeHtml($this->sortOrder); ?>">
							<button type="submit" id="search" class="btn btn-primary">Search</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	<?php } ?>

	<div class="table-responsive">
		<table class="table table-hover">
			<thead>
			<tr>
				<?php if ( !$this->hideAuthor ) { ?>
					<th<?php echo $this->sortField === 'author' ? ' class="'.$this->sortOrder.'"': '' ?>>
						<a href="<?php echo LinkHandler::getInstance()->getLink($this->controller, [], 'pageNo='.$this->pageNo.'&sortField=author&sortOrder='.($this->sortField === 'author' && $this->sortOrder === 'ASC' ? 'DESC' : 'ASC').$additionalParameters); ?>">Author</a>
					</th>
				<?php } ?>
				<th<?php echo $this->sortField === 'name' ? ' class="'.$this->sortOrder.'"': '' ?>>
					<a href="<?php echo LinkHandler::getInstance()->getLink($this->controller, [], 'pageNo='.$this->pageNo.'&sortField=name&sortOrder='.($this->sortField === 'name' && $this->sortOrder === 'ASC' ? 'DESC' : 'ASC').$additionalParameters); ?>">Build Name</a>
				</th>
				<th<?php echo $this->sortField === 'gamemodeID' ? ' class="'.$this->sortOrder.'"': '' ?>>
					<a href="<?php echo LinkHandler::getInstance()->getLink($this->controller, [], 'pageNo='.$this->pageNo.'&sortField=gamemodeID&sortOrder='.($this->sortField === 'gamemodeID' && $this->sortOrder === 'ASC' ? 'DESC' : 'ASC').$additionalParameters); ?>">Game Mode</a>
				</th>
				<th<?php echo $this->sortField === 'map' ? ' class="'.$this->sortOrder.'"': '' ?>>
					<a href="<?php echo LinkHandler::getInstance()->getLink($this->controller, [], 'pageNo='.$this->pageNo.'&sortField=map&sortOrder='.($this->sortField === 'map' && $this->sortOrder === 'ASC' ? 'DESC' : 'ASC').$additionalParameters); ?>">Map</a>
				</th>
				<th<?php echo $this->sortField === 'difficulty' ? ' class="'.$this->sortOrder.'"': '' ?>>
					<a href="<?php echo LinkHandler::getInstance()->getLink($this->controller, [], 'pageNo='.$this->pageNo.'&sortField=difficulty&sortOrder='.($this->sortField === 'difficulty' && $this->sortOrder === 'ASC' ? 'DESC' : 'ASC').$additionalParameters); ?>">Difficulty</a>
				</th>
				<th<?php echo $this->sortField === 'likes' ? ' class="'.$this->sortOrder.'"': '' ?>>
					<a href="<?php echo LinkHandler::getInstance()->getLink($this->controller, [], 'pageNo='.$this->pageNo.'&sortField=likes&sortOrder='.($this->sortField === 'likes' && $this->sortOrder === 'ASC' ? 'DESC' : 'ASC').$additionalParameters); ?>">Likes</a>
				</th>
				<th<?php echo $this->sortField === 'views' ? ' class="'.$this->sortOrder.'"': '' ?>>
					<a href="<?php echo LinkHandler::getInstance()->getLink($this->controller, [], 'pageNo='.$this->pageNo.'&sortField=views&sortOrder='.($this->sortField === 'views' && $this->sortOrder === 'ASC' ? 'DESC' : 'ASC').$additionalParameters); ?>">Views</a>
				</th>
				<th<?php echo $this->sortField === 'date' ? ' class="'.$this->sortOrder.'"': '' ?>>
					<a href="<?php echo LinkHandler::getInstance()->getLink($this->controller, [], 'pageNo='.$this->pageNo.'&sortField=date&sortOrder='.($this->sortField === 'date' && $this->sortOrder === 'ASC' ? 'DESC' : 'ASC').$additionalParameters); ?>">Date</a>
				</th>
				<th class="text-right">
					<?php if ( $this->viewMode !== 'grid' ) {
						echo '<a href="'.LinkHandler::getInstance()->getLink($this->controller, ['viewMode' => 'grid'], 'pageNo='.$this->pageNo.'&sortField='.$this->sortField.'&sortOrder='.$this->sortOrder.$additionalParameters).'">';
					} ?>
					<i class="fa fa-th" aria-hidden="true"></i>
					<?php if ( $this->viewMode !== 'grid' ) {
						echo '</a>';
					} ?>

					<?php if ( $this->viewMode !== 'list' ) {
						echo '<a href="'.LinkHandler::getInstance()->getLink($this->controller, ['viewMode' => 'list'], 'pageNo='.$this->pageNo.'&sortField='.$this->sortField.'&sortOrder='.$this->sortOrder.$additionalParameters).'">';
					} ?>
					<i class="fa fa-bars" aria-hidden="true"></i>
					<?php if ( $this->viewMode !== 'list' ) {
						echo '</a>';
					} ?>
				</th>
			</tr>
			</thead>
			<?php if ( $this->viewMode === 'list' ) { ?>
				<tbody>
				<?php
				/** @var \data\build\Build $build */
				foreach ( $this->objects->getObjects() as $build ) {
					echo '<tr>';
					if ( !$this->hideAuthor ) {
						echo '<td>'.$this->escapeHtml($build->author).'</td>';
					}

					echo '<td><a href="'.$build->getLink().'">'.$this->escapeHtml($build->name).'</a></td>
<td>'.$this->escapeHtml($build->getGamemodeName()).'</td>
<td>'.$this->escapeHtml($build->getMap()->name).'</td>
<td>'.$this->escapeHtml($build->getDifficulty()->name).'</td>
<td class="text-right">'.$this->number($build->likes).'</td>
<td class="text-right">'.$this->number($build->views).'</td>
<td class="text-right" colspan="2">'.$build->getDate().'</td>
</tr>';
				}
				?>
				</tbody>
			<?php } ?>
		</table>
	</div>
	<?php
	if ( $this->viewMode === 'grid' ) {
		$objects = $this->objects->getObjects();
		while ( $builds = array_splice($objects, 0, 3) ) { // 3 items per row
			echo '<div class="row">';
			foreach ( $builds as $build ) {
				?>
				<div class="col-md-4">
					<h3 class="text-center"><a href="<?php echo $build->getLink(); ?>"><?php echo $this->escapeHtml($build->name); ?></h3>
					<div class="row">
						<div class="col-md-7">
							<img class="img-responsive" style="height: 200px" src="<?php echo $build->getThumbnail(); ?>">
						</div>
						<div class="col-md-5">
							<?php
							echo '<h4><p>'.$this->escapeHtml($build->getMap()->name).'</p>';
							echo '<p>'.$this->escapeHtml($build->getDifficulty()->name).' ('.$this->escapeHtml($build->getGamemodeName()).')<p>';
							echo '<p><small>Likes:</small> '.$this->number($build->likes).'</p>';
							echo '<p><small>Views:</small> '.$this->number($build->views).'</p>';
							echo '<p>'.$build->getDate().'</p>';
							echo '<p>'.$this->escapeHtml($build->author).'</p></h4></a>';
							?>
						</div>
					</div>
				</div>
				<?php
			}
			echo '</div>';
		}
	}

	$this->renderPages([
		'controller' => $this->controller,
		'url'        => 'pageNo=%d&sortField='.$this->sortField.'&sortOrder='.$this->sortOrder.$additionalParameters,
		'print'      => true,
	]);
	?>
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