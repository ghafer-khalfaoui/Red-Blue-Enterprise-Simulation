<!DOCTYPE html><html><head><title>Payroll Lookup</title><link rel="stylesheet" href="style.css"></head><body>
<header><h1>CorpNet Intranet</h1></header>
<div class="container"><div class="module-box"><h2>Payroll System</h2>
<p>Enter Employee ID (Hint: Admin is ID 1)</p>
<form action="sqli.php" method="GET">
    <input type="text" name="id" placeholder="e.g. 1">
    <input type="submit" value="Retrieve Record">
</form>
<?php
if(isset($_GET['id'])) {
    $db = new SQLite3('/tmp/payroll.db');
    $id = $_GET['id'];
    
    // VULNERABILITY: User input concatenated directly into the SQL query string.
    $query = "SELECT username, salary FROM users WHERE id = " . $id;
    $result = @$db->query($query);
    
    if ($result) {
        echo "<h3>Results:</h3><ul style='background:#f9f9f9; padding:20px; list-style-type:none;'>";
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            echo "<li><strong>User:</strong> " . $row['username'] . " &nbsp;|&nbsp; <strong>Salary:</strong> <span style='color:green; font-weight:bold;'>" . $row['salary'] . "</span></li>";
        }
        echo "</ul>";
    } else {
        echo "<p style='color:red;'>Record not found or invalid input.</p>";
    }
}
?>
<a href="index.php" class="back-link">← Back to Dashboard</a>
</div></div></body></html>
