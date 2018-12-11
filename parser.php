<?php
$file_lines = file('http://www.who.int/csr/don/archive/year/2018/en/');
foreach ($file_lines as $line) {
    if (strpos($line, '<!-- date -->') !== false) {
    	$lineExploded = explode(">",$line);

    	$lineExploded_link = explode('"', $lineExploded[0]);
    	$LINK = $lineExploded_link[1];

    	$lineExploded_date = explode('<', $lineExploded[1]);
    	$DATE = $lineExploded_date[0];
	}
    if (strpos($line, '<!-- title -->') !== false) {
    	$lineExploded = explode(">",$line);    	

    	$lineExploded_Outbreak = explode('<', $lineExploded[2]);
    	$OUTBREAK = $lineExploded_Outbreak[0];    	
    	echo "$LINK --------- $DATE -------- $OUTBREAK\n";
	}	
}