<?php /**/ ?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<?php
	$json_file = "content.json";
	$json = json_decode(file_get_contents($json_file));
	$num_sections = count($json);
?>
<html xmlns='http://www.w3.org/1999/xhtml'>
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
	<title>Parley</title>

	<link rel="stylesheet" type="text/css" href="parley.css" />

	<script type="text/javascript" src="jquery-1.3.2.js"></script>
	<script type="text/javascript">
		$(document).ready(function() {
			$(".menu li").click(function() {
				$(".selected").removeClass("selected");
				$(this).addClass("selected");
				
				var index = $(".menu li").index($(this));
				if ($(".submenu:visible").length > 0) {
					$(".submenu:visible").hide();
					$(".submenu:eq(" + index + ")").show();
					$(".submenu:eq(" + index + ") li:first").click();
				}
				else {
					$(".submenu:eq(" + index + ")").show();
					<?php
						if (isset($_REQUEST["v"]) && $_REQUEST["v"] == 1) { 
							echo "$(\".submenu:visible li:last\").click();";
						}
						else {
							echo "$(\".submenu:visible li:first\").click();";
						}
					?>
					//$(".submenu:visible li:first").click();
				}
			});
			
			$(".submenu li").click(function() {
				$(".submenu li.selected").removeClass("selected");
				$(this).addClass("selected");
				$("#left").html("<img src='images/spinner.gif' />");
				$("#right").html("<img src='images/spinner.gif' />");
				
				var sectionName = $(".menu .selected").text();
				var pageIndex = $(".submenu:visible li").index($(this));
				$.post("fetch.php", {section : sectionName, page : pageIndex}, function(data) {
					$("#left").html(data.left);
					$("#right").html(data.right);
				}, "json");
			});
			
			$(".logo").click(function() {
				$(".menu li:first").click();			// light up "solution" as the main page
			});
		
			$(".logo").click();
		});
	</script>
</head>

<body>
	<!--pre><?php print_r($json); ?></pre-->
	<div id="outer">
		<div id="header">
			<ul class="menu">
				<?php
					foreach ($json as $name => $section) {
						echo "<li>$name</li>";
					}
				?>
			</ul>
			<span class="logo">
				&thinsp;<img src='images/logo.png' style='margin-top:0px' />
			</span>
			<hr style="background-color:#e6e6e6;color:#e6e6e6;height:10px;border-width:0;margin-top:20px;margin-bottom:20px;" />
			<?php
				foreach ($json as $name => $section) {
					echo "<ul class='submenu'>";
					foreach ($section as $page) {
						echo "<li>$page->name</li>";
					}
					echo "</ul>";
				}
			?>
		</div>
	
		<div id="body">
			<div id="right"></div>
			<div id="left"></div>
		</div>
	
		<div id="footer">
			<table style="width:100%;">
				<tr>
					<th style="width:30%;">About</th>
					<th style="width:50%;">Process</th>
					<th style="width:20%;">Team</th>
				</tr>
				<tr>
					<td>
						Spring 2010<br />
						Basic Interaction Design<br />
						Carnegie Mellon University
					</td>
					<td>
						This site documents a design for a public interactive surface, <br />tailored to its location and based on research of user needs.
					</td>
					<td>
						<a href='http://www.amybickerton.com'>Amy Bickerton</a><br />
						<a href='http://matthewmorosky.com/'>Matthew Morosky</a><br />
						<a href='http://orangejenny.com'>Jenny Schweers</a><br />
						<a href='http://jesseventicinque.com/'>Jesse Venticinque</a><br />
					</td>
				</tr>
			</table>
		</div>
	</div>
</body>
</html>