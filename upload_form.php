<html>
<body>

<form action="upload_image.php" method="post" enctype="multipart/form-data">
<label for="file">Select Image:</label>
<input type="file" name="file" id="file">
<input type="submit" name="submit1" value="Upload Photo">
</form>


<form action="upload_video.php" method="post" enctype="multipart/form-data">
<label for="file">Select Video:</label>
<input type="file" name="vid" id="vid">
<input type="submit" name="submit2" value="Upload Video">
</form>

<form action="upload_voice.php" method="post" enctype="multipart/form-data">
<label for="file">Select Video:</label>
<input type="file" name="voice" id="vid">
<input type="submit" name="submit3" value="Record Voice">
</form>

</body>
</html>
