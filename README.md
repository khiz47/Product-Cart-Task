# 🛒 Product Cart Management System (Laravel)

A Laravel-based backend application with **Admin CMS**, **Product Management**, **Cart APIs**, and **Checkout API (Stripe Test Mode)** built as part of a technical evaluation task.

---

## 🚀 Features

### ✅ Admin CMS (AdminLTE)

- Admin login
- Dashboard with statistics:
    - Total Products
    - Total Orders
    - Total Revenue
- Product CRUD
    - Add product with **multiple images**
    - Edit / Delete product
- Cart listing in admin panel
- Order management
    - Order listing page
    - Order detail (view order) page
- Clean, responsive AdminLTE UI

---

### ✅ APIs

- Product listing API
- Cart APIs:
    - Add to cart
    - Update cart item
    - Delete cart item
    - Cart listing with totals
- Checkout API with **Stripe Payment Intent (Test Mode)**

---

### ✅ Technical Highlights

- Laravel MVC architecture
- Relational MySQL database
- Eloquent relationships
- API versioning (`/api/v1`)
- Exception handling
- Postman-testable APIs

---

## 🛠 Tech Stack

- **PHP**: >= 8.1
- **Framework**: Laravel 10
- **Database**: MySQL 8
- **Admin UI**: AdminLTE (CDN)
- **Payment Gateway**: Stripe (Test Mode)

---

## ⚙️ Installation & Setup

### 1️⃣ Clone Repository

```bash
git clone <your-github-repo-url>

cd product-cart-task
```

### 2️⃣ Install Dependencies

```bash
composer install
```

### 3️⃣ Environment Configuration

Create .env file:

```bash
cp .env.example .env
```

Update database credentials in .env:

```bash
DB_DATABASE=product_cart_db
DB_USERNAME=root
DB_PASSWORD=
```

### 4️⃣ Generate App Key

```bash
php artisan key:generate
```

### 5️⃣ Run Migrations

```bash
php artisan migrate
```

### 6️⃣ Storage Link (For Product Images)

```bash
php artisan storage:link
```

### 7️⃣ Create Admin User (One Time)

```bash
php artisan tinker
```

```bash
use App\Models\User;
use Illuminate\Support\Facades\Hash;

User::create([
    'name' => 'Admin',
    'email' => 'admin@test.com',
    'password' => Hash::make('password123'),
]);

```

### 8️⃣ Start Server

```bash
php artisan serve
```

Application URL Something like this:

```bash
http://127.0.0.1:8000
```

🔐 Admin Access Login URL" /admin/login".

```bash
http://127.0.0.1:8000/admin/login
```

Credentials:

```bash
Email: admin@test.com
Password: password123
```

### 🌐 API Endpoints (v1)

Base URL:

```bash
http://127.0.0.1:8000/api/v1
```

### 📦 Products API

| Method | Endpoint    | Description                  |
| ------ | ----------- | ---------------------------- |
| GET    | `/products` | Get all products with images |

### 🛒 Cart APIs

| Method | Endpoint            | Description               |
| ------ | ------------------- | ------------------------- |
| POST   | `/cart/add`         | Add product to cart       |
| PUT    | `/cart/update`      | Update cart item quantity |
| DELETE | `/cart/remove/{id}` | Remove cart item          |
| GET    | `/cart`             | Cart listing with totals  |

### Add to Cart Payload

```bash
{
  "product_id": 1,
  "quantity": 2
}
```

### 💳 Checkout API (Stripe Test Mode)

| Method | Endpoint    |
| ------ | ----------- |
| POST   | `/checkout` |

### 💳 Stripe Setup (Test Mode):

Add Stripe keys in .env:

```bash
STRIPE_KEY=pk_test_xxxxxxxxxxxxx
STRIPE_SECRET=sk_test_xxxxxxxxxxxxx
```

Update config/services.php:

```bash
'stripe' => [
    'key' => env('STRIPE_KEY'),
    'secret' => env('STRIPE_SECRET'),
],
```

Clear config cache:

```bash
php artisan config:clear
php artisan cache:clear
php artisan serve
```

🧪 Stripe Test Card

```bash
Card Number: 4242 4242 4242 4242
Expiry: Any future date
CVC: Any 3 digits
```

### 📬 Postman Collection

- All APIs are tested via Postman
- A Postman collection (.json) is included in the repository

### 🗄 Database Backup

- A MySQL .sql dump file is included for easy setup

### 🧾 Notes

- User ID is hardcoded to 1 for cart and checkout (as per task).
- Payment is implemented in test mode only.
- No frontend store UI is included (API-focused backend task).

### ✅ Task Requirements Covered

- ✔ Relational DB design
- ✔ Product CRUD with multiple images
- ✔ Admin CMS with clean UI
- ✔ Cart APIs (Add, Update, Delete, List)
- ✔ Cart visible in backend
- ✔ Order listing & order detail pages
- ✔ Checkout API with payment gateway
- ✔ API documentation via Postman
- ✔ Clean, maintainable code

### 👤 Author

Khizer Qureshi |
Senior PHP Developer |
(Technical Assignment Submission)
