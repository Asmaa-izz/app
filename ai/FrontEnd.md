# �� Vue 3 + Inertia.js Generic Entity UI Generator – AI-Oriented Spec

## 🌐 Tech Stack & Standards

- Framework: Vue 3 (Composition API)
- Router: Inertia.js (Laravel Inertia adapter)
- Styling: TailwindCSS + shadcn-vue components (`https://www.shadcn-vue.com/`)
- i18n: vue-i18n (Arabic/English already configured)
- State: Pinia (optional, modular)
- Validation: Backend Form Requests + client-side hints
- Access Control: Permissions passed from backend (`Gate`-based flags)
- Placeholders (standardized):
    - `{{ Model }}` (e.g., Project)
    - `{{ modelVariable }}` (e.g., project)
    - `{{ tableName }}` (e.g., projects)
    - `{{ resourceRoute }}` (e.g., projects)

---

## 🎯 Goal
- Prefer reusing a small set of generic “Entity” pages and components instead of generating dedicated pages per model.
- Let the AI infer whether a new dedicated page is needed based on the project brief and frontend rules; otherwise, extend the registry/config and reuse generic pages.

---

## 🧾 Module Input (YAML)
```yaml
model: {{ Model }}
resourceRoute: {{ resourceRoute }}
features:
  create: true
  update: true
  delete: true
  show: false                # if true, enable dedicated view panel/route
  image_upload: false
  advanced_filtering: false
  custom_ui_needed: false    # if true, AI may create dedicated pages

ui:
  list:
    columns:
      - key: "name"
        label: "entities.{{ tableName }}.name"
        sortable: true
        searchable: true
      # add more columns as needed
    actions:
      create: true
      edit: true
      delete: true
  form:
    fields:
      - key: "name"
        type: "string"          # string, text, boolean, number, date, select, file, image
        required: true
        component: "Input"      # maps to shadcn-vue/Input
        props: {}
      # additional fields from backend field config
  filters:
    search: true
    date_from: false
    date_to: false
    extra: []                  # extra filter configs

strategy:
  page_generation: "auto"      # auto | never | always
  prefer_generic: true          # when true, reuse Entity pages if possible
```

---

## 🧠 AI Decision Framework
- If `strategy.page_generation` is `never`: do NOT create model-specific pages. Update the registry and reuse generic Entity pages.
- If `strategy.page_generation` is `always`: create dedicated pages for this model (only when `custom_ui_needed` is true or explicitly requested).
- If `auto` (default):
    - Reuse generic pages if the module fits standard CRUD with configurable list/form.
    - Create dedicated pages ONLY IF:
        - Non-trivial custom UI/flows (wizards, nested editors, complex dashboards)
        - Domain-specific interactions that require custom components/layouts
        - Performance/UI constraints unsuitable for generic components

---

## 🧱 Generic Entity Pages (Preferred)

### Directory
```
resources/js/Pages/Entity/
├── Index.vue           # List + inline Create/Edit modals/drawers
├── components/
│   ├── EntityTable.vue
│   ├── EntityForm.vue
│   └── EntityShow.vue  # optional
├── registry.js         # entity registry (config per entity)
```

### Registry – `resources/js/Pages/Entity/registry.js`
- Central place to map an entity key to UI config.
- The AI updates this registry to onboard a new model without adding new pages.

Example entry:
```js
export const entityRegistry = {
  '{{ tableName }}': {
    routeName: '{{ resourceRoute }}',
    i18nKey: 'entities.{{ tableName }}',
    permissions: {
      create: 'create_{{ tableName }}',
      update: 'update_{{ tableName }}',
      delete: 'delete_{{ tableName }}',
      view:   'view_{{ tableName }}'
    },
    list: {
      columns: [
        { key: 'name', label: 'entities.{{ tableName }}.name', sortable: true, searchable: true },
      ]
    },
    form: {
      fields: [
        { key: 'name', type: 'string', required: true, component: 'Input', props: {} },
      ]
    },
    filters: { search: true, date_from: false, date_to: false, extra: [] }
  }
}
```

### Data Flow
- Backend controller returns Inertia props:
    - `items` (paginated)
    - `can_create`, `can_update`, `can_delete`
    - Optional `uiSchema` to override/extend registry at runtime
- Frontend `Entity/Index.vue` merges `uiSchema` over `registry.js` entry.

---

## 📦 Input Type Mapping (shadcn-vue)

| Backend Field Type | Vue Input Component                    |
|--------------------|----------------------------------------|
| string             | `<Input />`                            |
| text               | `<Textarea />`                         |
| boolean            | `<Switch />`                           |
| integer, float     | `<Input type="number" />`             |
| date               | `<Input type="date" />`               |
| file, image        | `<CustomImageUploader />` (with preview) |
| enum, select       | `<Select :options="..." />`           |

---

## 🧪 Validation Handling
- Rely on Laravel Form Request validation; display backend errors.
- Example client code:
```js
if (error.response?.status === 422) {
  errors.value = error.response.data.errors
}
```
- i18n error messages via `resources/js/lang/{locale}.json`.

---

## 📥 File Upload (Optional)
- Drag & drop with preview
- Max size: 2MB; Formats: jpeg, png, jpg, gif
- Suggested UI:
```vue
<input type="file" @change="handleUpload" accept="image/*" />
<img :src="previewUrl" v-if="previewUrl" class="w-32 h-32 object-cover mt-2" />
```

---

## 🔐 Permissions (Passed from Backend)
- Props from backend:
```js
{
  can_create: Boolean,
  can_update: Boolean,
  can_delete: Boolean
}
```
- Usage:
```vue
<Button v-if="can_create">{{ t('actions.create') }}</Button>
```

---

## 🔍 Filters & Search
- Support `search`, `date_from`, `date_to`, and extensible `extra` filters.
- Bind reactively and send via Inertia:
```vue
<Input v-model="filters.search" />
```
```js
router.get(route('{{ resourceRoute }}.index'), filters)
```

---

## 🌍 i18n Policy
- Only two files:
    - `resources/js/lang/en.json`
    - `resources/js/lang/ar.json`
- Use module namespaces under `entities.{{ tableName }}`.
- Access via dot-notation: `t('entities.{{ tableName }}.title')`.

---

## 🔁 Generic Entity Page Template (Index.vue)
- Responsibilities:
    - Render table from `registry` + optional `uiSchema`
    - Manage filters and pagination
    - Open Create/Edit as modal/drawer using `EntityForm`
    - Respect permission flags from backend

Submission example:
```js
form.post(route('{{ resourceRoute }}.store'), {
  onSuccess: () => toast.success(t('messages.created_successfully')),
  onError: () => toast.error(t('messages.error_occurred')),
})
```

---

## ⚙️ Performance & UX
- Skeleton loaders/spinners while loading
- Debounced search
- Preserve filters/scroll with Inertia
- Lazy-load where applicable

---

## 🧭 When to Create Dedicated Pages
Create model-specific pages ONLY if one or more apply:
- Complex multi-step flows/wizards
- Highly customized layout or interactions not covered by generic components
- Domain visualizations (charts, timelines) tightly coupled to the entity
- Performance tuning requiring specialized pagination/virtualization

If none apply, update `registry.js` and reuse `Entity` pages.

---

## 🧩 Brief the AI (What to Provide)
- Project idea and UX guidelines (short description)
- For each entity:
    - `model`, `resourceRoute`, and permissions
    - Desired list columns and form fields (or let AI infer from backend field config)
    - Any custom UI notes (if `custom_ui_needed: true`)
- Preferred `strategy.page_generation`: `auto` (default), `never`, or `always`

AI will then:
1) Decide reuse vs create based on `strategy` and complexity.
2) If reuse: add/extend entry in `registry.js`, adjust `Entity` components as needed.
3) If create: scaffold minimal dedicated pages respecting the same conventions and i18n.

---

## 📦 Example File Structure (Generic-first)
```
resources/js/Pages/Entity/
├── Index.vue
├── components/
│   ├── EntityTable.vue
│   ├── EntityForm.vue
│   └── EntityShow.vue
├── registry.js
```

---

## 🏁 Final Output Requirements
- Generic-first implementation (registry + Entity pages)
- Uses shadcn-vue components consistently
- Fully responsive and accessible
- Arabic/English via centralized i18n
- Permission-aware UI
- Clean, maintainable, production-ready

---

> Note: Replace all `{{ placeholder }}` values with project-specific values. Prefer updating `registry.js` over generating new pages unless `custom_ui_needed` or `strategy.page_generation` requires otherwise.

