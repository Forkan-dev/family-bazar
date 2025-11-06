Here is the migration file for banners:
database/migrations/2021_04_17_000010_create_banners_table.php

Example controller to follow:
app/Http/Controllers/Admin/CategoryController.php

Example frontend folder to follow:
resources/js/pages/Admin/Category

Routes file to use for backend:
routes/backend.php
Requirements:
1. Create Banner model in app/Models.
2. Create BannerController in app/Http/Controllers/Admin following structure of CategoryController (index, create, store, edit, update, destroy).
3. Add backend routes in routes/backend.php using Route::resource('banners', BannerController::class);
4. Create Vue/React pages in resources/js/pages/Admin/Banner:
     - Index page (list table with edit & delete button)
     - Create page (form with fields)
     - Edit page (form with existing data)
5. Use Axios for API calls and integrate success/error toast messages.
6. Validate fields in store() and update().
7. Return proper JSON responses like Category module does.
8. Maintain same UI layout table style, buttons, form input style, alert messages, etc.
Now generate complete code step-by-step:
- Model
- Controller (all methods complete)
- Routes
- Vue/React pages (Index.vue or Index.jsx, Create.vue or Create.jsx, Edit.vue or Edit.jsx)
- Example Axios API integration

Follow the same coding style and flow as the Category module.
