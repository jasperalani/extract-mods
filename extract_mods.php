<?php

/*

Extract Cities Skylines Mods from Steam Workship zip folder

Directory must contain this file, folder 'new' and 'old' and zip files.
'new' folder must contain three folders 'asset', 'mod', 'unknown'

Run this file with:
$ php extract_mods.php

*/

$CWD = getcwd();

$files = array_diff(scandir($CWD), array('.', '..'));

$type = 'unknown'; // Unknown, Mod, Asset

foreach($files as $file){

	if($file == 'extract_mods.php'){
		continue;
	}

	if($file == 'new'){
		continue;
	}

	if($file == 'old'){
		continue;
	}

	$dir = $CWD . "\\" . $file;
	$item_folder = array_diff(scandir($dir), array('.', '..'));	
	$item_folder_contents = array_diff(scandir($CWD . "\\" . $file . "\\" . $item_folder[2]), array('.', '..'));

	foreach($item_folder_contents as $item_folder_contents_files){
		if(str_contains($item_folder_contents_files, '.dll')){
			$type = 'mod';
			break;
		}else{
			$type = 'asset';
		}
	}

	recurseCopy(
		$CWD . "\\" . $file . "\\" . $item_folder[2],
		$CWD . "\\new\\" . $type . "\\" . $item_folder[2]
	);
}

echo 'Finished!';

function recurseCopy(
	string $sourceDirectory,
	string $destinationDirectory,
	string $childFolder = ''
): void {
	$directory = opendir($sourceDirectory);

	if (is_dir($destinationDirectory) === false) {
		mkdir($destinationDirectory);
	}

	if ($childFolder !== '') {
		if (is_dir("$destinationDirectory/$childFolder") === false) {
			mkdir("$destinationDirectory/$childFolder");
		}

		while (($file = readdir($directory)) !== false) {
			if ($file === '.' || $file === '..') {
				continue;
			}

			if (is_dir("$sourceDirectory/$file") === true) {
				recurseCopy("$sourceDirectory/$file", "$destinationDirectory/$childFolder/$file");
			} else {
				copy("$sourceDirectory/$file", "$destinationDirectory/$childFolder/$file");
			}
		}

		closedir($directory);

		return;
	}

	while (($file = readdir($directory)) !== false) {
		if ($file === '.' || $file === '..') {
			continue;
		}

		if (is_dir("$sourceDirectory/$file") === true) {
			recurseCopy("$sourceDirectory/$file", "$destinationDirectory/$file");
		}
		else {
			copy("$sourceDirectory/$file", "$destinationDirectory/$file");
		}
	}

	closedir($directory);
}