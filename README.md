# 🛡️ Full-Lifecycle Web Application Attack & Defense Lab

Welcome to the Red/Blue Enterprise Simulation Lab! This project is a fully self-contained cybersecurity simulation designed to run on a single Kali Linux virtual machine. 

By running this lab, you will walk through the entire lifecycle of a cyberattack across four distinct phases: Architecture, Offensive (Red Team), Defensive (Blue Team), and Mitigation.

## ⚙️ Prerequisites
To run this lab locally, you will need:
* A Kali Linux VM (or any Debian-based Linux distro)
* Apache2 and PHP installed (`sudo apt install apache2 php`)
* Docker installed (`sudo apt install docker.io`)
* SQLite3 installed (`sudo apt install sqlite3`)

---

## 🚀 How to Run the Lab

### Phase 1: Deploy the Infrastructure (Architecture)
First, we need to spin up the vulnerable application and our containerized SIEM (Splunk).

1. **Clone this repository to your web root:**
   ```bash
   sudo git clone [https://github.com/ghafer-khalfaoui/Red-Blue-Enterprise-Simulation.git](https://github.com/ghafer-khalfaoui/Red-Blue-Enterprise-Simulation.git) /var/www/html/corpnet
   cd /var/www/html/corpnet
Revert to the vulnerable baseline:
(Note: The main branch contains the secure, patched code. To play the lab, you must checkout the initial vulnerable commit).

Bash
sudo git checkout $(git rev-list --max-parents=0 HEAD)
Prepare the logs, folders, and databases for the lab:

Bash
# Prepare Splunk log files
sudo touch /var/log/apache2/access.log
sudo touch /root/.bash_history
sudo chmod 755 /var/log/apache2
sudo chmod 644 /var/log/apache2/access.log

# Create the uploads directory and give Apache permission to write to it
sudo mkdir -p /var/www/html/corpnet/uploads
sudo chmod 777 /var/www/html/corpnet/uploads

# Generate the vulnerable SQLite database for the SQLi attack
sqlite3 /tmp/payroll.db "CREATE TABLE users (id INTEGER PRIMARY KEY, username TEXT, salary TEXT); INSERT INTO users (id, username, salary) VALUES (1, 'admin', '\$150,000'), (2, 'omar', '\$85,000');"
sudo chmod 666 /tmp/payroll.db
Deploy the Splunk SIEM via Docker:

Bash
sudo docker run -d --name splunk_siem -p 8000:8000 -p 8089:8089 \
  -e SPLUNK_GENERAL_TERMS='--accept-sgt-current-at-splunk-com' \
  -e SPLUNK_START_ARGS='--accept-license' \
  -e SPLUNK_PASSWORD='CorporateSIEM123!' \
  -v /var/log/apache2:/var/log/apache2:ro \
  splunk/splunk:latest
Wait 2-3 minutes for Splunk to boot, then log in at http://localhost:8000 (admin / CorporateSIEM123!) and add /var/log/apache2/access.log to your Data Inputs.

Phase 2: The Attack (Red Team)
Navigate to http://localhost/corpnet/index.php and attempt to exploit the four intentionally vulnerable modules.

SQL Injection (Payroll): Bypass the authentication to dump employee salaries using a boolean payload (e.g., 1 OR 1=1).

Cross-Site Scripting (Directory): Execute a reflected XSS attack in the search bar.

OS Command Injection (Network Tools): Chain commands in the ping tool to read the /etc/passwd file (e.g., 127.0.0.1; cat /etc/passwd).

File Upload to RCE (Profile): Upload a malicious revshell.php file to gain an interactive reverse shell on the server using Netcat.

Phase 3: Detection & Response (Blue Team)
Put on your incident responder hat. The attacker has compromised the server, and you must track them down.

Open Splunk (http://localhost:8000).

Search your Apache access logs: source="/var/log/apache2/access.log"

Reconstruct the attacker's timeline:

Find the exact SQLi payload they used.

Identify the name of the webshell they uploaded.

Contain the threat: Open your terminal and permanently delete the attacker's webshell from the /uploads/ directory.

Detection Engineering: Write custom Splunk queries to trigger alarms if these specific attack patterns are ever seen again.

Phase 4: Mitigation (Secure Coding)
The final step is to secure the application so the Red Team attacks no longer work.

Return to the main branch to apply the security patches:

Bash
sudo git checkout main
Review the code changes: Look at the PHP files to see how the vulnerabilities were fixed using:

Whitelisted file extensions (upload.php)

IP format validation (cmd.php)

HTML Entity Encoding (xss.php)

SQLite3 Prepared Statements (sqli.php)

Re-Exploitation: Try running your Phase 2 attacks again. They will all fail securely!

Created as a comprehensive cybersecurity capstone project covering the entire attack and defense lifecycle.

