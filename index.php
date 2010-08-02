<?php
require_once('poll.php');
$channel = time();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<title>Cometi Poll</title>
		<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
		<script src="../mootools.js" type="text/javascript"></script>
		<script src="../mootools-more.js" type="text/javascript"></script>
		<script src="poll.js" type="text/javascript"></script>
		<style type="text/css">
		fieldset { }
		.container {
			margin-bottom: 1em;
			padding-top: 1.5em;
			position: relative;
		}
		.container span,
		.container b {
			position: absolute;
			top: 0px;
		}
		.container span {
			left: 5px;
		}
		.container b {
			right: 5px;
		}
		.bar {
			width: 400px;
			height: 15px;
			font-size: 10px;
			border: 1px solid #000000;
			vertical-align: middle;
		}
		.bar-result {
			height: 15px;
			width: 0px;
			background-color: #999999;
		}
		.success	{ color: #090; }
		.error		{ color: #900; }
		</style>
	</head>
	<body>
		<fieldset style="float:left;">
			<legend><?= $p['name']; ?></legend>

			<form id="poll-form" action="action.php" method="POST">
				<input type="hidden" name="channel" value="<?php echo $channel; ?>" />
				<input type="hidden" name="poll" value="<?php echo $p['id']; ?>" />

				<?php foreach( $c['data'] as $choice ){
					echo "<label>\n",
						'<input type="radio" name="vote" value="'. $choice['id'] .'" />',
						$choice['name'],
					'</label><br />';
				} ?>
				<input type="submit" value="Vote" />
			</form>
			<span id="poll-response">&nbsp;</span>
		</fieldset>

		<fieldset style="float:right;">
			<legend>Results</legend>
				<?php foreach( $c['data'] as $choice ){
					$size = round( $choice['votes'] * 400 / $total );
					echo '<div id="choice-'. $choice['id'] .'" class="container">',
						'<span>', $choice['name'], '</span>',
						'<b>&nbsp;', $choice['_votes'], '</b>',
						'<div class="bar">',
							'<div id="bar-'. $choice['id'] .'" class="bar-result" astyle="width:'. $size .'px;"></div>',
						'</div>',
					"</div>";
				} ?>
		</fieldset>
			
	</body>
	<script>
		CPoll.channel = <?php echo $channel; ?>;
		CPoll.data = {
			total: <?php echo $total; ?>,
			choices: <?php echo json_encode( $c['data'] ); ?>
		};
	</script>
</html>
