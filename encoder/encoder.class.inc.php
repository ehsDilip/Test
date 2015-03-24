<?php
/*
Coded By Arsalan Emamjomehkashan
Version 1.0 Beta
*/
/*
Make sure your php file is plain php not php-html
When you want to enter directories enter it like this "Test" not "/Test" nor "/Test/"
*/
error_reporting(0); //Turn of error reporting
class Encode
{
	//Main Encoding Function
    function encode($file)
    {
        if ($file != "." && $file != ".." && $file != "") {
            $fh = fopen($file, 'r+');
            $contents = fread($fh, filesize($file));
            fclose($fh);
            $contents = str_replace("<?php", "", $contents);
            $contents = str_replace("<?", "", $contents);
            $contents = str_replace("?>", "", $contents);
            $x = base64_encode(gzdeflate($contents, 9));
            $y = "<?php eval(gzinflate(base64_decode('$x'))); ?>";
			
            $file1 = $file . ".encoded";
            $fh1 = fopen($file1, 'a+');
            fwrite($fh1, $y);
            fclose($fh1);
            echo "coding done<br>";
        }
    }
    // Displaying Html form
    function Display()
    {
        if ($_POST["file"] == "" && $_POST["dir"] == "") {
            echo '<form action="test.php" method="post">
Enter file name: <input type="text" name="file" /><br>
Or<br> Enter dir name: <input type="text" name="dir" /><br>
<input type="submit" />
</form>';
        } elseif ($_POST["file"] !== "") {
            $file = $_POST["file"];
            encode($file);
        } elseif ($_POST["dir"] !== "") {
            $dir = $_POST["dir"];
            if ($handle = opendir($dir)) {
                while (false !== ($file = readdir($handle))) {

                    encode($dir . "/" . $file);

                }
                closedir($handle);
            }
        }
    }
}
?>
