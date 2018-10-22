<?php
if(isset($_POST['update'])) {
	echo 'hi';
}

$stringfromfile = file('.git/HEAD', FILE_USE_INCLUDE_PATH);

$firstLine = $stringfromfile[0]; //get the string from the array

$explodedstring = explode("/", $firstLine, 3); //seperate out by the "/" in the string

$branchname = $explodedstring[2]; //get the one that is always the branch name



?>
<!DOCTYPE HTML>
<html>  
<body>
<?php
echo "<div style='clear: both; width: 100%; font-size: 14px; font-family: Helvetica; color: #30121d; background: #bcbf77; padding: 20px; text-align: center;'>Current branch: <span style='color:#fff; font-weight: bold; text-transform: uppercase;'>" . $branchname . "</span></div>"; //show it on the page
?>


<form action="" method="post">
<input type="submit" value="Update Version" name="update">
</form>

</body>
</html>