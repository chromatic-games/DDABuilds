<?php

use system\exception\IExtraInformationException;
use system\util\StringUtil;
use function functions\exception\sanitizePath;
use function functions\exception\sanitizeStacktrace;

$exceptionTitle = 'An error has occurred';
$exceptionSubtitle = 'Internal error code: <span class="exceptionInlineCodeWrapper"><span class="exceptionInlineCode">'.$this->exceptionID.'</span></span>';
$exceptionExplanation = '<h3 class="exceptionSubtitle">What happened?</h3>
<p class="exceptionText">An error has occured while trying to handle your request and execution has been terminated. Please forward the above error code to the site administrator.</p>
<p class="exceptionText">Notice: The error code was randomly generated and has no use beyond looking up the full message.</p>';

echo '<div class="container">';
if ( DEBUG_MODE ) {
	$exceptions = [];
	$current = $this->exception;
	do {
		$exceptions[] = $current;
	} while ( $current = $current->getPrevious() );

	$first = true;
	$e = array_pop($exceptions);
	do {
		?>
		<style>
			.exceptionStacktraceCall + .exceptionStacktraceFile {
				margin-top: 5px;
			}

			.exceptionStacktraceCall {
				padding-left: 40px;
			}

			.exceptionErrorDetails,
			.exceptionStacktrace {
				list-style-type: none;
			}
		</style>
		<div>
			<ul class="exceptionErrorDetails">
				<li>Error Type: <?php echo StringUtil::encodeHTML(get_class($e)); ?></li>
				<li>Error Message: <?php echo StringUtil::encodeHTML($e->getMessage()); ?></li>
				<?php if ( $e->getCode() ) { ?>
					<li>Error Code: <?php echo intval($e->getCode()); ?></li>
				<?php } ?>
				<li>File: <?php echo StringUtil::encodeHTML(sanitizePath($e->getFile())); ?> (<?php echo $e->getLine(); ?>)</li>
				<?php
				if ( $e instanceof IExtraInformationException ) {
					foreach ( $e->getExtraInformation() as list( $key, $value ) ) {
						?>
						<li><?php echo StringUtil::encodeHTML($key); ?>: <?php echo StringUtil::encodeHTML($value); ?></li>
						<?php
					}
				}
				?>
				<li>
					Stack Trace:
					<ul class="exceptionStacktrace">
						<?php
						$trace = sanitizeStacktrace($e);
						// @formatter:off
						for ($i = 0, $max = count($trace);$i < $max;$i++) {
						// @formatter:on
						?>
						<li class="exceptionStacktraceFile"><?php echo '#'.$i.' '.StringUtil::encodeHTML($trace[$i]['file']).' ('.$trace[$i]['line'].')'.':'; ?></li>
						<li class="exceptionStacktraceCall">
						<?php
						echo $trace[$i]['class'].$trace[$i]['type'].$trace[$i]['function'].'(';
						echo implode(', ', array_map(function ($item) {
							switch ( gettype($item) ) {
								case 'integer':
								case 'double':
									return $item;
								case 'NULL':
									return 'null';
								case 'string':
									return "'".addcslashes(StringUtil::encodeHTML($item), "\\'")."'";
								case 'boolean':
									return $item ? 'true' : 'false';
								case 'array':
									$keys = array_keys($item);
									if ( count($keys) > 5 ) {
										return "[ ".count($keys)." items ]";
									}

									return '[ '.implode(', ', array_map(function ($item) {
											return $item.' => ';
										}, $keys)).']';
								case 'object':
									return get_class($item);
								case 'resource':
									return 'resource('.get_resource_type($item).')';
							}

							throw new \LogicException('Unreachable');
						}, $trace[$i]['args']));
						echo ')</li>';
						}
						?>
					</ul>
				</li>
			</ul>
		</div>
		<?php
		$first = false;
	} while ( $e = array_pop($exceptions) );
}
else {
	echo '<h2>'.$exceptionTitle.'</h2><small>'.$exceptionSubtitle.'</small>';
	echo $exceptionExplanation;
}

echo '</div>';