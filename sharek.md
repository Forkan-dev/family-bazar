Migration file: database/migrations/2021_04_17_000010_create_banners_table.php – defines the banners table.
Vue file: resources/js/pages/Admin/Banner/Edit.vue – used to edit a banner.
The problem: When I open the edit page, the existing banner data is not showing in the form.
Requirements:
Fix Edit.vue so that all existing banner data is correctly loaded and displayed in the form fields (including text, select, and file inputs).
Ensure the API request fetches the correct banner by ID.
Make sure v-model bindings are correctly mapped to the fetched data.
Keep the current styling and form structure from Create.vue.
Ensure file input shows existing image if any.
Provide the full corrected Edit.vue code with proper data fetching, binding, and submit functionality
