# Security Requirements — NGO System

## Authentication & Authorization

### Middleware Stack (apply in this order)
```php
// Every protected route must have ALL THREE:
->middleware(['auth', 'role:ROLE_NAME', 'account.active'])
```

### RoleMiddleware Implementation
```php
// app/Http/Middleware/RoleMiddleware.php
class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!auth()->check() || !in_array(auth()->user()->role, $roles)) {
            abort(403, 'غير مصرح لك بالوصول إلى هذه الصفحة');
        }
        return $next($request);
    }
}
```

### CheckAccountActive Middleware
```php
// app/Http/Middleware/CheckAccountActive.php
class CheckAccountActive
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && !auth()->user()->is_active) {
            auth()->logout();
            return redirect()->route('login')
                ->withErrors(['email' => 'تم تعطيل هذا الحساب. تواصل مع المدير.']);
        }
        return $next($request);
    }
}
```

### Policy Rules
- Every controller method that modifies data MUST call `$this->authorize()`
- Never rely on route middleware alone for authorization
- Use Policies for resource-level checks, Gates for simple boolean checks

```php
// app/Policies/ProjectPolicy.php
class ProjectPolicy
{
    public function view(User $user, Project $project): bool
    {
        return match($user->role) {
            'admin'           => true,
            'project_manager' => $project->manager_id === $user->id,
            'data_entry'      => $project->status === 'active'
                                 && $project->dataEntryUsers->contains($user->id),
            default           => false,
        };
    }

    public function update(User $user, Project $project): bool
    {
        return $user->role === 'admin'
            || ($user->role === 'project_manager' && $project->manager_id === $user->id);
    }
}
```

---

## Input Validation

### Form Request Rules
```php
// ALWAYS use Form Requests — never validate in controllers
// app/Http/Requests/TrainingRequest.php
class TrainingRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name'              => 'required|string|max:255',
            'beneficiary_count' => 'required|integer|min:1|max:10000',
            'start_date'        => 'required|date|before_or_equal:end_date',
            'end_date'          => 'required|date|after_or_equal:start_date',
            'funder'            => 'required|string|max:255',
            'gender_type'       => 'required|in:male,female,both',
            'attendees'         => 'required|array|min:1',
            'attendees.*.name'  => 'required|string|max:255',
            'attendees.*.id_number' => 'nullable|string|max:20',
            'attendees.*.phone'     => 'nullable|string|max:20',
            'attendees.*.age'       => 'nullable|integer|min:1|max:120',
            'attendees.*.specialty' => 'nullable|string|max:255',
            'attendees.*.governorate' => 'nullable|string|max:100',
            'attendees.*.marital_status' => 'nullable|in:single,married,widowed,divorced',
            'attendees.*.has_disability'  => 'nullable|boolean',
        ];
    }
}
```

### Never Trust User Input
```php
// ❌ NEVER
Project::create($request->all());
$user->update($request->all());

// ✅ ALWAYS
Project::create($request->validated());
$user->update($request->safe()->only(['name', 'phone', 'specialty']));
```

---

## File Upload Security

### Validation Rules (always apply)
```php
'image'  => 'required|file|mimes:jpg,jpeg,png,webp|max:5120',  // 5MB max
'images' => 'required|array|max:10',
'images.*' => 'file|mimes:jpg,jpeg,png,webp|max:5120',
```

### Storage Rules
```php
// NEVER store files in public/ directory directly
// ALWAYS use Storage facade with private disk for sensitive docs

// ✅ Correct
$path = $request->file('image')->store('project-images', 'private');

// Serve via signed route, not direct URL
Route::get('files/{path}', [FileController::class, 'serve'])
     ->where('path', '.*')
     ->middleware(['auth', 'account.active'])
     ->name('files.serve');

// FileController::serve checks authorization before streaming
public function serve(string $path): StreamedResponse
{
    $this->authorize('viewFile', $path);  // Check policy
    return Storage::disk('private')->download($path);
}
```

### Rename Uploaded Files
```php
// Generate unguessable filename
$filename = Str::uuid() . '.' . $request->file('image')->extension();
$path = $request->file('image')->storeAs('project-images', $filename, 'private');
```

---

## SQL Injection Prevention
```php
// ❌ NEVER use raw queries with user input
DB::select("SELECT * FROM projects WHERE name = '{$name}'");

// ✅ ALWAYS use query builder or Eloquent
Project::where('name', $name)->get();
DB::select('SELECT * FROM projects WHERE name = ?', [$name]);

// For complex filters, use query builder chaining
$query = Project::query();
if ($request->has('status')) {
    $query->where('status', $request->validated('status'));
}
```

---

## XSS Prevention
```blade
{{-- ❌ Never use unescaped output for user data --}}
{!! $user->name !!}

{{-- ✅ Always use escaped output --}}
{{ $user->name }}

{{-- Only use {!! !!} for trusted HTML (e.g., your own rendered Markdown) --}}
```

---

## CSRF Protection
- All POST/PUT/DELETE forms must include `@csrf`
- AJAX requests: include `X-CSRF-TOKEN` header from meta tag
- API routes (if added later): use Sanctum tokens

---

## Password Security
```php
// In UserController when creating/updating users
'password' => 'required|string|min:8|confirmed',

// Store:
User::create([
    ...
    'password' => bcrypt($request->validated('password')),
    // OR: Hash::make($request->validated('password'))
]);
```

---

## Rate Limiting
```php
// routes/web.php — protect login route
RateLimiter::for('login', function (Request $request) {
    return Limit::perMinute(5)->by($request->ip());
});

// Apply in routes/auth.php
Route::post('/login', [AuthController::class, 'login'])
     ->middleware('throttle:login');
```

---

## Logging & Audit
```php
// Log all approval actions
activity()
    ->performedOn($approval)
    ->causedBy(auth()->user())
    ->withProperties(['status' => 'approved'])
    ->log('Approval approved');

// Log admin user disable/enable actions
Log::channel('audit')->info("User {$user->id} disabled by admin {$admin->id}");
```

---

## Checklist Before Every PR
- [ ] No `$request->all()` passed directly to model
- [ ] All routes have `role:` middleware
- [ ] All controller actions call `$this->authorize()`
- [ ] File uploads validated for type and size
- [ ] Files stored in `private` disk, served via authenticated route
- [ ] No raw SQL with user input
- [ ] No `{!! !!}` with user-generated content
- [ ] Sensitive actions logged
