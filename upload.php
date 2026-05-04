<!DOCTYPE html><html><head><title>Profile Upload</title><link rel="stylesheet" href="style.css"></head><body>
<header><h1>CorpNet Intranet</h1></header>
<div class="container"><div class="module-box"><h2>Profile Picture Upload</h2>
<p>Select image to upload (.jpg, .png):</p>
<form action="upload.php" method="post" enctype="multipart/form-data">
    <input type="file" name="fileToUpload" id="fileToUpload">
    <input type="submit" value="Upload Document" name="submit">
</form>
<?php
if(isset($_POST["submit"])) {
    $target_dir = "uploads/";
    if (!file_exists($target_dir)) { mkdir($target_dir, 0777, true); }
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
    
    // VULNERABILITY: No extension or content validation. Moves any file directly.
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        echo "<p style='color:green; font-weight:bold;'>File uploaded successfully to: " . $target_file . "</p>";
    } else {
        echo "<p style='color:red;'>Sorry, there was an error uploading your file.</p>";
    }
}
?>
<a href="index.php" class="back-link">← Back to Dashboard</a>
</div></div></body></html>
