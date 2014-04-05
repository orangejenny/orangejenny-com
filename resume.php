<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<?php
	$json = json_decode(file_get_contents("resume.json"));
	$skillsets = $json->skillsets;
	$experiences = $json->experiences;
?>

<html xmlns='http://www.w3.org/1999/xhtml'>
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
	<title>Jennifer Schweers</title>
	<link rel="stylesheet" type="text/css" href="css/resume.css" />
</head>
<body>

	<div class="right">
		<h3>Hello, I'm <span class="emphasize">Jenny</span></h3>
		<ul class="horizontal">
			<li>Jennifer Schweers</li>
			<li>orange.jenny@gmail.com</li>
			<li>orangejenny.com</li>
		</ul>

	<?php foreach ($skillsets as $skillset => $skills) { ?>
		<h3>
			<?php echo preg_replace('/ ([^ ]+)$/', " <span class='emphasize'>$1</span>", $skillset) ?>
		</h3>
		<ul class="horizontal right">
		<?php foreach ($skills as $skill) { ?>
			<li><?php echo $skill ?></li>
		<?php } ?>
		</ul>
	<?php } ?>
	</div>

	<hr>

	<div class="left">
		<!--h3>I have the following <span class="emphasize">experience</span></h3-->
		<?php $degree = 0 ?>
		<?php foreach ($experiences as $experience) { ?>
			<?php if (!$degree && $experience->degree) { ?>
			 	<!--h3>And have been <span class="emphasize">well-educated</span></h3-->
			<?php } ?>
			<?php $degree = $experience->degree ?>
			<h4><?php echo $experience->heading ?></h4>
			<ul class="horizontal">
				<?php foreach ($experience->subheaders as $subheader) { ?>
					<li><?php echo $subheader ?></li>
				<?php } ?>
			</ul>
			<ul class="vertical">
			<?php foreach ($experience->bullets as $bullet) { ?>
				<li><?php echo $bullet ?></li>
			<?php } ?>
			</ul>
		<?php } ?>
	</div>

</body>
</html>
