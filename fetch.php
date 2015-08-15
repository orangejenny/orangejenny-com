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
	echo "<div class='detail' id='thumb_${index}'>";

	echo "<h3>$element->name</h3>";

	echo "<div class='column'>";
	if ($element->detail) {
		echo $element->detail;
	}
	else {
		echo  $element->brief;
	}
	echo "</div>";

	echo projectLinks($element, $path);

	if (isset($element->gallery)) {
		foreach ($element->gallery as $index => $g) {
			echo projectLinks($g, $path);
		}
	}
		
	if (isset($element->image)) {
		echo "<img src='$path/$element->image' alt=\"$element->name\" class='single-image'/>";
	}
	elseif (isset($element->gallery)) {
		foreach ($element->gallery as $index => $g) {
			$width = 500;
			if (isset($g->width)) {
				$width = $g->width;
			}
			echo "<div class='captioned-image' style='width: ${width}px;'>";
			echo "<span>" . $g->detail . "</span>";
			echo "<img src='$path/$g->image' alt=\"$g->detail\"/>";
			echo "</div>";
		}
	}

	echo "</div>";


function projectLinks($element, $path) {
	$links = array();
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
			array_push($links, "<div class='$class'><a href='$href'$extra>$text</a>$extra_text</div>");
		}
	}
	if (count($links)) {
		return "<div class='links'>" . implode($links) . "</div>";
	}
	return "";
}

?>
