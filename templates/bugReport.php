<?php

use data\bugReport\BugReport;
use data\bugReport\comment\BugReportComment;
use system\cache\runtime\SteamUserRuntimeCache;
use system\Core;
use system\steam\Steam;
use system\util\StringUtil;

/** @var BugReport $bugReport */
$bugReport = $this->bugReport;
$hasMenu = Core::getUser()->isMaintainer() && $bugReport->status === BugReport::STATUS_OPEN;
?>
	<div class="container">
		<?php
		if ( $hasMenu ) {
			echo '<div class="text-right"><button class="btn btn-primary btn-close">Close</button></div>';
		}
		?>
		<table class="table table-bordered marginTop">
			<tr>
				<td>Status</td>
				<td><?php echo $bugReport->getStatus(); ?></td>
			</tr>
			<tr>
				<td>Created</td>
				<td><?php echo $bugReport->getDate(); ?></td>
			</tr>
			<tr>
				<td>Created by</td>
				<td><?php echo Steam::getInstance()->getDisplayName($bugReport->steamID); ?></td>
			</tr>
			<tr>
				<td style="width:10%;">Title</td>
				<td><?php echo $this->escapeHtml($bugReport->getTitle()); ?></td>
			</tr>
			<tr>
				<td>Description</td>
				<td><?php echo StringUtil::removeInsecureHtml($bugReport->description); ?></td>
			</tr>
		</table>

<?php
	if ( isset($this->error) ) {
		echo '<div class="alert alert-danger">'.$this->error.'</div>';
	}

	echo '<form method="post">
			<dl>
				<dt>Comment</dt>
				<dd>
					<textarea id="description" name="description"></textarea>
				</dd>
			</dl>
	
			<div class="text-center">
				<input type="submit" class="btn btn-primary" value="Save" />
			</div>
		</form>';

/** @var BugReportComment $object */
foreach ( $this->objects as $object ) {
	$steamUser = SteamUserRuntimeCache::getInstance()->getObject($object->steamID);
	echo '<table class="table table-bordered marginTop">
<tr><td> '.$steamUser->name.' ('.$object->getDate().') </td></tr>
<tr><td> '.StringUtil::removeInsecureHtml($object->description).' </td></tr>
</table>';
}

echo '</div><div class="text-center">';
$this->renderPages([
	'controller'           => 'BugReport',
	'controllerParameters' => [
		'object' => $bugReport,
	],
	'url'                  => 'pageNo=%d&sortField='.$this->sortField.'&sortOrder='.$this->sortOrder,
	'print'                => true,
]);
echo '</div>';

?>

	<script>
		$(document).ready(function () {
			CKEDITOR.replace('description');
		});
	</script>

<?php if ( $hasMenu ) { ?>
	<script>
		$(document).ready(function () {
			$('.btn-close').on('click', function () {
				$.post('?ajax', {
					className: '\\data\\bugReport\\BugReportAction',
					actionName: 'close',
					objectIDs: [<?php echo $bugReport->getObjectID(); ?>]
				}).done((e) => {
					window.location.reload();
				}).fail((err) => {
					console.log(err.responseJSON);
				});
			});
		});
	</script>
<?php } ?>