<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<?php
	if (isset($_REQUEST["new"]) && $_REQUEST["new"] == 1) {
		$json_file = "content-new.json";
	}
	else {
		$json_file = "content.json";
	}
	$content = json_decode(file_get_contents($json_file));
?>
<html xmlns='http://www.w3.org/1999/xhtml'>
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
	<title>Jennifer Schweers</title>

	<link rel="stylesheet" type="text/css" href="css/portfolio.css" />

	<script type="text/javascript" src="js/jquery-1.3.2.js"></script>
	<script type="text/javascript" src="js/underscore-min.js"></script>

	<script type="text/javascript">
	<!--
		var moveTooltip = function(event) {
			$('#tooltip').css('left', event.pageX + 5);
			$('#tooltip').css('top', event.pageY + 8);
		};
		var showTooltip = function(title, description) {
			$("#tooltip").html("<div style='font-weight:bold;'>" + title + "</div>" + description);
			$("#tooltip").show();
		};
		var hideTooltip = function() {
			$("#tooltip").hide();
		};

		$(document).ready(function() {
			var project_brief = [];
			var templates = {};
			_.each(jQuery("script[type='text/template']"), function(template) {
				var $template = jQuery(template);
				templates[$template.attr("name")] = _.template($template.html());
			});

			$.getJSON("content.json", function(data) {
				// Intro content
				_.each(data.paragraphs, function(p) {
					jQuery(".columns").append(templates.paragraph({
						text: p,
					}));
				});

				// Project content
				_.each(data.projects, function(project, index) {
					project_brief[project.name] = project.brief;

					jQuery("#thumbs").append(templates.thumb(_.extend({}, project, {
						left: index * 900 / (data.projects.length + 1),
					})));
				});

				// Tooltip events
				$('#thumbs, #juicy img').mousemove(function(event) {
					moveTooltip(event);
				});
				$(".thumb").hover(function() {
					var label = $(this).find(".thumb_label").text();
					showTooltip(label, project_brief[label]);
				}, hideTooltip);
				$("#juicy img").hover(function() {
					showTooltip("Why \"orange\"?", "Tangerines, pumpkins, and apricots - what's not to love?");
				}, hideTooltip);

				// TODO: event for clicking on project (grab data & stuff in template)
			});
		});
	//-->
	</script>
	<script type="text/template" name="paragraph">
		<div class="column"><%= text %></div>
	</script>
	<script type="text/template" name="thumb">
		<div class='thumb' style='left: <%= left %>px; background-image: url(content/<%= folder %>/thumb.png)'>
			<div class='thumb_veil'></div>
			<div class='thumb_label'><%= name %></div>
		</div>
	</script>
</head>

<body>
	<div id="main-wrapper">
		<div id="main">
			<div id="juicy">
				<img src='juicy.png'>
			</div>
			<div id="not-juicy">
				<div id="header">
					<span id="name">Jennifer Schweers</span>
					<span id="contact">
						orange.jenny {at} gmail
						/
						<a href='JSchweers_Resume.pdf'>resume</a>
						/
						<a href='https://github.com/orangejenny'>github</a>
					</span>
				</div>
				<h3>Well, hello.</h3>
				<div class='columns'></div>
			</div>
		</div>
	</div>

	<div id="thumbs">
		<?php
			$total = count($content->projects);
			foreach (array() as $i => $project) {#array_reverse($content->projects) as $i => $project) {
				$i = $total - $i - 1;
				$left = $i * 900 / ($total + 1);
				$style = "background-image:url(content/$project->folder/thumb.png); left: ${left}px;";
				$div_html = "<div class='thumb' rel='#thumb_${i}' style='$style'>";
				$div_html .= "<div class='thumb_veil'></div>";
				$div_html .= "<div class='thumb_label'>$project->name</div>";
				$div_html .= "</div>";
				echo $div_html . "\n";
			}
		?>
	</div>

	<div id="content"></div>

	<script type="text/javascript">
		var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
		document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
	</script>
	<div id="tooltip"></div>
</body>
</html>
