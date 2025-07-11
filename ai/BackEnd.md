# 🚀 **Laravel 12 CRUD Backend Module Prompt (Professional)**

> 🛠 **Tech stack:**
- Laravel 12 (PHP 8.2+)
- Vue 3 + shadcn-vue + Inertia.js
- vue-i18n (multi-language frontend)
- TailwindCSS
- spatie/laravel-activitylog (audit logs)
- spatie/laravel-permission (roles & permissions)
- Service Layer pattern
- Laravel Cache

---

## 📦 **Feature**
- Model: `{{ ModelName }}`
- Table: `{{ table_name }}`
- Fields:
  {{ list of fields }}

yaml
نسخ
تحرير
> ⚠️ Provide fields as variables: name, type, options.

- Frontend must support multiple languages with vue-i18n.

---

## 🧠 **Before generation, AI must ask:**
- Do you need:
- Soft deletes?
- Slug field?
- UUIDs?
- REST API?
- Media attachments?
- Events/observers?
- Use DTOs?

---

## ✅ **Implementation steps & file locations**

### 📍 Step 1: Install packages
- Install & configure:
- `spatie/laravel-permission` → roles & permissions
- `spatie/laravel-activitylog` → activity logging

---

### 📍 Step 2: Migration
📂 `database/migrations`

- Create migration file for `{{ table_name }}`
- Use the provided fields variables.

---

### 📍 Step 3: Model
📂 `app/Models`

- Add `$fillable` for fields.
- Use `LogsActivity` trait.
- Set `$logAttributes` to track all fields.

---

### 📍 Step 4: Observer
📂 `app/Observers`

- Generate observer:
```bash
php artisan make:observer {{ ModelName }}Observer --model={{ ModelName }}`
```
➕ Use observer to log extra activity or handle special logic when creating, updating, deleting.

📍 Step 5: Service Layer
📂 app/Services

Generate service:

```bash
php artisan make:service {{ ModelName }}Service
```
🧩 AI must explain:
Service holds business logic (validation, external APIs, transformations).
Controller stays thin: validates request, calls service, returns response.
If project is large: AI can suggest adding Repository layer.

📍 Step 6: Seeder
📂 database/seeders

Generate & update RolePermissionSeeder:

Add permissions:

```perl
view_{{ table_name }}, create_{{ table_name }}, update_{{ table_name }}, delete_{{ table_name }}
```
Assign to roles if needed.

🛡 Must be created before Policy to register permissions.

📍 Step 7: Policy
📂 app/Policies

Generate policy:

```bash
php artisan make:policy {{ ModelName }}Policy --model={{ ModelName }}
```
Should include methods:

```sql
viewAll, view, create, update, delete
```
Register in AuthServiceProvider.

✅ Must be created before controller so AI can add:

```php
$this->authorizeResource({{ ModelName }}::class, '{{ variableName }}');
```
📍 Step 8: Controller
📂 app/Http/Controllers

Generate controller:

```bash
php artisan make:controller {{ ModelName }}Controller
```
Controller must:

Stay thin: call service methods

Apply caching for index/list

Return Inertia pages

📍 Step 9: FormRequests
📂 app/Http/Requests

Generate:

```bash
php artisan make:request Store{{ ModelName }}Request
php artisan make:request Update{{ ModelName }}Request
```
Add strict validation rules.

📍 Step 10: Routes
📂 routes/web.php

Add resource routes:

```php
Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('{{ resourceRoute }}', {{ ModelName }}Controller::class);
});
```
🖼 Frontend
📂 resources/js/Pages/{{ ModelName }}/

Create:

Index.vue → table of data (using shadcn-vue Table)

Create.vue → form

Edit.vue → form

Show.vue → optional

Use useI18n for labels and titles:

```vue
<script setup>
import { useI18n } from 'vue-i18n'
const { t } = useI18n()
</script>
```
🛡 Security must:
Add policies & authorizeResource

Validate inputs strictly

Sanitize & filter data

Use HTTPS

Avoid exposing sensitive fields

Use pagination & eager loading

📢 AI suggestion system (after generation):
Add DTOs if complex

Add cache invalidation on store/update/delete

Add API Resource & apiResource routes if needed

Add tests (unit & feature)

Add events/listeners if required

🎯 Expected output:
Modular, testable, production-ready backend

Activity logs & permissions

Service Layer

Caching

Secure, clean code

Vue 3 + shadcn-vue frontend with i18n

✨ Replace placeholders before generation:

{{ ModelName }}

{{ table_name }}

{{ list of fields }}

{{ resourceRoute }}
