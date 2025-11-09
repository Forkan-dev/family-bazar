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
            - `ProductController`: Handles product management.
                - **Brand Integration:** The `create` and `edit` methods now fetch and pass a list of `Brand` models to the product form.
            - `PermissionsController`: Handles permission management.
            - `RolesController`: Handles role management.
                - **Role Assignment:** The `assignRoleForm` method fetches all users and roles and passes them to the `AssignRole` view. The `assignRole` method handles the form submission, syncing the selected roles to the selected user. The `show` method has been added to prevent errors.
        - `Auth`: Authentication controllers.
        - `Settings`: User settings controllers.
    - `app/Http/Middleware`:
        - `HandleInertiaRequests`: Shares user permissions and an `is_super_admin` flag with Inertia.js.
    - `app/Models`: Eloquent models for database interaction.
        - `Product`: Models related to products, categories, and tags.
            - **Integration with Brand:** The `Product` model now includes a `brand_id` column and a `belongsTo` relationship with the `Brand` model, allowing products to be associated with a specific brand.
        - `Category`: The `Category` model now has a `displayName` attribute that concatenates `title_en` and `title_bn`.
        - `Unit`: The `Unit` model now has a `displayName` attribute that concatenates `name` and `abbreviation`.
    - `bootstrap/app.php`:
        - **Middleware Registration:** Registers the Spatie permission middleware aliases.
    - `routes`: Defines the application's routes.
        - `web.php`: Contains the main landing page route.
        - `backend.php`: Contains all backend-related routes, such as the dashboard and product management. These routes are protected by the `auth` and `verified` middleware.
            - **Permissions & Roles:** Added resource routes for `/permissions` and `/roles` for CRUD operations. All resource routes are protected by Spatie's permission middleware.
            - **Role Assignment:** Added routes for the role assignment page.
        - `auth.php`: Authentication routes.
        - `settings.php`: User settings routes.
    - `database/migrations`: Database schema migrations.
    - `database/seeders`: Database seeders.
        - `RolesAndPermissionsSeeder`: New seeder to create default roles (super_admin, admin, shop_owner) and granular permissions (e.g., product.create, order.view).

### Frontend

- **Framework:** Vue.js with Inertia.js
- **Language:** TypeScript
- **Key Directories:**
    - `resources/js/pages`: Inertia.js pages (Vue components).
        - `Admin`: Admin panel pages.
            - `Products/Form.vue`: Product creation and editing form.
                - **Brand Integration:** Includes a `v-select` component for selecting a `Brand`, and the form data now includes `brand_id`.
            - `Permissions`: Contains `Index.vue` and `Form.vue` for managing permissions.
            - `Roles`: Contains `Index.vue` and `Form.vue` for managing roles.
            - `Roles/AssignRole.vue`: A form for assigning roles to users. It includes a user select and a role multi-select. The form pre-selects the roles that are already assigned to a user.
        - `auth`: Authentication pages.
        - `settings`: User settings pages.
    - `resources/js/components`: Reusable Vue components.
        - `AppSidebar.vue`: The main sidebar component.
            - **Permission Handling:** Conditionally renders sidebar items based on user permissions using a `hasPermission` function.
    - `resources/js/layouts`: Vue components for page layouts.

### Polymorphic Documents Table

A `documents` table is used to store images and other files for various models in a polymorphic relationship.

-   **Table Name:** `documents`
-   **Model:** `App\Models\Document`
-   **Schema:**
    -   `documentable_id` (unsignedBigInteger)
    -   `documentable_type` (string)
    -   `file_path` (string)
    -   `file_name` (string)
    -   `mime_type` (string)
    -   `file_size` (unsignedBigInteger)
    -   `type` (string, default: 'image')
    -   `alt_text` (string, nullable)
    -   `status` (boolean, default: true)
    -   `is_primary` (boolean, default: false)
-   **Related Models:**
    -   `Product`
    -   `Category`
    -   `Brand`

These models have a `morphMany` relationship with the `Document` model, allowing each of them to have multiple documents.

## Development Workflow

*TODO: Describe the development workflow, including how to run the application, tests, and any other relevant commands.*
