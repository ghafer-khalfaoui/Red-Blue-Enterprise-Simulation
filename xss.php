<!DOCTYPE html><html><head><title>Directory Search</title><link rel="stylesheet" href="style.css"></head><body>
    <header><h1>CorpNet Intranet (SECURE)</h1></header>
    <div class="container"><div class="module-box"><h2>Employee Directory</h2>
            <form action="xss.php" method="GET">
                <input type="text" name="query" placeholder="Enter name...">
                <input type="submit" value="Search Database">
            </form>
            <?php
            if(isset($_GET['query'])) {
                // SECURITY FIX: Encode all special characters to prevent HTML/JS execution
                $safe_query = htmlspecialchars($_GET['query'], ENT_QUOTES, 'UTF-8');
                echo "<h3>Search results for: " . $safe_query . "</h3><p><em>No active employees found.</em></p>";
            }
            ?>
            <a href="index.php" class="back-link">← Back to Dashboard</a>
        </div></div></body></html>
