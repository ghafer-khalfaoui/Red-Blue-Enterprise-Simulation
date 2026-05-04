<!DOCTYPE html><html><head><title>Network Tools</title><link rel="stylesheet" href="style.css"></head><body>
<header><h1>CorpNet Intranet</h1></header>
<div class="container"><div class="module-box"><h2>IT Diagnostic: Server Ping</h2>
<p>Enter an IP address or hostname to verify connectivity.</p>
<form action="cmd.php" method="GET">
    <input type="text" name="ip" value="127.0.0.1" placeholder="e.g. 192.168.1.1">
    <input type="submit" value="Execute Ping">
</form>
<?php
if(isset($_GET['ip'])) {
    $ip = $_GET['ip'];
    // VULNERABILITY: User input concatenated directly into a shell command.
    echo "<h3>Terminal Output:</h3><pre>" . shell_exec("ping -c 3 " . $ip) . "</pre>";
}
?>
<a href="index.php" class="back-link">← Back to Dashboard</a>
</div></div></body></html>
