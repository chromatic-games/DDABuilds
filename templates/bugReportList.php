<?php

use data\bugReport\BugReport;
use system\Core;

$isMaintainer = Core::getUser()->isMaintainer();
?>
<div class="container">
	<?php if ( $this->objects->count() ) { ?>
		<table class="table table-bordered">
			<thead>
			<tr>
				<?php
				if ( $isMaintainer ) {
					echo '<td style="width:1%;">Action</td>';
				}
				?>
				<td style="width:15%;">Created</td>
				<td>Title</td>
				<td style="width:15%;">Status</td>
			</tr>
			</thead>
			<tbody>
			<?php
			/** @var BugReport $bugReport */
			foreach ( $this->objects as $bugReport ) {
				echo '<tr>';

				if ( $isMaintainer ) {
					echo '<td>'.($bugReport->status === BugReport::STATUS_OPEN ? '<button class="btn btn-primary btn-close" data-id="'.$bugReport->getObjectID().'"><i class="fa fa-lock"></i></button>' : '').'</td>';
				}

				echo '<td>'.$bugReport->getDate().'</td>
					<td><a href="'.$bugReport->getLink().'">'.$this->escapeHtml($bugReport->getTitle()).'</a></td>
					<td> '.$bugReport->getStatus().' </td>
				</tr>';
			}
			?>
			</tbody>
		</table>
		<?php

		$this->renderPages([
			'controller' => $this->controller,
			'url'        => 'pageNo=%d&sortField='.$this->sortField.'&sortOrder='.$this->sortOrder,
			'print'      => true,
		]);
	}
	else {
		echo '<div class="alert alert-info">no issues</div>';
	} ?>
</div>

<?php if ( $isMaintainer ) { ?>
	<script>
		$(document).ready(function () {
			$('.btn-close').on('click', function () {
				$.post('?ajax', {
					className: '\\data\\bugReport\\BugReportAction',
					actionName: 'close',
					objectIDs: [$(this).data('id')]
				}).done((e) => {
					window.location.reload();
				}).fail((err) => {
					console.log(err.responseJSON);
				});
			});
		});
	</script>
<?php } ?>
