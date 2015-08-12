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
	<link rel="stylesheet" type="text/css" href="css/scrollable.css" />

	<script type="text/javascript" src="js/jquery-1.3.2.js"></script>
	<script type="text/javascript" src="js/tools.scrollable-1.1.2.js"></script>
	<script type="text/javascript" src="js/tools.scrollable.navigator-1.0.2.js"></script>
	<script type="text/javascript" src="js/tools.expose-1.0.5.js"></script>

	<script type="text/javascript">
	<!--
		var project_brief = new Array();
		<?php
			$numProjects = 0;
			foreach ($content->projects as $i => $project) {
				$numProjects++;
				if (isset($project->brief)) {
					$text = $project->brief;
				}
				echo "project_brief[\"$project->name\"] = \"$text\";\n";
			}
		?>

		var loading_expose = null;
		var projects_loaded = 0;
		function updateLoading() {
			projects_loaded++;
			$("#loading_text").html(projects_loaded + "/<?php echo $numProjects; ?> projects loaded");
			
			if (projects_loaded == <?php echo $numProjects; ?>) {
				if (loading_expose) {
					loading_expose.close();
					$("#loading").hide();
				}
			
				// Set up content display
				/*$("div[rel]").overlay({
					//effect:'apple',
					expose:{
						color: '#ffffff',
						opacity:0.75,
						loadSpeed:'fast',
						closeSpeed:'fast'
					}
				});*/

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

				$('#thumbs, #juicy img').mousemove(function(event) {
					moveTooltip(event);
				});
				$("div[rel]").hover(function() {
					var label = $(this).find(".thumb_label").text();
					showTooltip(label, project_brief[label]);
				}, hideTooltip);
				$("#juicy img").hover(function() {
					showTooltip("Why \"orange\"?", "Tangerines, pumpkins, and apricots - what's not to love?");
				}, hideTooltip);
			
				// Initialize scrollable gallery
				$("div.scrollable").scrollable({
					size: 1,
					clickable: false,
					onStart: function() { 
						var detail = this.getRoot().parents(".project").children(".detail");
						var index = this.getPageIndex() + 1;
						$(".gallery_detail > div:visible").fadeOut("fast", function() {
							detail.children(".gallery_detail").children("div:nth-child(" + index + ")").fadeIn("fast");
						});
					}
				}).navigator({
					navi: ".gallery_menu", 
					naviItem: "img", 
					activeClass: 'gallery_selected'
				});
			}
		}

		$(document).ready(function() {
			// Loading div
			$("#loading").show();
			loading_expose = $("#loading").expose({
				api: true,
				loadSpeed: 0,
				closeSpeed: 'slow',
				onBeforeClose: function() {
					$("#loading").hide();
				}
			}).load();
		
			// load content
			<?php for ($i = 0; $i < count($content->projects); $i++) { ?>
				$.ajax({
					type: "POST",
					url: "fetch.php",
					data: "index=<?php echo $i; ?><?php if (isset($_REQUEST["new"]) && $_REQUEST["new"] == 1) { echo "&new=1"; } ?>",
					success: function(data) {
						$("#content").append(data);
						updateLoading();
					},
					error: function(data) {
						$(".thumb[rel='#thumb<?php echo $i; ?>']").remove();
						updateLoading();
					}
				});
			<?php } ?>

		});
	//-->
	</script>
</head>

<body>
	<div id="loading">
		<div id="loading_text">0/0 projects loaded</div>
	</div>
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
				<?php
					foreach ($content->paragraphs as $p) {
						echo "<div class='column'>" . $p . "</div>";
					}
				?>
				<!--div class="column-footer">
					<h3>Why "orange"?</h3>
					<div>
						<a href="http://en.wikipedia.org/wiki/Shades_of_orange">
							Tangerines, pumpkins, and apricots
						</a> - what's not to love?
					</div>
				</div-->
			</div>
			<div style="clear: both;"></div>
		</div>
	</div>

	<div id="thumbs">
		<?php
			$total = count($content->projects);
			foreach (array_reverse($content->projects) as $i => $project) {
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
