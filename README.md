# Laravel Project Setup

## Overview: QBO Customer and Invoice Sync

This project aims to integrate a **Laravel** application with **QuickBooks Online (QBO)** for managing **customer** and **invoice** data. The system provides seamless synchronization between QBO and the local database by fetching customer and invoice records from QBO and storing them in the application’s database. Additionally, the project includes the ability to generate **multiple invoices** for each customer and manage them efficiently using background job processing.


## Prerequisites

1. **PHP** (version 8.2 or higher)
2. **Composer** (for managing PHP dependencies)
3. **MySQL** (or any other compatible database)
4. **Node**

## Setup Instructions

### 1. Clone the Repository

Clone the repository to your local machine:

```bash
git clone https://github.com/shailendradigarse/laravelTest.git
cd laravelTest
```

### 2. Install PHP Dependencies

Install the PHP dependencies using Composer:

```bash
composer install
```
### 3. Install NODE Dependencies

Install the node dependencies using Composer:

```bash
npm install
```

### Configure Environment Variables

1. **Create the .env File**:

Copy the example environment file to create a new .env file:

```bash
cp .env.example .env
```

2 **Set Up Database Configuration**:

Open the .env file and configure your database settings:

```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_username
DB_PASSWORD=your_database_password
```

Make sure to replace your_database_name, your_database_username, and your_database_password with your actual database credentials.

3 **Set Up QBO Configuration**:

Open the .env file and configure your QBO settings:

```bash
QBO_CLIENT_ID=ABLNXp0DzNZhrtExRwf0ibOq62UKFBYBrbfzdXGJxUM7LqjJGx
QBO_CLIENT_SECRET=0e9FXC7t6ok9pUNFwNXNKB8O8m3gzM4QoPCzXctd
QBO_REDIRECT_URI=https://9c8c-2409-40c4-11c9-ad9a-5cae-8253-546d-fae6.ngrok-free.app/qbo-callback
QBO_SANDBOX=true
QBO_COMPANY_ID=2
QBO_REFRESH_TOKEN=AB1173434767970pz01dPMqCO45lCXvsMK8FJryRgsc820vRFJ

```

**Generate Application Key**:
Generate a new application key for the Laravel project:

```bash
php artisan key:generate
```

**Run Migrations**:
Run the database migrations to set up the necessary tables:

```bash
php artisan migrate
```

**Run Seeder**:
Run the seeder to set up the necessary data in token:

```bash
php artisan db:seed --class=TokenSeeder

```

**Start PHP Server**:
Open a new terminal and start the PHP development server:

```bash
php artisan serve
```
**Start node dev**:
Open a new terminal and start the node development server:

```bash
npm run dev
```

The application will be accessible at http://127.0.0.1:8000 (or another port specified in the output).
