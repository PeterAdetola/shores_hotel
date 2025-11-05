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
- 🍸 **Citibar** – The in-house bar for guests and visitors

The website is designed to be user-friendly, dynamic, and easy to manage via the **admin dashboard**.

---

## ✨ Features
- **Public Website**
    - Room & apartment listings with filters
    - CITIBAR information and events
    - Contact page with form and dynamic info (address, phone, email, socials)
- **Admin Dashboard**
    - Manage rooms, apartments, categories
    - Upload galleries & featured images
    - Manage facilities
    - Content management via Markdown/YAML
    - Email system
- AJAX-powered filtering for room types
- Fully Dockerized environment with Laravel Sail

---


## ⚙️ Tech Stack
- **Backend**: [Laravel 12](https://laravel.com/) (PHP 8.1+)
- **Database**: MySQL 8
- **Frontend**: Blade templates, TailwindCSS/Bootstrap utilities
- **Environment**: [Laravel Sail](https://laravel.com/docs/sail) (Docker)
- **Markdown + YAML** for content management

---

## 🚀 Getting Started

### Prerequisites
- [Docker](https://www.docker.com/) & Docker Compose
- [Composer](https://getcomposer.org/)
- [Node.js](https://nodejs.org/) & npm/yarn (for frontend assets)

### Installation
1. Clone the repository:
   ```bash
   git clone https://github.com/PeterAdetola/shores-hotel.git
   cd shores-hotel
2. Copy .env example and update settings:
    ```bash
    cp .env.example .env

3. Start Sail:
    ```bash
    ./vendor/bin/sail up -d


4. Install dependencies:
    ```bash
    ./vendor/bin/sail composer install
    ./vendor/bin/sail npm install && ./vendor/bin/sail npm run dev


5. Run migrations & seeders:
    ```bash
    ./vendor/bin/sail artisan migrate --seed


6. Visit the app:

    http://localhost

## 📂 Project Structure 

    app/Http/Controllers/    → Controllers (public & admin)
    resources/views/         → Blade templates
    resources/views/public/  → Public-facing pages
    resources/views/admin/   → Admin dashboard
    storage/app/content/     → Markdown + YAML content
    docker/                  → Docker Sail configs

## 🤝 Contributing

    Contributions are welcome!
    
    Fork the repo
    
    Create a feature branch (git checkout -b feature/xyz)
    
    Commit your changes (git commit -m 'Add new feature')
    
    Push to branch (git push origin feature/xyz)
    
    Create a Pull Request

## 📄 License

    This project is proprietary to Shores Hotel.
    For inquiries about use or contributions, please contact the development team.

## 📬 Contact

    📍 Hotel Address: KM 18, Lekki Epe Expressway, Eleganza Bus Stop
    
    📞 Phone: +234 906 834 3870
    
    📧 Email: hello@shoreshotelng.com
    
    🍸 Bar: Citibar

## 🌍 Socials:

    Instagram
    
    X (Twitter)
    
    Facebook
