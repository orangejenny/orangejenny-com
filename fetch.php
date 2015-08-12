<?php
	if (isset($_REQUEST["new"]) && $_REQUEST["new"] == 1) {
		$json_file = "content-new.json";
	}
	else {
		$json_file = "content.json";
	}
	// TODO: stupid to parse whole file for each individual project
	$json = json_decode(file_get_contents($json_file));
	$index = $_POST["index"];
	$element = $json->projects[$index];

	$path = "content";
	if (isset($element->folder)) {
		$path .= "/$element->folder";
	}
	echo "<div class='project' id='thumb_${index}'>";
	echo "<div class='detail'>";

	echo "<h3>$element->name</h3>";
	if ($element->detail) {
		echo $element->detail;
	}
	else {
		echo  $element->brief;
	}
	echo getProjectDetails($element, $path);
		
	echo "<div class='gallery_detail' style='margin-top:20px;'>";
	$style = "";
	if (isset($element->gallery)) {
		foreach ($element->gallery as $index => $g) {
			echo "<div$style>";
			echo "<h3>$g->name</h3>";
			echo $g->detail;
			echo getProjectDetails($g, $path);
			echo "</div>";
			$style = " style='display:none;'";
		}
	}
	echo "</div>";

	echo "</div>";

	echo "<div class='rdetail'>";
	if (isset($element->image)) {
		$lr_margin = 0;
		$tb_margin = 0;
		if (isset($element->width)) {
			$lr_margin = (500 - $element->width) / 2;
		}
		if (isset($element->height)) {
			$tb_margin = (500 - $element->height) / 2;
		}
		echo "	<img src='$path/$element->image' alt=\"$element->name\" style='margin:${tb_margin}px ${lr_margin}px;' 	/>";
	}
	elseif (isset($element->gallery)) {
		$prev_offset = 500 / 2 - 55 * count($element->gallery) / 2 - 5 - 20;
		$next_offset = 500 / 2 + 55 * count($element->gallery) / 2 + 5;
			
		echo "<div class='gallery_menu'>";
		#echo "<a class='prevPage browse left' style='left:${prev_offset}px;'></a>";
		foreach ($element->gallery as $g) {
			$thumbfile = preg_replace('/\.[A-Za-z]{3}/', '.png', $g->image);
			if (preg_match("/\//", $thumbfile)) {
				$thumbfile = preg_replace("/\//", "/thumb_", $thumbfile);
			}
			else {
				$thumbfile = "thumb_" . $thumbfile;
			}
			echo "<img src='$path/$thumbfile' alt=\"thumbnail for $g->name\" />";
		}
		#echo "<a class='nextPage browse right' style='left:${next_offset}px;'></a>";
		echo "</div>";

   				echo "<div class='scrollable' style='vertical-align:middle;'>";
  				echo "<div class='items'>";
		foreach ($element->gallery as $g) {
			$lr_margin = 50;
			if (isset($g->width)) {
				$lr_margin += (425 - $g->width) / 2;
			}
			$tb_margin = 0;
			if (isset($g->height)) {
				$tb_margin += (425 - $g->height) / 2;
			}
			echo "<img src='$path/$g->image' alt=\"$g->name\" style='max-width:425px;max-height:425px;margin:${tb_margin}px ${lr_margin}px;'/>";
		}
		echo "</div>";
		echo "</div>";
	}
	echo "</div>";			// close rdetail
	echo "</div>";			// close apple_overlay black


function getProjectDetails($element, $path) {
	$rv = "<br /><br />";
		
	// Links/downloads
	if (isset($element->links)) {
		foreach ($element->links as $link) {
			$extra = "";
			$extra_text = "";
			if (strpos($link->url, "http") === 0) {
				$class = "icon_link";
				$href = $link->url;
				$extra = " target='_blank'";
				$text = "$link->name";
				$extra_text = "<br />(opens in new window)";
			}
			else {
				$class = "icon_download";
				$href = "$path/$link->url";
				$ext = explode(".", $link->url);
				$ext = $ext[1];
				if ($ext == "pdf" || $ext == "dv" || $ext == "mov") {
					$size = filesize($href);
					$units = "bytes";
					$units_array = array("KB", "MB", "GB", "TB");
					while ($size > 1024 && count($units_array)) {
						$size /= 1024;
						$units = array_shift($units_array);
					}
					if ($size < 100) {
						$size = number_format($size, 1);
					}
					else {
						$size = number_format($size, 0);
					}
					$text = "Download $link->name <span style='white-space:nowrap;'>(" . strtoupper($ext) . ", $size $units)</span>";
				}
				elseif ($ext == "swf" || $ext == "html") {
					$extra = " target='_blank'";
					$text = "Try $link->name";
					$extra_text = "<br />(opens in new window)";
				}
				else {
					$text = "UNKNOWN FILETYPE ($link->url)";
				}
			}
			$rv .= "<div class='$class'><a href='$href'$extra>$text</a>$extra_text</div>";
		}
	}
	return $rv;
}

?>
