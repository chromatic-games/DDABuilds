<?php
/** @var BugReport $bugReport */

use data\bugReport\BugReport;
use system\Core;
use system\steam\Steam;
use system\util\StringUtil;

$bugReport = $this->bugReport;
$isMaintainer = Core::getUser()->isMaintainer();
$hasMenu = false;
?>
	<div class="container">
		<?php
		if ( Core::getUser()->isMaintainer() && $bugReport->status === BugReport::STATUS_OPEN ) {
			$hasMenu = true;
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
	</div>

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