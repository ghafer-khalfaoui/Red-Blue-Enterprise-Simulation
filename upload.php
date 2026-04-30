<!DOCTYPE html><html><head><title>Profile Upload</title><link rel="stylesheet" href="style.css"></head><body>
    <header><h1>CorpNet Intranet (SECURE)</h1></header>
    <div class="container"><div class="module-box"><h2>Profile Picture Upload</h2>
            <form action="upload.php" method="post" enctype="multipart/form-data">
                <input type="file" name="fileToUpload" id="fileToUpload">
                <input type="submit" value="Upload Document" name="submit">
            </form>
            <?php
            if(isset($_POST["submit"])) {
                $target_dir = "uploads/";
                $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
                $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
                
                // SECURITY FIX: Explicit whitelist of safe extensions
                if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
                    echo "<div class='alert' style='border-left-color: red; background-color: #fee;'>Error: Only JPG, JPEG, & PNG files are allowed.</div>";
                } else {
                    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                        echo "<div class='alert' style='border-left-color: #4CAF50; background-color: #e8f5e9;'>File uploaded successfully.</div>";
                    }
                }
            }
            ?>
            <a href="index.php" class="back-link">← Back to Dashboard</a>
        </div></div></body></html>
