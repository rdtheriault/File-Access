<!DOCTYPE html>
<!--
Files accepted - txt, doc, docx, xlsx, csv, pdf, xls, ppt, pptx, flipchart, pub
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <?php
            $access = 0;
            $root = 0;
            $url = "";
            $userL = get_current_user();
            $userA = $_SERVER['AUTH_USER'];
            $userH = explode("\\",$userA);
            $userA = $userH[1];
            session_start();
            if(isset($_GET['loc'])){$url=$_GET['loc'];$root = 1;$url = str_replace('%amp','&',$url);}//if someone has %amp in the folder it will be replaced with &
            $user = $_SESSION['userName'];
            $other = $_SESSION['IdoHaveAccess'];
            echo "<br>";
            
            if ($userA == $user)
            {
                $access = 1;
            }
            else
            {
                echo "Your credentials do not match your login. Please try <a href='../'>again</a><br>";
            }
            

            if ($other == "secretPass" && $access == 1)
            {
                $dir = "staff/".$user."/Data/".$url;//these files and folders are under the "Data" folder, change as needed
                
                echo "<h3><br>You are accessing ".$dir."<br><br></h3>";
                
                if ($_POST['upload'] == "upload")
                {
                    $target_dir = $dir."/";
                    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
                    $uploadOk = 1;
                    $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
                    
                    //Files accepted - txt, doc, docx, xlsx, csv, pdf, xls, ppt, pptx, flipchart, pub

                    // Allow certain file formats
                    if($imageFileType != "txt" && $imageFileType != "doc" && $imageFileType != "docx" && $imageFileType != "xlsx" && $imageFileType != "csv"
                       && $imageFileType != "pdf" && $imageFileType != "xls" && $imageFileType != "ppt" && $imageFileType != "pptx" && $imageFileType != "flipchart" && $imageFileType != "pub")
                    {
                        echo "Sorry, only txt, doc, docx & xlsx files are allowed.";
                        $uploadOk = 0;
                    }
                    // Check if $uploadOk is set to 0 by an error
                    if ($uploadOk == 0) {
                        echo " Sorry, your file was not uploaded.";
                    // if everything is ok, try to upload file
                    }
                    else
                    {
                        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file))
                        {
                            echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
                        }
                        else
                        {
                            echo "Sorry, there was an error uploading your file.";
                        }
                    }
                }
                if ($root == 1)
                {
                    echo '<form action="" method="post" enctype="multipart/form-data">
                    Select file to upload:
                    <input type="file" name="fileToUpload" id="fileToUpload">
                    <input type="submit" value="Upload File" name="submit">
                    <input type="hidden" value="upload" name ="upload">
                    </form>';
                }
                
                $files = scandir($dir);
                
                foreach ($files as $file)
                {
                    if(strpos($file,'.txt') !== false OR strpos($file,'.doc') !== false OR strpos($file,'.docx') !== false OR strpos($file,'.xlsx') !== false OR strpos($file,'.csv') !== false OR strpos($file,'.pdf')
                       !== false OR strpos($file,'.xls') !== false OR strpos($file,'.ppt') !== false OR strpos($file,'.pptx') !== false OR strpos($file,'.flipchart') !== false OR strpos($file,'.pub') !== false)
                    {
                        echo "<a href='".$dir."/".$file."' target='_blank'>".$file."</a><br>";
                    }
                    else if (strpos($file,'.') === false)//else if (is_dir($file) === true)//else if (strpos($file,'.') === false)
                    {
                        $filed = str_replace('&','%amp',$file);
                        echo "<a href='?loc=".$url."/".$filed."'>FOLDER > ".$file."</a><br>";
                    }
                            
                }
            }
            else 
            {
                echo "Restricted site, please leave";
            }
        ?>
    </body>
</html>
