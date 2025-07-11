# 🚀 **Laravel 12 CRUD Backend Module Generator - Optimized Prompt**

## 📋 **Context & Requirements**
You are an expert Laravel developer creating a complete, production-ready CRUD module following modern Laravel best practices.

### **Tech Stack:**
- **Backend:** Laravel 12 (PHP 8.2+)
- **Frontend:** Vue 3 + Inertia.js + shadcn-vue components
- **Styling:** TailwindCSS
- **Internationalization:** vue-i18n (Arabic & English already configured)
- **Packages:** spatie/laravel-activitylog, spatie/laravel-permission (already installed)
- **Architecture:** Service Layer pattern + Repository (if needed)
- **Caching:** Laravel Cache with Redis

---

## 🎯 **Module Configuration**

### **Core Information:**
```yaml
Model: {{ ModelName }}
Table: {{ table_name }}
Resource Route: {{ resourceRoute }}
```

### **Fields Configuration:**
```yaml
fields:
  - name: "{{ field_name }}"
    type: "{{ field_type }}" # string, integer, boolean, date, json, etc.
    validation: "{{ validation_rules }}" # required, nullable, unique, etc.
    frontend_type: "{{ input_type }}" # text, textarea, select, checkbox, etc.
    options: "{{ field_options }}" # for select fields, relationships, etc.
    relationship: "{{ relationship_type }}" # belongsTo, hasMany, etc. (if applicable)
    related_model: "{{ RelatedModel }}" # if relationship exists
```

---

## 🤖 **Pre-Generation Questions** (Limited Set)

**IMPORTANT: Only ask these essential questions:**

1. **API Requirements:**
    - Generate REST API endpoints? (Default: No)
    - API versioning needed? (Default: No)

2. **Advanced Features:**
    - Use DTOs for data transfer? (Default: No)
    - Add full-text search? (Default: No)
    - Export functionality (PDF, Excel)? (Default: No)

3. **Media & Notifications:**
    - Add image upload with size limits and validation? (Default: No)
    - Include notification system (email, database)? (Default: No)
    - Add advanced filtering (date ranges, multiple select)? (Default: No)

4. **Custom Requirements:**
    - Any custom business logic or validations?(Default: No)
    - Integration with external APIs?(Default: No)

---

## 📁 **Implementation Structure**

### **Step 1: Migration**
📂 `database/migrations/create_{{ table_name }}_table.php`

**Requirements:**
- Include `$table->softDeletes()` and `$table->timestamps()` automatically
- Add slug field with unique index if "slug" field is mentioned
- Use proper column types and indexes for all fields
- Add foreign key constraints for any detected relationships
- Create indexes for frequently queried columns
- Include image/file columns if media upload is enabled

### **Step 2: Model**
📂 `app/Models/{{ ModelName }}.php`

**Must include:**
```php
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class {{ ModelName }} extends Model
{
    use SoftDeletes, LogsActivity;
    
    protected $fillable = [/* all fields */];
    
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll();
    }
    
    // Auto-generate relationships based on field configuration
    // Add proper casting for date/json fields
    // Include accessors/mutators if needed
    // Add image handling methods if media upload is enabled
}
```

### **Step 3: Observer** (Auto-Generated)
📂 `app/Observers/{{ ModelName }}Observer.php`

**Generate with:**
```bash
php artisan make:observer {{ ModelName }}Observer --model={{ ModelName }}
```

**Must include:**
- Cache invalidation on all CRUD operations (created, updated, deleted)
- Enhanced activity logging with custom descriptions
- File cleanup on delete if file fields exist
- Image optimization and resizing if media upload is enabled
- Any custom business logic hooks

### **Step 4: Service Layer**
📂 `app/Services/{{ ModelName }}Service.php`

**Must include comprehensive error handling with try-catch:**
```php
public function index(array $filters = []): Collection
{
    try {
        $cacheKey = '{{ table_name }}_list_' . md5(serialize($filters));
        
        return Cache::remember($cacheKey, 3600, function () use ($filters) {
            return {{ ModelName }}::with(['relationships'])
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
        
    } catch (\Exception $e) {
        Log::error('Error fetching {{ table_name }}: ' . $e->getMessage());
        throw new \Exception('Failed to fetch data. Please try again.');
    }
}

public function store(array $data): {{ ModelName }}
{
    try {
        DB::beginTransaction();
        
        // Handle file upload if enabled
        if (isset($data['image']) && $data['image']) {
            $data['image'] = $this->handleImageUpload($data['image']);
        }
        
        ${{ variableName }} = {{ ModelName }}::create($data);
        
        // Clear related cache
        Cache::forget('{{ table_name }}_list');
        
        // Send notification if enabled
        if (config('app.notifications_enabled')) {
            $this->sendNotification(${{ variableName }}, 'created');
        }
        
        DB::commit();
        return ${{ variableName }};
        
    } catch (\Exception $e) {
        DB::rollback();
        Log::error('Error creating {{ table_name }}: ' . $e->getMessage());
        throw new \Exception('Failed to create record. Please try again.');
    }
}

// Image handling method (if media upload is enabled)
private function handleImageUpload($image): string
{
    $path = $image->store('{{ table_name }}/images', 'public');
    
    // Optimize image if needed
    $this->optimizeImage(storage_path('app/public/' . $path));
    
    return $path;
}
```

### **Step 5: Policy** (Permission-Based)
📂 `app/Policies/{{ ModelName }}Policy.php`

**Must include permission-based authorization:**
```php
public function viewAny(User $user): bool
{
    return $user->can('view_{{ table_name }}');
}

public function view(User $user, {{ ModelName }} ${{ variableName }}): bool
{
    return $user->can('view_{{ table_name }}');
}

public function create(User $user): bool
{
    return $user->can('create_{{ table_name }}');
}

public function update(User $user, {{ ModelName }} ${{ variableName }}): bool
{
    return $user->can('update_{{ table_name }}');
}

public function delete(User $user, {{ ModelName }} ${{ variableName }}): bool
{
    return $user->can('delete_{{ table_name }}');
}
```

### **Step 6: Permissions Seeder**
📂 `database/seeders/{{ ModelName }}PermissionSeeder.php`

**Auto-create permissions:**
```php
$permissions = [
    'view_{{ table_name }}',
    'create_{{ table_name }}',
    'update_{{ table_name }}',
    'delete_{{ table_name }}'
];
```

### **Step 7: Form Requests**
📂 `app/Http/Requests/{{ ModelName }}/`

**With comprehensive validation and clear error messages:**
```php
// Store{{ ModelName }}Request.php
public function rules(): array
{
    return [
        // validation rules based on field configuration
        // Image validation if media upload is enabled
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ];
}

public function messages(): array
{
    return [
        'name.required' => 'The name field is required',
        'email.email' => 'Invalid email format',
        'image.max' => 'Image size must not exceed 2MB',
        'image.mimes' => 'Image must be of type: jpeg, png, jpg, gif',
        // Error messages for all fields
    ];
}
```

### **Step 8: Controller**
📂 `app/Http/Controllers/{{ ModelName }}Controller.php`

**Must be thin and include:**
```php
public function __construct({{ ModelName }}Service $service)
{
    $this->service = $service;
    $this->authorizeResource({{ ModelName }}::class, '{{ variableName }}');
}

public function index(Request $request)
{
    try {
        ${{ variableName }}s = $this->service->index($request->all());
        
        return Inertia::render('{{ ModelName }}/Index', [
            '{{ variableName }}s' => ${{ variableName }}s,
            'can_create' => Gate::allows('create', {{ ModelName }}::class),
            'can_update' => Gate::allows('update', {{ ModelName }}::class),
            'can_delete' => Gate::allows('delete', {{ ModelName }}::class),
            'filters' => $request->only(['search', 'date_from', 'date_to']),
        ]);
        
    } catch (\Exception $e) {
        return redirect()->back()->with('error', $e->getMessage());
    }
}

public function store(Store{{ ModelName }}Request $request)
{
    try {
        ${{ variableName }} = $this->service->store($request->validated());
        
        return redirect()->route('{{ resourceRoute }}.index')
            ->with('success', '{{ ModelName }} created successfully');
            
    } catch (\Exception $e) {
        return redirect()->back()
            ->with('error', $e->getMessage())
            ->withInput();
    }
}
```

### **Step 9: Routes**
📂 `routes/web.php`

```php
Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('{{ resourceRoute }}', {{ ModelName }}Controller::class);
});
```

### **Step 10: Notification System** (If Enabled)
📂 `app/Notifications/{{ ModelName }}Notification.php`

```php
class {{ ModelName }}Notification extends Notification
{
    public function via($notifiable): array
    {
        return ['database', 'mail'];
    }
    
    public function toDatabase($notifiable): array
    {
        return [
            'title' => 'New {{ ModelName }} Created',
            'message' => 'A new {{ modelName }} has been created',
            'action_url' => route('{{ resourceRoute }}.show', $this->{{ variableName }}),
        ];
    }
}
```

### **Step 11: Frontend Components**
📂 `resources/js/Pages/{{ ModelName }}/`

**Create files using existing i18n setup:**
- `Index.vue` - Data table with shadcn-vue components and advanced filtering
- `Create.vue` - Creation form with image upload (if enabled)
- `Edit.vue` - Edit form with image preview and replacement
- `Show.vue` - Detail view with image display (optional)

**Requirements:**
- Use existing Arabic/English language files: `resources/js/lang/ar/{{ table_name }}.json` and `resources/js/lang/en/{{ table_name }}.json`
- Implement proper form validation with clear error messages
- Add loading states and error handling
- Use shadcn-vue components consistently
- Pass permission checks from controller to frontend
- Include image preview and drag-drop upload (if media upload is enabled)
- Add advanced filtering with date pickers and multi-select

**Example Index.vue structure:**
```vue
<script setup>
import { useI18n } from 'vue-i18n'
import { ref, reactive } from 'vue'

const { t } = useI18n()

const props = defineProps({
    {{ variableName }}s: Object,
    can_create: Boolean,
    can_update: Boolean,
    can_delete: Boolean,
    filters: Object,
})

const filters = reactive({
    search: props.filters.search || '',
    date_from: props.filters.date_from || '',
    date_to: props.filters.date_to || '',
})

// Image handling (if enabled)
const handleImageUpload = (file) => {
    // Validate image size and type
    if (file.size > 2048 * 1024) {
        alert('Image size must not exceed 2MB')
        return false
    }
    
    const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif']
    if (!allowedTypes.includes(file.type)) {
        alert('Invalid image format. Please use JPEG, PNG, JPG, or GIF')
        return false
    }
    
    return true
}
</script>
```

---

## 🛡️ **Security Implementation**

**Auto-implement these security measures:**
- All inputs validated and sanitized through Form Requests
- CSRF protection enabled by default
- XSS protection with proper escaping
- SQL injection prevention through Eloquent ORM
- Authorization checks via Policy and Gate
- Rate limiting on sensitive endpoints
- Sensitive data excluded from API responses
- Image upload security with file type and size validation
- File storage in secure directories with proper permissions

---

## 🚀 **Performance Optimizations**

**Auto-implement:**
- Database indexes on frequently queried columns
- Eager loading for relationships to prevent N+1 queries
- Pagination for large datasets (15 items per page)
- Redis caching with proper cache invalidation
- Query optimization using select() for specific columns
- Proper use of database transactions
- Image optimization and compression for uploaded files
- Lazy loading for images in frontend components

---

## 📊 **Error Handling Standards**

**All methods must include:**
- Try-catch blocks for all database operations
- Database transactions for data integrity
- Clear error messages for user-facing errors
- Proper logging with Log::error() for debugging
- Graceful fallbacks for failed operations
- Consistent error response format
- File upload error handling with user-friendly messages

---

## 🎯 **Expected Output Quality**

The generated code must be:
- **Production-ready:** Complete functional code, no placeholders
- **Secure:** Following all Laravel security best practices
- **Performant:** Optimized queries and caching implemented
- **Maintainable:** Clean, well-documented code structure
- **Bilingual:** Full Arabic/English support using existing i18n setup
- **Error-resistant:** Comprehensive error handling throughout
- **Feature-rich:** Including requested features like image upload, notifications, and filtering

---

## 📝 **Code Generation Rules**

1. **Always include soft deletes and timestamps** in migrations
2. **Auto-generate observers** for activity logging and cache management
3. **Implement caching** in all service methods
4. **Use existing i18n structure** - don't create new language files
5. **Add comprehensive error handling** with try-catch blocks
6. **Generate permission-based policies** with proper authorization
7. **Include relationships** automatically based on field configuration
8. **Pass permission checks** to frontend components
9. **Use clear error messages** for better user experience
10. **Follow Laravel naming conventions** and PSR-12 standards
11. **Include image handling** with proper validation and optimization (if enabled)
12. **Implement notification system** with database and email channels (if enabled)
13. **Add advanced filtering** with date ranges and multi-select options (if enabled)
14. **Ensure mobile responsiveness** for all frontend components
15. **Add loading states** and proper user feedback throughout the application

---

## 🔧 **Additional Features Configuration**

### **Image Upload Features (If Enabled):**
- Maximum file size: 2MB
- Allowed formats: JPEG, PNG, JPG, GIF
- Automatic image optimization and resizing
- Multiple image upload support
- Image preview and crop functionality
- Secure file storage with proper naming

### **Notification Features (If Enabled):**
- Database notifications for admin actions
- Email notifications for important events
- Real-time notifications using broadcasting
- Notification preferences per user
- Notification history and read status

### **Advanced Filtering Features (If Enabled):**
- Date range filtering with date pickers
- Multi-select dropdown filters
- Search across multiple fields
- Export filtered results
- Save and load filter presets
- Advanced search with operators (contains, equals, etc.)

---

> **Note:** Replace all `{{ placeholder }}` values with actual project-specific information before generation. Default answers are "No" for all optional features unless specifically requested.
