# Shores Hotel 🏨🍸🏠

![Laravel](https://img.shields.io/badge/Laravel-12-red?logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.1+-blue?logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8.0+-4479A1?logo=mysql&logoColor=white)
![Docker](https://img.shields.io/badge/Docker-Sail-2496ED?logo=docker&logoColor=white)
![License](https://img.shields.io/badge/license-proprietary-orange)

---

## 🌐 About the Project

**Shores Hotel** is a modern hotel management website built with **Laravel**.  
It showcases the services of **Shores Hotel**, which include:

- 🏨 **Hotel** – Room lodging and booking
- 🏠 **Apartments** – Renting fully furnished apartments
- 🍸 **CitiB Lounge** – The in-house bar for guests and visitors

The website is designed to be user-friendly, dynamic, and easy to manage via the **admin dashboard**.

---

## ✨ Features

### **Public Website**
- Room & apartment listings with advanced filters
- CitiB Lounge information and events
- Contact page with dynamic forms (address, phone, email, socials)
- Responsive design optimized for mobile and desktop

### **Admin Dashboard**
- **Content Management**
    - Manage rooms, apartments, and categories
    - Upload galleries & featured images
    - Manage facilities and amenities
    - Content management via Markdown/YAML

- **📧 Email Management System**
    - **Full IMAP Inbox** – View, read, and manage emails without logging into cPanel
    - **Compose & Send** – Send emails directly to guests with attachments
    - **Guest Communication** – Email guests directly from booking list
    - **Email Templates** – Pre-built templates for confirmations, reminders, and custom messages
    - **Search & Filter** – Search emails by sender, subject, or content
    - **Attachments** – Download and view email attachments
    - **Reply & Forward** – Respond to guest inquiries instantly
    - **Smart Labels** – Organize emails with stars, flags, and folders
    - **WhatsApp Integration** – Send WhatsApp messages to guests (requires Twilio setup)

- **Booking Management**
    - Send booking confirmations automatically
    - Send reminder emails before check-in
    - Track all guest communications in one place

### **Technical Features**
- AJAX-powered filtering for room types
- Fully Dockerized environment with Laravel Sail
- IMAP/SMTP integration for real-time email management
- Secure authentication and authorization
- Responsive Material Design UI

---

## ⚙️ Tech Stack

- **Backend**: [Laravel 12](https://laravel.com/) (PHP 8.1+)
- **Database**: MySQL 8
- **Email**: IMAP/SMTP with [webklex/php-imap](https://github.com/Webklex/php-imap)
- **Frontend**: Blade templates, Materialize CSS
- **Environment**: [Laravel Sail](https://laravel.com/docs/sail) (Docker)
- **Content**: Markdown + YAML for flexible content management
- **Mail Server**: DirectAdmin with SSL/TLS encryption

---

## 🚀 Getting Started

### Prerequisites
- [Docker](https://www.docker.com/) & Docker Compose
- [Composer](https://getcomposer.org/)
- [Node.js](https://nodejs.org/) & npm/yarn (for frontend assets)
- Email server credentials (IMAP/SMTP)

### Installation

1. **Clone the repository:**
```bash
   git clone https://github.com/PeterAdetola/shores-hotel.git
   cd shores-hotel
```

2. **Copy .env example and update settings:**
```bash
   cp .env.example .env
```

3. **Configure Email Settings in `.env`:**
```env
   # IMAP Configuration (for receiving emails)
   IMAP_HOST=mail.jupitercorporateservices.com
   IMAP_PORT=993
   IMAP_ENCRYPTION=ssl
   IMAP_VALIDATE_CERT=true
   IMAP_USERNAME=hello@shoreshotelng.com
   IMAP_PASSWORD=your_email_password
   IMAP_DEFAULT_ACCOUNT=default

   # SMTP Configuration (for sending emails)
   MAIL_MAILER=smtp
   MAIL_HOST=mail.jupitercorporateservices.com
   MAIL_PORT=587
   MAIL_USERNAME=hello@shoreshotelng.com
   MAIL_PASSWORD=your_email_password
   MAIL_ENCRYPTION=tls
   MAIL_FROM_ADDRESS="hello@shoreshotelng.com"
   MAIL_FROM_NAME="${APP_NAME}"
```

4. **Start Sail:**
```bash
   ./vendor/bin/sail up -d
```

5. **Install dependencies:**
```bash
   ./vendor/bin/sail composer install
   ./vendor/bin/sail npm install && ./vendor/bin/sail npm run dev
```

6. **Publish IMAP configuration:**
```bash
   ./vendor/bin/sail artisan vendor:publish --provider="Webklex\PHPIMAP\Providers\LaravelServiceProvider"
```

7. **Run migrations & seeders:**
```bash
   ./vendor/bin/sail artisan migrate --seed
```

8. **Clear caches:**
```bash
   ./vendor/bin/sail artisan config:clear
   ./vendor/bin/sail artisan cache:clear
```

9. **Visit the app:**
    - **Public site:** http://localhost
    - **Admin dashboard:** http://localhost/admin
    - **Email inbox:** http://localhost/admin/email/inbox

---

## 📂 Project Structure
```
app/
├── Http/Controllers/
│   ├── EmailController.php      → Email management
│   ├── BookingController.php    → Booking & guest communication
│   └── ...
├── Helpers/
│   └── BookingEmailHelper.php   → Email utilities
resources/
├── views/
│   ├── public/                  → Public-facing pages
│   ├── admin/
│   │   ├── email/               → Email inbox & compose
│   │   └── ...
│   └── emails/                  → Email templates
storage/
├── app/content/                 → Markdown + YAML content
config/
├── imap.php                     → IMAP configuration
docker/                          → Docker Sail configs
```

---

## 📧 Email System Usage

### **For Administrators:**

1. **Access Inbox:**
    - Navigate to `Admin Dashboard → Mailbox`
    - View all emails from `hello@shoreshotelng.com`

2. **Send Email to Guest:**
    - Go to `Bookings → View Booking`
    - Click email icon next to guest
    - Choose template or write custom message
    - Add attachments if needed
    - Click Send

3. **Reply to Guest Inquiry:**
    - Open email from inbox
    - Click "Reply" button
    - Compose response
    - Send

4. **Email Templates Available:**
    - Booking Confirmation
    - Booking Reminder
    - Check-in Instructions
    - Thank You Message
    - Custom Messages

### **Automated Emails:**
The system can automatically send:
- Booking confirmations upon reservation
- Reminder emails 24 hours before check-in
- Thank you emails after checkout

---

## 🔧 Configuration

### **Email Server Setup**

Ensure your email server (DirectAdmin) allows:
- IMAP connections on port 993 (SSL)
- SMTP connections on port 587 (TLS)
- Authentication with email credentials

### **WhatsApp Integration (Optional)**

To enable WhatsApp messaging:

1. Install Twilio SDK:
```bash
   ./vendor/bin/sail composer require twilio/sdk
```

2. Add to `.env`:
```env
   TWILIO_SID=your_twilio_sid
   TWILIO_TOKEN=your_twilio_token
   TWILIO_WHATSAPP_NUMBER=whatsapp:+14155238886
```

3. Uncomment WhatsApp code in `BookingEmailHelper.php`

---

## 🧪 Testing Email System

### Test IMAP Connection:
```bash
./vendor/bin/sail artisan tinker
```
```php
$cm = new \Webklex\PHPIMAP\ClientManager();
$client = $cm->account('default');
$client->connect();
echo "Connected successfully!";
```

### Test Sending Email:
```bash
./vendor/bin/sail artisan tinker
```
```php
Mail::raw('Test email', function($message) {
    $message->to('your-email@example.com')
            ->subject('Test from Shores Hotel');
});
```

---

## 🤝 Contributing

Contributions are welcome!

1. Fork the repo
2. Create a feature branch (`git checkout -b feature/xyz`)
3. Commit your changes (`git commit -m 'Add new feature'`)
4. Push to branch (`git push origin feature/xyz`)
5. Create a Pull Request

---

## 📄 License

This project is proprietary to Shores Hotel.  
For inquiries about use or contributions, please contact the development team.

---

## 📬 Contact

📍 **Hotel Address:** KM 18, Lekki Epe Expressway, Eleganza Bus Stop, Lagos, Nigeria

📞 **Phone:** +234 906 834 3870

📧 **Email:** hello@shoreshotelng.com

🍸 **Bar:** CitiB Lounge

---

## 🌍 Connect With Us

- [Instagram](https://instagram.com/shoreshotelng)
- [X (Twitter)](https://twitter.com/shoreshotelng)
- [Facebook](https://facebook.com/shoreshotelng)

---

## 🙏 Acknowledgments

- [Laravel](https://laravel.com) - The PHP Framework
- [webklex/php-imap](https://github.com/Webklex/php-imap) - IMAP Library
- [Materialize CSS](https://materializecss.com) - UI Framework
- [Laravel Sail](https://laravel.com/docs/sail) - Development Environment

---

**Made with ❤️ for Shores Hotel**
