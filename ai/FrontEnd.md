# 🎨 Vue 3 + Inertia.js Frontend CRUD Module Generator - Optimized Prompt

## 🌐 Tech Stack

- **Framework:** Vue 3 (Composition API)
- **Router:** Inertia.js (Laravel Inertia adapter)
- **Styling:** Tailwind CSS + **shadcn-vue** components (https://www.shadcn-vue.com/)
- **Language Support:** vue-i18n (already configured - Arabic & English)
- **State Management:** Pinia (optional)
- **Forms & Validation:** Vee-validate / native + Laravel backend validation
- **File Upload:** Drag-drop with preview (optional)
- **Access Control:** Permissions passed from backend (Gate-based)

---

## 📁 Module Details

```yaml
Module: {{ ModelName }}
Route: /{{ route_prefix }}
Directory: resources/js/Pages/{{ ModelName }}
```

---

## 🧩 Pages to Generate

1. `Index.vue`
    - Paginated data table
    - Search input
    - Date range filters (if enabled)
    - Permission-based action buttons (create/edit/delete)
    - i18n labels and dynamic columns

2. `Create.vue`
    - Form to create a new record
    - i18n field labels and validation messages
    - File/image upload with preview (if enabled)

3. `Edit.vue`
    - Form to edit an existing record
    - Load current data on mount
    - File/image preview and replace
    - Permission checks for editing

4. `Show.vue` (Optional)
    - Read-only detail view of a record
    - Image display (if enabled)

---

## 📦 Input Type Mapping (Based on backend field config)

| Backend Field Type | Vue Input Component     |
|--------------------|------------------------|
| string             | `<Input />`             |
| text               | `<Textarea />`          |
| boolean            | `<Switch />`            |
| integer, float     | `<Input type="number" />` |
| date               | `<Input type="date" />`  |
| file, image        | `<CustomImageUploader />` (with preview) |
| enum, select       | `<Select :options="..." />` |

---

## 🧪 Validation Handling

- Use Laravel Form Requests backend validation
- Display validation errors returned by backend
- Example error handling in Vue:

```js
if (error.response?.status === 422) {
  errors.value = error.response.data.errors
}
```

- Use i18n keys for error messages:

```vue
<span class="text-red-500">{{ t('validation.name_required') }}</span>
```

---

## 📥 File Upload (If Enabled)

- Drag & drop support with image preview
- Maximum file size: 2MB
- Allowed formats: jpeg, png, jpg, gif
- Optional cropping (if required)

Example usage:

```vue
<input type="file" @change="handleUpload" accept="image/*" />
<img :src="previewUrl" v-if="previewUrl" class="w-32 h-32 object-cover mt-2" />
```

---

## 🔐 Permissions (Passed from Backend)

Props from backend controller:

```js
{
  can_create: Boolean,
  can_update: Boolean,
  can_delete: Boolean
}
```

Use permissions in UI:

```vue
<Button v-if="can_create">{{ t('actions.create') }}</Button>
```

---

## 🔍 Filters & Search (If Enabled)

- Search input field
- Date range filters: `date_from`, `date_to`
- Multi-select filters (optional)

Bind filters reactively:

```vue
<Input v-model="filters.search" />
```

Send filter parameters via Inertia:

```js
router.get(route('{{ route_prefix }}.index'), filters)
```

---

## 🌍 i18n Translation Guidelines

### Translation Files Structure

- All translations must be centralized in **only two files**:

    - `resources/js/lang/en.json` (English)
    - `resources/js/lang/ar.json` (Arabic)

- Avoid splitting translation keys across multiple files (e.g., avoid `users.json`, `products.json`) to prevent duplication and ease maintenance.

---

### Adding Translations

- Add translations inside the language files under unique namespaces (keys) per module or section.

- Example for users module (`en.json`):

```json
{
  "users": {
    "title": "Users",
    "name": "Name",
    "email": "Email",
    "actions": {
      "create": "Create",
      "edit": "Edit",
      "delete": "Delete"
    }
  },
  "common": {
    "save": "Save",
    "cancel": "Cancel"
  }
}
```

Corresponding `ar.json`:

```json
{
  "users": {
    "title": "المستخدمون",
    "name": "الاسم",
    "email": "البريد الإلكتروني",
    "actions": {
      "create": "إنشاء",
      "edit": "تعديل",
      "delete": "حذف"
    }
  },
  "common": {
    "save": "حفظ",
    "cancel": "إلغاء"
  }
}
```

---

### Important Notes

- **Do not duplicate translation keys** inside the same file.
- Avoid creating multiple translation files per module.
- Add new modules/sections by adding keys inside these two main files.
- Access translations using dot notation: `t('users.title')` or `t('common.save')`.

---

### Example Usage in Vue

```vue
<template>
  <h1>{{ t('users.title') }}</h1>
  <label>{{ t('users.name') }}</label>
  <button>{{ t('common.save') }}</button>
</template>

<script setup>
import { useI18n } from 'vue-i18n'
const { t } = useI18n()
</script>
```

---

## 🔁 Form Logic Template (Example)

```vue
<script setup>
import { ref } from 'vue'
import { useForm } from '@inertiajs/vue3'
import { useI18n } from 'vue-i18n'

const { t } = useI18n()

const form = useForm({
  name: '',
  email: '',
  // other fields...
})

const submit = () => {
  form.post(route('{{ route_prefix }}.store'), {
    onSuccess: () => {
      toast.success(t('messages.created_successfully'))
    },
    onError: () => {
      toast.error(t('messages.error_occurred'))
    },
  })
}
</script>
```

---

## ⚙️ Performance and UX

- Use skeleton loaders or spinners while loading data
- Debounce search input to reduce requests
- Smooth transitions for modals and forms
- Preserve filters and scroll position using Inertia
- Lazy load images where applicable

---

## 📋 Example File Structure

```
resources/js/Pages/{{ ModelName }}/
├── Index.vue
├── Create.vue
├── Edit.vue
├── Show.vue
```

---

## 📌 Best Practices

1. Always use i18n strings for all labels, buttons, and validation messages.
2. Use permissions from backend to conditionally show/hide UI elements.
3. Wrap root nodes properly; avoid multiple root elements in templates.
4. Use `defineProps` and `defineEmits` correctly in script setup.
5. Encapsulate reusable UI parts, like image uploaders, into components.
6. Validate images on frontend before submitting to backend.
7. Handle Inertia progress with NProgress or similar UI feedback.

---

## 🏁 Final Output Requirements

- Vue 3 with Composition API
- Compatible with Inertia.js routing and page lifecycle
- Uses shadcn-vue components consistently
- Fully responsive and accessible
- Full Arabic and English support via vue-i18n centralized files
- Permission-aware UI elements
- Clean, maintainable, and production-ready code

---

> **Note:** Replace all `{{ placeholder }}` values with your actual project-specific information before generating.

