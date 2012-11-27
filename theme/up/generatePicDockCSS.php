<?php
/*
 * change this to actual directory of moodle
 */
$languagedirectory = 'C:\Projekt\trunk\dev\proj\moodle\src\moodledata\lang';
mb_internal_encoding( 'UTF-8' );

$documentation = '/*NOTE: Bilder für die Dockleiste<br />
Bilder für die Dockleiste müssen so abgelegt werden, dass die Bilder mit [Titel].jpg gefunden werden<br />
Zusätzlich muss für jedes Bild eine CSS-Regel angelegt werden vergleiche die Regel für Navigation:<p />

.dock_item_Navigation_picture  {<br />
    background-image:url([[pix:theme|Navigation]]);<br />  
    width: 35px !important;<br />
    height: 35px !important;<br />    
}<p />

1) .dock_item_Navigation_picture muss z.B. zu .dock_item_Einstellungen_picture umbenannt werden<br />
2)  background-image:url([[pix:theme|Navigation]]);  muss zu  background-image:url([[pix:theme|Einstellungen]]);  umbenannt werden*/<p />';



/*
 * function to get Line with $str as pattern to search
 */
function getLineWithString($fileName, $str) {
    $lines = file($fileName);
    foreach ($lines as $lineNumber => $line) {
        if (strpos($line, $str) !== false) {
            return $line;
        }
    }
    return -1;
}

function initlializeParameters($dir) {
	global $blocks;
	global $languages;
	$blocks = array();
	$languages = array();
	if($handle = opendir($dir)) {
		while (false != ($entry = readdir($handle))) {
			if(preg_match("/^[a-z]/",$entry)) {
				array_push($languages,$entry);
			}
		}
	}
	if($handle = opendir($dir.'\\de')) {
		while (false != ($entry = readdir($handle))) {
			if(preg_match("/^block_/",$entry)) {
				array_push($blocks,$entry);
			}
		}
	}
}

// initialize parameters
$pattern = '$string[\'pluginname';
$not_existing_styles = array();
initlializeParameters($languagedirectory);

echo $documentation;

// iterate over languages for every block and get the pluginnames
foreach($blocks as $block) {
	$blockname = explode(".",$block);
	echo	"/* ".$blockname[0]." <br />
			--------------------------*/ <br />";
	$count = 0;
	foreach($languages as $language) {
		$count++;
		$current_working_directory = $languagedirectory.'\\'.$language;
		$current_working_file = $current_working_directory.'\\'.$block;
		
		if(file_exists($current_working_file)) {
			$line = getLineWithString($current_working_file, $pattern);
			if ($line != -1 && (count($languages)==$count)) {
				$parts = explode("'",$line);
				$name = $parts[3];
				$name = str_replace(" ","_",$name);
				echo ".dock_item_".$name."_picture {<br />";
			} elseif ($line != -1) {
				$parts = explode("'",$line);
				$name = $parts[3];
				$name = str_replace(" ","_",$name);
				echo ".dock_item_".$name."_picture,<br />";
				
			} else {
				array_push($not_existing_styles,$block);
				array_push($not_existing_styles,$language);				
			}
		}
	}
	$picname = str_replace("block_","",$block);
	$picname = str_replace(".php","",$picname);
	echo "background-image: url([[pix:theme|".$picname."]]);<br />";
	echo "width: 35px !important;<br />";
	echo "height: 35px !important;<br />";
	echo "}";
	echo "<p />";
}
echo "/* Problems with following styles<br />";
for ($i=0;$i<count($not_existing_styles);$i++) {
	if($i % 2) {
		echo "$not_existing_styles[$i]<br />";
	} else {
	echo "$not_existing_styles[$i]  ";
	}
}
echo "*/";

?>