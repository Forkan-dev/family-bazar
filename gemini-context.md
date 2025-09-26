# Gemini Context

This file helps Gemini understand the project context.

## Business Logic

Family Bazar is a mobile application designed to solve the inconvenience of grocery shopping in rural Bangladesh. It connects customers with their local "mudi" (grocery) shops, allowing them to order items for home delivery. This saves users the time, cost, and difficulty of traveling 1-5 kilometers to the nearest market, a challenge especially pronounced during bad weather or for women and the elderly.

The Minimum Viable Product (MVP) focuses on this core feature: bringing the local store to the customer's doorstep via a simple mobile app. Initially, Family Bazar will not manage its own inventory. Instead, the local grocery shops will act as the product owners and manage their own stock. Future plans include developing in-house product sourcing and inventory management.

## Code Structure

### Backend

- **Framework:** Laravel
- **Key Directories:**
    - `app/Http/Controllers`: Handles incoming HTTP requests.
        - `Admin`: Controllers for the admin panel.
        - `Auth`: Authentication controllers.
        - `Settings`: User settings controllers.
    - `app/Models`: Eloquent models for database interaction.
        - `Product`: Models related to products, categories, and tags.
    - `routes`: Defines the application's routes.
        - `web.php`: Contains the main landing page route.
        - `backend.php`: Contains all backend-related routes, such as the dashboard and product management. These routes are protected by the `auth` and `verified` middleware.
        - `auth.php`: Authentication routes.
        - `settings.php`: User settings routes.
    - `database/migrations`: Database schema migrations.

### Frontend

- **Framework:** Vue.js with Inertia.js
- **Language:** TypeScript
- **Key Directories:**
    - `resources/js/pages`: Inertia.js pages (Vue components).
        - `Admin`: Admin panel pages.
        - `auth`: Authentication pages.
        - `settings`: User settings pages.
    - `resources/js/components`: Reusable Vue components.
    - `resources/js/layouts`: Vue components for page layouts.

## Development Workflow

*TODO: Describe the development workflow, including how to run the application, tests, and any other relevant commands.*
