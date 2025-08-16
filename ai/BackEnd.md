# 🚀 Laravel 12 CRUD Backend Module Generator – AI-Oriented Spec

## 📋 Goal
A precise, production-ready specification for generating a full CRUD backend module in Laravel 12 (PHP 8.2+), optimized for AI agents to read and implement consistently.

---

## 🧩 Tech Stack & Conventions
- Backend: Laravel 12, PHP 8.2+
- Frontend: Vue 3 + Inertia.js + shadcn-vue (already configured)
- Styling: TailwindCSS
- i18n: vue-i18n (ar/en configured)
- Packages: spatie/laravel-activitylog, spatie/laravel-permission
- Architecture: Service Layer (Repository optional)
- Cache: Redis (prefer Cache::tags)
- Standards:
    - declare(strict_types=1);
    - PSR-12 coding style
    - SOLID principles

---

## 🔖 Placeholders (Standardized)
- {{ Model }}: PascalCase model name (e.g., Project)
- {{ modelVariable }}: camelCase variable (e.g., project)
- {{ tableName }}: snake_case plural table (e.g., projects)
- {{ resourceRoute }}: resource route name/prefix (e.g., projects)
- {{ fields }}: array of field configs

---

## 🧾 Module Input (YAML)
```yaml
model: {{ Model }}
table: {{ tableName }}
resourceRoute: {{ resourceRoute }}
fields:
  - name: "name"
    type: "string"            # string, integer, boolean, date, json, text, uuid, etc.
    validation: "required|max:255"
    frontend_type: "text"     # text, textarea, select, checkbox, date, number, file, etc.
    options: null              # for selects (array or key=>label)
    relationship: null         # belongsTo, hasMany, etc.
    related_model: null        # e.g., User

options:
  api: false                   # generate API endpoints
  api_versioned: false
  use_dto: false
  full_text_search: false
  export: false                # PDF/Excel
  image_upload: false
  notifications: false
  advanced_filtering: false
```

---

## 📁 Implementation Steps

### 1) Migration – `database/migrations/create_{{ tableName }}_table.php`
- Always include: `$table->softDeletes();`, `$table->timestamps();`
- Add indexes for foreign keys, searchable columns, and filters
- Add unique `slug` if requested
- Add proper foreign key constraints
- Prefer correct column types (date vs datetime, json for structured data)

### 2) Model – `app/Models/{{ Model }}.php`
```php
<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class {{ Model }} extends Model
{
    use SoftDeletes, LogsActivity;

    protected $fillable = [/* all fillable fields */];

    protected $casts = [
        // e.g. 'meta' => 'array', 'published_at' => 'datetime'
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll();
    }

    // Define relationships automatically based on field config
}
```

### 3) Observer – `app/Observers/{{ Model }}Observer.php`
- Responsibilities:
    - Cache invalidation on created/updated/deleted/restored
    - Enhanced activity logging (custom descriptions if needed)
    - File cleanup for file fields (if any)
    - Image processing if `image_upload` enabled

Register in `App\Providers\AppServiceProvider::boot()`:
```php
use App\Models\{{ Model }};
use App\Observers\{{ Model }}Observer;

public function boot(): void
{
    {{ Model }}::observe({{ Model }}Observer::class);
}
```

### 4) Service Layer – `app/Services/{{ Model }}Service.php`
- Error handling with try/catch
- Prefer `Cache::tags('{{ tableName }}')` where supported
- Use transactions for create/update/delete

Key methods (signatures):
```php
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class {{ Model }}Service
{
    public function index(array $filters = []): LengthAwarePaginator
    {
        try {
            $cacheKey = '{{ tableName }}:list:' . md5(serialize($filters));

            return Cache::tags(['{{ tableName }}'])->remember($cacheKey, 3600, function () use ($filters) {
                return {{ Model }}::query()
                    ->with([/* relationships */])
                    ->when($filters['search'] ?? null, function ($query, $search) {
                        $query->where('name', 'like', "%{$search}%");
                    })
                    ->when($filters['date_from'] ?? null, function ($query, $date) {
                        $query->whereDate('created_at', '>=', $date);
                    })
                    ->when($filters['date_to'] ?? null, function ($query, $date) {
                        $query->whereDate('created_at', '<=', $date);
                    })
                    ->latest()
                    ->paginate(15);
            });
        } catch (\Throwable $e) {
            Log::error('Error fetching {{ tableName }}: ' . $e->getMessage(), ['filters' => $filters]);
            throw new \RuntimeException(__('messages.errors.fetch_failed'));
        }
    }

    public function show(int|string $id): {{ Model }}
    {
        try {
            $cacheKey = '{{ tableName }}:show:' . $id;
            return Cache::tags(['{{ tableName }}'])->remember($cacheKey, 3600, fn () => {{ Model }}::with([/* relationships */])->findOrFail($id));
        } catch (\Throwable $e) {
            Log::error('Error fetching {{ tableName }} item: ' . $e->getMessage(), ['id' => $id]);
            throw new \RuntimeException(__('messages.errors.fetch_failed'));
        }
    }

    public function store(array $data): {{ Model }}
    {
        try {
            return DB::transaction(function () use ($data) {
                if (isset($data['image']) && $data['image']) {
                    $data['image'] = $this->handleImageUpload($data['image']);
                }

                $model = {{ Model }}::create($data);

                Cache::tags(['{{ tableName }}'])->flush();
                // Optionally dispatch notifications/events here

                return $model;
            });
        } catch (\Throwable $e) {
            Log::error('Error creating {{ tableName }}: ' . $e->getMessage(), ['data' => $data]);
            throw new \RuntimeException(__('messages.errors.create_failed'));
        }
    }

    public function update({{ Model }} ${{ modelVariable }}, array $data): {{ Model }}
    {
        try {
            return DB::transaction(function () use (${{ modelVariable }}, $data) {
                if (isset($data['image']) && $data['image']) {
                    $data['image'] = $this->handleImageUpload($data['image']);
                }

                ${{ modelVariable }}->update($data);

                Cache::tags(['{{ tableName }}'])->flush();

                return ${{ modelVariable }};
            });
        } catch (\Throwable $e) {
            Log::error('Error updating {{ tableName }}: ' . $e->getMessage(), ['id' => ${{ modelVariable }}->id, 'data' => $data]);
            throw new \RuntimeException(__('messages.errors.update_failed'));
        }
    }

    public function delete({{ Model }} ${{ modelVariable }}): void
    {
        try {
            DB::transaction(function () use (${{ modelVariable }}) {
                ${{ modelVariable }}->delete();
                Cache::tags(['{{ tableName }}'])->flush();
            });
        } catch (\Throwable $e) {
            Log::error('Error deleting {{ tableName }}: ' . $e->getMessage(), ['id' => ${{ modelVariable }}->id]);
            throw new \RuntimeException(__('messages.errors.delete_failed'));
        }
    }

    private function handleImageUpload($image): string
    {
        $path = $image->store('{{ tableName }}/images', 'public');
        // Optional: optimize image here
        return $path;
    }
}
```

### 5) Permissions Seeder – `database/seeders/RoleAndPermissionSeeder.php`
Create consistent CRUD permissions (unify naming with Policy):
```php
Permission::firstOrCreate(['name' => 'view_{{ tableName }}']);
Permission::firstOrCreate(['name' => 'create_{{ tableName }}']);
Permission::firstOrCreate(['name' => 'update_{{ tableName }}']);
Permission::firstOrCreate(['name' => 'delete_{{ tableName }}']);
```
Assign to roles as needed (e.g., `admin`).

Run:
```bash
php artisan db:seed --class=RoleAndPermissionSeeder
```

### 6) Policy – `app/Policies/{{ Model }}Policy.php`
Permission-based authorization, aligned with seeder:
```php
public function viewAny(User $user): bool { return $user->can('view_{{ tableName }}'); }
public function view(User $user, {{ Model }} ${{ modelVariable }}): bool { return $user->can('view_{{ tableName }}'); }
public function create(User $user): bool { return $user->can('create_{{ tableName }}'); }
public function update(User $user, {{ Model }} ${{ modelVariable }}): bool { return $user->can('update_{{ tableName }}'); }
public function delete(User $user, {{ Model }} ${{ modelVariable }}): bool { return $user->can('delete_{{ tableName }}'); }
```
- Prefer Laravel policy auto-discovery, or map explicitly in `AuthServiceProvider` if needed.

### 7) Form Requests – `app/Http/Requests/{{ Model }}/`
Validation with clear error messages and authorization.
```php
// Store{{ Model }}Request.php
public function authorize(): bool { return $this->user()->can('create_{{ tableName }}'); }

public function rules(): array
{
    return [
        // rules based on fields config
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ];
}

public function messages(): array
{
    return [
        'name.required' => __('validation.custom.name.required'),
        'email.email' => __('validation.custom.email.email'),
        'image.max' => __('validation.custom.image.max'),
        'image.mimes' => __('validation.custom.image.mimes'),
    ];
}
```

### 8) Controller – `app/Http/Controllers/{{ Model }}Controller.php`
Thin controller delegating to service, with authorization and Inertia response.
```php
public function __construct(private readonly {{ Model }}Service $service)
{
    $this->authorizeResource({{ Model }}::class, '{{ modelVariable }}');
}

public function index(Request $request)
{
    try {
        $items = $this->service->index($request->all());

        $items->getCollection()->transform(function ($item) {
            return array_merge($item->toArray(), [
                'can_view' => Gate::allows('view', $item),
                'can_update' => Gate::allows('update', $item),
                'can_delete' => Gate::allows('delete', $item),
            ]);
        });

        return Inertia::render('{{ Model }}/Index', [
            '{{ modelVariable }}s' => $items,
            'can_create' => Gate::allows('create', {{ Model }}::class),
            'filters' => $request->only(['search', 'date_from', 'date_to']),
        ]);
    } catch (\Throwable $e) {
        return redirect()->back()->with('error', $e->getMessage());
    }
}

public function store(Store{{ Model }}Request $request)
{
    try {
        ${{ modelVariable }} = $this->service->store($request->validated());
        return redirect()->route('{{ resourceRoute }}.index')->with('success', __('messages.created'));
    } catch (\Throwable $e) {
        return redirect()->back()->with('error', $e->getMessage())->withInput();
    }
}
```

### 9) Routes – `routes/web.php`
```php
Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('{{ resourceRoute }}', {{ Model }}Controller::class)->names('{{ resourceRoute }}');
});
```

### 10) Notifications (optional) – `app/Notifications/{{ Model }}Notification.php`
```php
class {{ Model }}Notification extends Notification
{
    public function via($notifiable): array { return ['database', 'mail']; }

    public function toDatabase($notifiable): array
    {
        return [
            'title' => 'New {{ Model }} Created',
            'message' => 'A new {{ Model }} has been created',
            'action_url' => route('{{ resourceRoute }}.show', $this->{{ modelVariable }}),
        ];
    }
}
```

---

## 🛡️ Security
- All inputs validated via Form Requests
- CSRF enabled, XSS mitigated via escaping
- SQL injection protection through Eloquent
- Authorization enforced via Policies
- Rate limiting where appropriate
- File uploads: strictly validate type/size; store in `storage/app/public`

---

## ⚡ Performance
- Necessary DB indexes and eager loading
- Pagination (default 15)
- Redis caching with `Cache::tags('{{ tableName }}')` and proper invalidation
- Transactions for write operations
- Use `select()` for specific columns when beneficial

---

## 🧰 Error Handling
- Wrap DB operations in try/catch
- Log with context using `Log::error()`
- Throw user-friendly localized messages (e.g., `__('messages.errors.create_failed')`)
- Graceful fallbacks in controllers (redirect back with error flash)

---

## 🌐 i18n
- Reuse existing ar/en localization
- Validation errors via `resources/lang/{locale}/validation.php`
- Generic messages in `messages.php` (add keys like `created`, `errors.create_failed`, etc.)

---

## 🔎 Advanced Filtering (optional)
- Filters: `search`, `date_from`, `date_to` (extendable)
- For complex filters: add request DTO or dedicated Filter class

---

## ✅ Testing & QA
- Add feature tests for CRUD actions with policies
- Seed roles/permissions before tests
- Example commands:
```bash
php artisan test
php artisan db:seed --class=RoleAndPermissionSeeder
```

---

## ✅ Generation Rules (Recap)
1) Always include soft deletes and timestamps in migrations
2) Auto-generate and register observers
3) Implement caching with tagged keys and invalidation on writes
4) Comprehensive try/catch and logging in service methods
5) Permission-based policies aligned with seeded permissions
6) Pass permission flags to frontend via controller
7) Use relationships, casts, and eager loading appropriately
8) Use Form Requests for validation and authorization
9) Transactions for store/update/delete
10) Follow naming conventions and PSR-12; `declare(strict_types=1);`

---

## 🔍 Notes for AI
- Keep placeholder usage consistent: `{{ Model }}`, `{{ modelVariable }}`, `{{ tableName }}`, `{{ resourceRoute }}`
- Ensure policy permission strings match the seeder
- Use `Cache::tags` where the backend cache driver supports it (Redis/Memcached)
- If `image_upload` disabled, omit image handling paths entirely
- Prefer auto-discovery for policies; fall back to manual mapping only if needed
