<!DOCTYPE html><html><head><title>Payroll Lookup</title><link rel="stylesheet" href="style.css"></head><body>
    <header><h1>CorpNet Intranet (SECURE)</h1></header>
    <div class="container"><div class="module-box"><h2>Payroll System</h2>
            <form action="sqli.php" method="GET">
                <input type="text" name="id" placeholder="e.g. 1">
                <input type="submit" value="Retrieve Record">
            </form>
            <?php
            $db = new SQLite3('/tmp/payroll.db');
            if(isset($_GET['id'])) {
                // SECURITY FIX: Prepared statements prevent query modification
                $stmt = $db->prepare('SELECT username, salary FROM users WHERE id = :id');
                $stmt->bindValue(':id', $_GET['id'], SQLITE3_INTEGER);
                $result = $stmt->execute();
                
                if ($row = $result->fetchArray(SQLITE3_ASSOC)) {
                    echo "<ul style='background: #f9f9f9; padding: 20px; border-radius: 4px; list-style-type: none;'><li style='margin-bottom: 10px;'><strong>User:</strong> " . $row['username'] . " &nbsp;|&nbsp; <strong>Salary:</strong> <span style='color:green; font-weight:bold;'>" . $row['salary'] . "</span></li></ul>";
                } else {
                    echo "<p style='color:red;'>Record not found or invalid input.</p>";
                }
            }
            ?>
            <a href="index.php" class="back-link">← Back to Dashboard</a>
        </div></div></body></html>
