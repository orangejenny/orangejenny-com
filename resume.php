<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<?php
	$json = json_decode(file_get_contents("resume.json"));
	$skillsets = $json->skillsets;
	$content = $json->content;
   $notes = $json->notes;
?>

<html xmlns='http://www.w3.org/1999/xhtml'>
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
	<title>Jennifer Schweers</title>
	<link rel="stylesheet" type="text/css" href="css/resume.css" />
</head>
<body>

	<div class="right" id="intro">
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

	<div class="left" id="content">
		<?php foreach ($content as $heading => $items) { ?>
	      <h3><?php echo $heading ?></h3>
			<?php foreach ($items as $item) { ?>
				<h4>
					<?php echo $item->heading ?>
					<?php if ($item->heading_annotation) { ?>
						<span class="muted"><?php echo $item->heading_annotation ?></span>
					<?php } ?>
				</h4>
				<?php foreach ($item->subsections as $subsection) { ?>
					<ul class="horizontal">
						<?php foreach ($subsection->subheadings as $subheading) { ?>
							<li><?php echo $subheading ?></li>
						<?php } ?>
					</ul>
					<ul class="vertical">
						<?php foreach ($subsection->bullets as $bullet) { ?>
							<li>
								<?php echo $bullet->text ?>
								<?php if ($bullet->bullets) { ?>
									<ul>
										<?php foreach ($bullet->bullets as $bullet) { ?>
											<li><?php echo $bullet ?></li>
										<?php } ?>
									</ul>
								<?php } ?>
							</li>
						<?php } ?>
					</ul>
				<?php } ?>
			<?php } ?>
		<?php } ?>
	</div>

	<div>
		<?php foreach ($notes as $note) { ?>
			<div><?php echo $note ?></div>
		<?php } ?>
	</div>

</body>
</html>
