<?php

require_once 'version.php';

$repo = 'soorajlalkg-bridge/test/';
$gitApiRepo = 'https://api.github.com/repos/'.$repo;
$gitRepo = 'https://github.com/'.$repo;

$opts = [
        'http' => [
                'method' => 'GET',
                'header' => [
                        'User-Agent: PHP'
                ]
        ]
];

$context = stream_context_create($opts);
$content = file_get_contents($gitApiRepo.'releases/latest', false, $context);
$latestVersionInfo = json_decode($content);
$latestVersion = $latestVersionInfo->tag_name;


//$latestVersion = '0.1';


function rcopy($src, $dest){

    // If source is not a directory stop processing
    if(!is_dir($src)) return false;

    // If the destination directory does not exist create it
    if(!is_dir($dest)) { 
        if(!mkdir($dest)) {
            // If the destination directory could not be created stop processing
            return false;
        }    
    }

    // Open the source directory to read in files
    $i = new DirectoryIterator($src);
    foreach($i as $f) {
        if($f->isFile()) {
            copy($f->getRealPath(), "$dest/" . $f->getFilename());
        } else if(!$f->isDot() && $f->isDir()) {
            rcopy($f->getRealPath(), "$dest/$f");
        }
    }
}


if(isset($_POST['update'])) {
	//download file and extract then replace it, 

	$download_url = $gitRepo.'archive/'.$latestVersion.'.zip'; 
	$delete = 'yes'; // "yes" to "no".
	/* don't touch nothing after this line */
	$file = 'file.zip';
	$script = basename($_SERVER['PHP_SELF']);
	// download the file 
	file_put_contents($file, fopen($download_url, 'r'));
	// extract file content 
	$path = pathinfo(realpath($file), PATHINFO_DIRNAME); // get the absolute path to $file (leave it as it is)
	$zip = new ZipArchive;
	$res = $zip->open($file);
	if ($res === TRUE) {
	  $zip->extractTo($path);
	  //$zip->extractTo(getcwd());
	  $filename = $zip->getNameIndex(0);
	  $zip->close();

	  echo "<strong>$file</strong> extracted to <strong>$path</strong><br>";
	  if ($delete == "yes") { 
	  	unlink($file); 


	  } else { 
	  	echo "remember to delete <strong>$file</strong> & <strong>$script</strong>!"; 
	  }

	  rcopy($filename, '.');
	} else {
	  echo "Couldn't open $file";
	}


	//@todo - migrations, composer updates etc



	echo '<p>Thank you for updating.</p>';
}

// $stringfromfile = file('.git/HEAD', FILE_USE_INCLUDE_PATH);

// $firstLine = $stringfromfile[0]; //get the string from the array

// $explodedstring = explode("/", $firstLine, 3); //seperate out by the "/" in the string

// $branchname = $explodedstring[2]; //get the one that is always the branch name



?>
<!DOCTYPE HTML>
<html>  
<body>
	<h2>Gridview Updates</h2>
<?php

if ($latestVersion > $appVersion) {
	echo '<p>An updated version of gridview is available.</p>';

	echo '<p>You can update to version '.$latestVersion.' automatically.</p>';

?>
<form action="" method="post">
<input type="submit" value="Update Now" name="update">
</form>

<?php 
} else {
	echo '<p>No updates available.</p>';
}
?>

</body>
</html>