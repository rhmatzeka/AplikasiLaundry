# LaundryGo

A simple laundry pickup-and-delivery web app. Customers order a pickup by pointing to their location on a map, drivers take the order, and both sides can chat about it until the laundry is delivered.

This was a team project for a web programming course.

## Features

- **Sign up and log in** as a customer
- **Order a pickup** by picking your address on an interactive map (Leaflet + OpenStreetMap geocoding)
- **Driver dashboard** to take open orders and update their status
- **Chat per order** between the customer and the driver or admin
- **Order history**, plus a **rating and review** once an order is finished

An order moves through these steps: `menunggu` (waiting) → `dijemput` (picked up) → `proses` (washing) → `diantar` (on the way) → `selesai` (done), or `dibatalkan` (cancelled).

There are three roles: `customer`, `driver` and `admin`. New sign-ups are customers; set driver and admin accounts in the `users` table.

## Tech stack

PHP (no framework), MySQL/MariaDB with PDO, HTML, CSS, JavaScript, Leaflet

## Getting started

You need PHP 7.4+ and MySQL or MariaDB (XAMPP or Laragon works well).

1. Copy the project into your web server folder, for example `htdocs/laundrygo`.
2. Create a database called `laundrygo` and import `config/Database/laundrygo.sql`.
3. Open `config/db.php` and set your database username and password.
4. Visit http://localhost/laundrygo in your browser.

## Project structure

| Path | What it is |
| --- | --- |
| `index.php`, `login.php`, `register.php` | Landing, login and sign-up pages |
| `pesan.php` | Place a new order (with the map) |
| `dashboard.php` | Dashboard for customers and drivers |
| `ambil_order.php`, `update_status.php` | Driver takes an order and updates its status |
| `riwayat.php` | Order history |
| `api_chat.php` | Chat endpoint used by the order chat |
| `config/` | Database connection and SQL dump |
