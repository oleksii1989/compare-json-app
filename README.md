# Compare JSON App

A simple Vue.js + Laravel application to compare two product JSON payloads and display their differences interactively.

## Features

- Send two product JSON payloads to the backend
- Automatic 30-second delay between payloads
- Visual diff of all changes (including nested fields)
- Minimal, clear UI using Bulma

---

## Getting Started

### 1. Clone the Repository

```
git clone https://github.com/oleksii1989/compare-json-app.git
cd compare-json-app
```

### 2. Setup Backend (Laravel)

```
cd compare-json-backend
composer install
cp .env.example .env
php artisan key:generate
php artisan serve
```

The backend will run at `http://localhost:8000`.

### 3. Setup Frontend (Vue.js)

```
cd ../compare-json-frontend
npm install
npm run dev
```

The frontend will run at `http://localhost:5173` (or as shown in terminal).

---

## Usage

1. Open the frontend in your browser.
2. Click "Send Payload 1" button.
3. Wait for the timer to send Payload 2 automatically.
4. View the differences displayed in the UI.

---
