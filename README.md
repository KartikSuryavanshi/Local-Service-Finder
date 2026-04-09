# Local Service Finder

## Author
Kartik Suryavanshi  

---

## Project Overview
**Local Service Finder** is a **full-stack web application** designed to connect users with nearby service providers such as plumbers, electricians, cleaners, and other local professionals.

The platform allows users to **search services by category and location**, **book services**, **manage bookings**, **give feedback**, and **communicate with service providers**, while providers can manage requests, track performance, and handle customer interactions through a dedicated dashboard.

---

## Key Features

### User Features
- User registration & login (OTP verification)
- Browse services by category
- Search services by location
- Book services
- View & manage bookings
- Cancel bookings
- Favorite service providers
- Send messages to providers
- Submit & view feedback

### Service Provider Features
- Provider registration & login
- Provider dashboard
- Accept / reject service bookings
- Manage bookings
- Performance analytics
- View customer feedback
- Messaging system with users

### Admin / System Features
- Category management
- Service management
- Booking approval workflow
- Location settings
- Secure authentication
- Database-backed operations

---

## Tech Stack

### 🔹 Frontend
- HTML
- CSS
- JavaScript

### 🔹 Backend
- PHP

### 🔹 Database
- SQLite (`sql.db`)

---



A PHP/MySQL web application for finding and booking local services.

---

## Requirements

- [XAMPP for macOS](https://www.apachefriends.org/download.html)

---

## How to Run on macOS

### Step 1 — Copy the project to XAMPP

Place the project folder (`admin1`) inside:
```
/Applications/XAMPP/htdocs/admin1
```

### Step 2 — Open XAMPP Manager

- Open **Finder** → **Applications** → **XAMPP**
- Double-click **manager-osx.app**

### Step 3 — Start the servers

- Click the **"Manage Servers"** tab
- Click **Apache Web Server** → click **Start**
- Click **MySQL Database** → click **Start**
- Both should show a **green dot** when running

### Step 4 — Set up the database

- Open your browser and go to: `http://localhost/phpmyadmin`
- Click **"New"** in the left sidebar
- Create a database named exactly: **`local`**
- Click **Create**

### Step 5 — Open the project

- Go to: `http://localhost/admin1/admin/signin.php`

---

## Run Fully via Terminal

Use these commands to start everything without the XAMPP GUI:

```bash
# Start Apache
sudo /Applications/XAMPP/xamppfiles/bin/apachectl start

# Start XAMPP MySQL (MariaDB)
sudo /Applications/XAMPP/xamppfiles/bin/mysql.server start

# Verify app is reachable
curl -s -o /dev/null -w "App HTTP %{http_code}\n" http://localhost/admin1/admin/signin.php
```

Open in browser:

```bash
open http://localhost/admin1/admin/signin.php
open http://localhost/phpmyadmin
```

---

## Troubleshooting

- If MySQL fails to start, restart your Mac and try again — this clears port conflicts.
- If Apache is already running, that is not an error — it means it started successfully before.
- Always start XAMPP from `/` directory, not from a Desktop path, to avoid `getcwd` permission errors.

### MySQL Keeps Stopping (Port 3306 Conflict)

If XAMPP MySQL starts and stops repeatedly, another MySQL service is usually occupying port `3306`.

Check what is using port `3306`:

```bash
lsof -nP -iTCP:3306 -sTCP:LISTEN
```

If you see `/usr/local/mysql/bin/mysqld` (Oracle MySQL), disable it and start XAMPP MySQL:

```bash
# Disable Oracle MySQL auto-start daemon
sudo launchctl bootout system /Library/LaunchDaemons/com.oracle.oss.mysql.mysqld.plist 2>/dev/null || sudo launchctl unload -w /Library/LaunchDaemons/com.oracle.oss.mysql.mysqld.plist
sudo launchctl disable system/com.oracle.oss.mysql.mysqld

# Stop existing Oracle MySQL process (if running)
sudo pkill -f '/usr/local/mysql/bin/mysqld' || true

# Start XAMPP MySQL
sudo /Applications/XAMPP/xamppfiles/bin/mysql.server start

# Verify XAMPP MySQL
/Applications/XAMPP/xamppfiles/bin/mysql -u root -e "SELECT VERSION() AS xampp_mysql_version;"
```
