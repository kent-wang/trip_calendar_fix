<?php 
$content = file_get_contents($_GET[feed]); 
if ($content !== false) {
	$lines = explode("\n", $content);
	for ($i = 0; $i < count($lines); $i++) {
		if (preg_match('/^DT(START|END):(\w+)/', $lines[$i], $matches)) {
			$lines[$i] = "DT$matches[1];TZID=$_GET[timezone]:$matches[2]";
		}
	}
	echo implode("\n", $lines);
} else {
	echo 'Error retrieving calendar';
}
?>