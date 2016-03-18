<?php
if ($_FILES["vid"]["error"] > 0)
  {
  echo "Error: " . $_FILES["vid"]["error"] . "<br>";
  }
else
  {
  echo "Upload: " . $_FILES["vid"]["name"] . "<br>";
  echo "Type: " . $_FILES["vid"]["type"] . "<br>";
  echo "Size: " . ($_FILES["vid"]["size"] / 1024) . " kB<br>";
  echo "Stored in: " . $_FILES["vid"]["tmp_name"];
  }
?>



<?php
$allowedExts = array("mov", "mp4", "wmv", "3gp", "flv", "divx");
$temp = explode(".", $_FILES["vid"]["name"]);
$extension = end($temp);
if (($_FILES["vid"]["type"] == "video/mov")
|| ($_FILES["vid"]["type"] == "video/MOV")
|| ($_FILES["vid"]["type"] == "video/mp4")
|| ($_FILES["vid"]["type"] == "video/MP4")
|| ($_FILES["vid"]["type"] == "video/wmv")
|| ($_FILES["vid"]["type"] == "video/WMV")
|| ($_FILES["vid"]["type"] == "video/3gp")
|| ($_FILES["vid"]["type"] == "video/3GP")
|| ($_FILES["vid"]["type"] == "video/flv")
|| ($_FILES["vid"]["type"] == "video/FLV")
|| ($_FILES["vid"]["type"] == "video/divx")
|| ($_FILES["vid"]["type"] == "video/DIVX")
&& in_array($extension, $allowedExts))
  {
  if ($_FILES["vid"]["error"] > 0)
    {
    echo "Return Code: " . $_FILES["vid"]["error"] . "<br>";
    }
  else
    {
    echo "Upload: " . $_FILES["vid"]["name"] . "<br>";
    echo "Type: " . $_FILES["vid"]["type"] . "<br>";
    echo "Size: " . ($_FILES["vid"]["size"] / 1024) . " kB<br>";
    echo "Temp vid: " . $_FILES["vid"]["tmp_name"] . "<br/>";

    if (file_exists("upload/" . $_FILES["vid"]["name"]))
      {
      echo $_FILES["vid"]["name"] . " already exists. ";
      }
    else
      {
        $id = 6;
        $idname = "upload/" ."vid" . $id . ".mp4";// . $extension;
      move_uploaded_file($_FILES["vid"]["tmp_name"],"upload/" . $_FILES["vid"]["name"]);
      rename("upload/" . $_FILES["vid"]["name"], "{$idname}");
      //echo "Stored in: " . "upload/" . $_FILES["vid"]["name"];
      //echo "<video src='" . $idname. "'>";
      //echo "<embed src='".$idname."' width='320' height='240'>";

      $myvid = "<video width=\"320\" height=\"240\" controls>";
      $myvid .= "<source src='".$idname."'' type=video/mp4";
      $myvid .= "></video>";
      echo $myvid;
      }
    }
  }
else
  {
  echo "Invalid vid";
  }
?>
