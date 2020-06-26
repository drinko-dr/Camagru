#!/usr/bin/php
<?php
$str = fopen("php://stdin", "r");
while ($str && !feof($str))
{
     echo "Enter a number: ";
     $s = fgets($str);
     if ($s) {
		 $s = str_replace("\n", "", $s);
		 if (is_numeric($s)) {
			 if ($s % 2 == 0) {
				 echo "The number " . $s . " is even\n";
			 } else
				 echo "The number " . $s . " is odd\n";
		 }else
			 echo "'" . $s . "' is not a number\n";
	 }
}
 fclose($str);
echo "\n";
?>