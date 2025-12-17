# KXO205 Accommodation Booking Platform

A full-stack accommodation booking system built with PHP, MySQL, and Bootstrap. This platform allows users to search, view, and book properties with role-based access for clients, hosts, and managers.

## Features

### Client Features

- Search accommodations by city, dates, and number of guests
- View detailed property information
- Book accommodations with real-time availability checking
- View and cancel bookings
- User profile management

### Host Features

- Add and manage properties
- View bookings for owned properties
- Edit property details and amenities

### Manager Features

- Manage all users, properties, and bookings
- Add properties for any host
- View system-wide statistics

## Tech Stack

- **Backend**: PHP 8.2
- **Database**: MySQL 8.0
- **Frontend**: Bootstrap 5, Vanilla JavaScript
- **Deployment**: Docker & Docker Compose

## Quick Start with Docker

1. **Clone the repository**

   ```bash
   git clone <repository-url>
   cd KXO205_Assignment
   ```

2. **Start Docker containers**

   ```bash
   docker-compose up -d
   ```

3. **Access the application**

   - **Main site**: http://localhost
   - **phpMyAdmin**: http://localhost:8080

4. **Default login credentials**
   - Manager: `manager@kxo205.com`
   - Host: `host1@kxo205.com`
   - Client: `client1@kxo205.com`
   - Password (all): Check `database.sql` for hashed passwords

## Database Management

### Reset Database

```bash
docker-compose down -v
docker-compose up -d
```

### Access MySQL CLI

```bash
docker exec -it kxo205_db mysql -u root KXO205
```

### View Data via phpMyAdmin

Visit http://localhost:8080

- Server: `db` (auto-filled)
- Username: `root`
- Password: (leave empty)

## Project Structure

```
KXO205_Assignment/
├── includes/          # Database connections
├── css/              # Stylesheets
├── js/               # JavaScript files
├── img/              # Property images
├── *.php             # Page files
├── database.sql      # Database schema and sample data
├── docker-compose.yml
└── Dockerfile
```

## Development

### Local WAMP Setup (Alternative)

If not using Docker, configure WAMP and update `includes/dbconn.php`:

```php
define("DB_HOST", "localhost");
define("DB_NAME", "KXO205");
```

### Naming Convention

- **Form fields**: camelCase (`pricePerNight`, `maxGuests`)
- **Database columns**: camelCase matching form fields
- **Session variables**: camelCase (`$_SESSION["userId"]`)

## Security Features

- Password hashing with `password_hash()`
- SQL injection prevention (prepared statements where applicable)
- XSS protection with `htmlspecialchars()`
- Session-based authentication
- Role-based access control
- Transaction-based booking to prevent double bookings

## Author

Shengyi Shi - 744564
