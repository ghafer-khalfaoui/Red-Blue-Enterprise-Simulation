<!DOCTYPE html><html><head><title>Network Tools</title><link rel="stylesheet" href="style.css"></head><body>
    <header><h1>CorpNet Intranet (SECURE)</h1></header>
    <div class="container"><div class="module-box"><h2>IT Diagnostic: Server Ping</h2>
            <form action="cmd.php" method="GET">
                <input type="text" name="ip" value="127.0.0.1" placeholder="e.g. 192.168.1.1">
                <input type="submit" value="Execute Ping">
            </form>
            <?php
            if(isset($_GET['ip'])) {
                $ip = $_GET['ip'];
                // SECURITY FIX: Strict validation that input is a valid IP address
                if (filter_var($ip, FILTER_VALIDATE_IP)) {
                    echo "<h3>Results:</h3><pre>" . shell_exec("ping -c 3 " . escapeshellarg($ip)) . "</pre>";
                } else {
                    echo "<div class='alert' style='border-left-color: red; background-color: #fee;'>Error: Invalid IP Address format detected.</div>";
                }
            }
            ?>
            <a href="index.php" class="back-link">← Back to Dashboard</a>
        </div></div></body></html>
