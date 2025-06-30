
# CreditSystem

## 🚀 Setup Steps (Docker Compose)

1. **Clone the repository**
   ```sh
   git clone <your-repo-url>
   cd <project-folder>
   ```
2. **Copy the example environment file and adjust as needed**
   ```sh
   cp .env.example .env
   # Edit .env for DB, Meilisearch, etc. (see .env for details)
   ```
3. **Start all services (app, MySQL, Meilisearch, Redis, phpMyAdmin)**
   ```sh
   docker compose up --build
   ```
4. **Install Composer dependencies (in the app container)**
   ```sh
   docker compose exec app composer install
   ```
5. **Run migrations and seeders**
   ```sh
   docker compose exec app php artisan migrate --seed
   ```
6. **Access the app**
   - Web: [http://localhost:8000](http://localhost:8000)
   - phpMyAdmin: [http://localhost:8080](http://localhost:8080) (user: root, pass: root)

---

## 📚 API Documentation

All API endpoints are prefixed with `/api` and use Laravel Sanctum for authentication.

### **Authentication**
- `POST /api/login` — Login, returns token
- `POST /api/logout` — Logout (token required)

### **Products**
- `GET /api/products` — List products (search, filter, paginate)
- `GET /api/products/{id}` — Get product details
- `POST /api/products` — Create product (admin only)
- `PUT /api/products/{id}` — Update product (admin only)
- `DELETE /api/products/{id}` — Delete product (admin only)

### **Cart**
- `GET /api/cart` — Get current cart
- `POST /api/cart/add` — Add item to cart
- `PATCH /api/cart/update/{id}` — Update item quantity
- `DELETE /api/cart/remove/{id}` — Remove item

### **Orders**
- `GET /api/orders` — List user orders
- `POST /api/orders` — Checkout/create order
- `GET /api/orders/{id}` — Order details

### **Packages**
- `GET /api/packages` — List credit packages
- `POST /api/packages/buy/{id}` — Buy package

### **AI & Search**
- `POST /api/ai/recommendation` — Get product recommendations
- `GET /api/products/search` — Advanced search

> For full details, see the Postman collection or use `/api/docs` if enabled.

---

## 🌱 Sample Test Data

### **Seeders**
- Run `php artisan db:seed` (or `php artisan migrate --seed`) to populate:
  - Admin and user accounts
  - Realistic products (with images, categories)
  - Credit packages
  - Example orders, carts, and more

### **Manual SQL Import**
- If you want a SQL dump, export from your running MySQL container or use phpMyAdmin.
- Example:
  ```sh
  docker compose exec db mysqldump -u root -proot ElSalam > sample_dump.sql
  ```

---

## 🤖 (Bonus) AI Prompt Explanation

The built-in AI assistant (RAG) for users and admins uses retrieval-augmented generation:
- **User AI**: Answers questions about your balance, points, and what you can buy. It can recommend products and guide you through the app.
- **Admin AI**: Lets admins search, create, update, and delete users/products via natural language. It can answer queries like "get users", "create product name=Hoodie price=200", or "how many users do we have". It reads live data from the database and responds accordingly.
- **How it works**: The AI parses your prompt, maps it to database queries or actions, and returns a natural-language response. For recommendations, it uses product and user data to suggest relevant items.

---

## 📝 Notes
- For troubleshooting Docker, see `.env` and `docker-compose.yml` for service names and ports.
- For local development without Docker, set `DB_HOST=127.0.0.1` in `.env` and run MySQL/Meilisearch locally.
- For more, see the in-app onboarding tour and floating user guide.

---

**Happy coding!**
