# Code Style Guidelines — Laravel NGO System

## PHP / Laravel

### Naming Conventions
```php
// Controllers: PascalCase + Controller suffix
class TrainingController extends Controller {}

// Models: PascalCase singular
class EconomicProject extends Model {}

// Methods: camelCase, descriptive verbs
public function approveSubmission(Request $request, Approval $approval) {}

// Variables: camelCase
$projectManager = User::find($id);

// Constants: UPPER_SNAKE_CASE
const MAX_FILE_SIZE_MB = 5;

// Database columns: snake_case
// approval_status, created_at, project_manager_id
```

### Controller Rules
- **Single Responsibility**: one controller per resource/feature
- Use **Form Requests** for all validation (never validate in controller directly)
- Return **Resource classes** for JSON responses
- Max method length: **30 lines** — extract to Service class if longer
- Always use `$this->authorize()` at the start of methods that need it

```php
// ✅ Good
public function store(TrainingRequest $request)
{
    $this->authorize('create', Training::class);
    $training = $this->trainingService->create($request->validated());
    return redirect()->route('manager.trainings.index')
                     ->with('success', 'تم إنشاء التدريب بنجاح');
}

// ❌ Bad — validation in controller, no authorization
public function store(Request $request)
{
    $request->validate([...]);
    Training::create($request->all());
}
```

### Model Rules
- Always define `$fillable` explicitly — **never use `$guarded = []`**
- Always define relationships with return types
- Use **SoftDeletes** on all main models
- Define casts for booleans, dates, enums

```php
class Training extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'project_id', 'name', 'beneficiary_count',
        'start_date', 'end_date', 'funder', 'gender_type',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
        'gender_type' => GenderType::class,  // PHP Enum
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function attendees(): HasMany
    {
        return $this->hasMany(Attendee::class);
    }
}
```

### Migrations
- One concept per migration file
- Always add `->comment('Arabic description')` on non-obvious columns
- Use `enum()` for fixed-value fields

```php
// ✅ Enum column
$table->enum('approval_status', ['pending', 'approved', 'rejected'])->default('pending');
$table->enum('gender_type', ['male', 'female', 'both'])->default('both');
$table->enum('marital_status', ['single', 'married', 'widowed', 'divorced']);
$table->boolean('has_disability')->default(false);
$table->boolean('is_active')->default(true)->comment('Account active status');
```

### Service Classes
Use for business logic that spans multiple models:

```php
// app/Services/ApprovalService.php
class ApprovalService
{
    public function approve(Approval $approval, User $approver): void
    {
        DB::transaction(function () use ($approval, $approver) {
            $approval->update([
                'approval_status' => 'approved',
                'approved_by'     => $approver->id,
                'approved_at'     => now(),
            ]);
            // trigger notifications, update related records...
        });
    }
}
```

---

## Blade Templates

### Layout Structure (RTL)
```html
<!-- All layouts must have RTL direction -->
<html lang="ar" dir="rtl">

<!-- Sidebar RIGHT, Content LEFT -->
<div class="flex flex-row-reverse min-h-screen">
    <aside class="w-64 fixed right-0"><!-- sidebar --></aside>
    <main class="flex-1 mr-64"><!-- content --></main>
</div>
```

### Component Naming
```
resources/views/
├── components/
│   ├── sidebar.blade.php
│   ├── stat-card.blade.php
│   ├── approval-badge.blade.php
│   ├── file-upload.blade.php
│   └── forms/
│       ├── attendee-row.blade.php
│       └── select-field.blade.php
```

### Alpine.js Usage
```html
<!-- Dynamic attendee rows -->
<div x-data="attendeeForm()">
    <template x-for="(attendee, index) in attendees" :key="index">
        <div>...</div>
    </template>
    <button @click="addAttendee()">إضافة شخص</button>
</div>

<script>
function attendeeForm() {
    return {
        attendees: [{}],
        addAttendee() { this.attendees.push({}); },
        removeAttendee(i) { this.attendees.splice(i, 1); }
    }
}
</script>
```

---

## Routing

### File Organization
```php
// routes/web.php — just includes
require __DIR__.'/admin.php';
require __DIR__.'/manager.php';
require __DIR__.'/data-entry.php';

// routes/admin.php
Route::middleware(['auth', 'role:admin', 'account.active'])
     ->prefix('admin')
     ->name('admin.')
     ->group(function () {
         Route::get('/dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');
         Route::resource('users', Admin\UserController::class);
     });

// routes/manager.php
Route::middleware(['auth', 'role:project_manager', 'account.active'])
     ->prefix('manager')
     ->name('manager.')
     ->group(function () {
         Route::get('/dashboard', [Manager\DashboardController::class, 'index'])->name('dashboard');
         Route::resource('projects', Manager\ProjectController::class);
         Route::resource('projects.trainings', Services\TrainingController::class);
         Route::resource('projects.economic', Services\EconomicController::class);
         Route::post('approvals/{approval}/approve', [Manager\ApprovalController::class, 'approve'])->name('approvals.approve');
         Route::post('approvals/{approval}/reject', [Manager\ApprovalController::class, 'reject'])->name('approvals.reject');
     });

// routes/data-entry.php
Route::middleware(['auth', 'role:data_entry', 'account.active'])
     ->prefix('entry')
     ->name('entry.')
     ->group(function () {
         Route::get('/dashboard', [DataEntry\EntryController::class, 'index'])->name('dashboard');
         Route::post('projects/{project}/training/submit', [DataEntry\EntryController::class, 'submitTraining'])->name('training.submit');
         Route::post('projects/{project}/economic/submit', [DataEntry\EntryController::class, 'submitEconomic'])->name('economic.submit');
     });
```

---

## Colors & Design Tokens (TailwindCSS)
```js
// tailwind.config.js
theme: {
    extend: {
        colors: {
            primary:  { DEFAULT: '#4F46E5', light: '#818CF8', dark: '#3730A3' },
            accent:   { DEFAULT: '#10B981', light: '#34D399', dark: '#059669' },
            warning:  '#F59E0B',
            danger:   '#EF4444',
            surface:  '#F8FAFC',
            sidebar:  '#1E293B',
        }
    }
}
```

---

## Import Order (PHP files)
1. `declare(strict_types=1);`
2. `namespace App\...;`
3. Laravel core imports
4. Third-party packages
5. App imports (alphabetical)
