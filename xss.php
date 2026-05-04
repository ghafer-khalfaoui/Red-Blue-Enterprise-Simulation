<!DOCTYPE html><html><head><title>Directory Search</title><link rel="stylesheet" href="style.css"></head><body>
<header><h1>CorpNet Intranet</h1></header>
<div class="container"><div class="module-box"><h2>Employee Directory</h2>
<p>Search by First or Last Name.</p>
<form action="xss.php" method="GET">
    <input type="text" name="query" placeholder="Enter name...">
    <input type="submit" value="Search Database">
</form>
<?php
if(isset($_GET['query'])) {
    $query = $_GET['query'];
    // VULNERABILITY: User input echoed directly into the DOM without sanitization.
    echo "<h3>Search results for: " . $query . "</h3><p><em>No active employees found matching that query.</em></p>";
}
?>
<a href="index.php" class="back-link">← Back to Dashboard</a>
</div></div></body></html>
