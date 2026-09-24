# Testing Conventions — NGO System

## Framework
- **PHPUnit** via Laravel's built-in test suite
- **Pest PHP** preferred for new tests (expressive syntax)
- Test database: SQLite in-memory (`:memory:`) for speed

## Test Types & Priority

| Type | Location | Priority |
|------|----------|----------|
| Feature (HTTP) | `tests/Feature/` | HIGH — test every route |
| Unit (Services) | `tests/Unit/` | HIGH — test business logic |
| Model (Relationships) | `tests/Unit/Models/` | MEDIUM |
| Browser (Dusk) | `tests/Browser/` | LOW — only critical flows |

---

## Directory Structure
```
tests/
├── Feature/
│   ├── Auth/
│   │   ├── LoginTest.php
│   │   └── RoleRedirectTest.php
│   ├── Admin/
│   │   ├── UserManagementTest.php
│   │   └── StatisticsTest.php
│   ├── Manager/
│   │   ├── ProjectTest.php
│   │   ├── TrainingTest.php
│   │   ├── EconomicProjectTest.php
│   │   └── ApprovalTest.php
│   └── DataEntry/
│       ├── SubmissionTest.php
│       └── ActiveProjectsOnlyTest.php
├── Unit/
│   ├── Services/
│   │   ├── ApprovalServiceTest.php
│   │   └── StatisticsServiceTest.php
│   └── Models/
│       ├── UserTest.php
│       └── ProjectTest.php
└── TestCase.php
```

---

## Naming Rules
```php
// Test method names: descriptive snake_case or natural language (Pest)
it('allows project manager to approve data entry submission');
it('prevents disabled users from logging in');
it('data entry user can only see active projects');
it('auto approves submissions made by project manager');

// PHPUnit style:
public function test_disabled_user_cannot_login(): void {}
public function test_project_manager_sees_pending_approvals(): void {}
```

---

## Test Templates

### Feature Test (HTTP)
```php
// tests/Feature/Manager/ApprovalTest.php
use App\Models\{User, Project, Training, Approval};
use function Pest\Laravel\{actingAs, post, assertDatabaseHas};

it('project manager can approve a pending submission', function () {
    $manager = User::factory()->projectManager()->create();
    $project = Project::factory()->for($manager)->create(['status' => 'active']);
    $approval = Approval::factory()->pending()->for($project)->create();

    actingAs($manager)
        ->post(route('manager.approvals.approve', $approval))
        ->assertRedirect()
        ->assertSessionHas('success');

    assertDatabaseHas('approvals', [
        'id' => $approval->id,
        'approval_status' => 'approved',
        'approved_by' => $manager->id,
    ]);
});

it('data entry user cannot approve submissions', function () {
    $dataEntry = User::factory()->dataEntry()->create();
    $approval = Approval::factory()->pending()->create();

    actingAs($dataEntry)
        ->post(route('manager.approvals.approve', $approval))
        ->assertForbidden();
});
```

### Unit Test (Service)
```php
// tests/Unit/Services/ApprovalServiceTest.php
use App\Services\ApprovalService;
use App\Models\{Approval, User};

it('marks approval as approved and sets approver', function () {
    $service = new ApprovalService();
    $approval = Approval::factory()->pending()->create();
    $manager = User::factory()->projectManager()->create();

    $service->approve($approval, $manager);

    expect($approval->fresh())
        ->approval_status->toBe('approved')
        ->approved_by->toBe($manager->id)
        ->approved_at->not->toBeNull();
});
```

---

## Factory Conventions
```php
// database/factories/UserFactory.php
class UserFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name'       => fake()->name(),
            'email'      => fake()->unique()->safeEmail(),
            'password'   => bcrypt('password'),
            'role'       => 'data_entry',
            'is_active'  => true,
        ];
    }

    // State methods for roles
    public function admin(): static
    {
        return $this->state(['role' => 'admin']);
    }

    public function projectManager(): static
    {
        return $this->state(['role' => 'project_manager']);
    }

    public function dataEntry(): static
    {
        return $this->state(['role' => 'data_entry']);
    }

    public function disabled(): static
    {
        return $this->state(['is_active' => false]);
    }
}
```

---

## Critical Test Cases (Must Pass Before Deploy)

### Authentication
- [ ] Login with valid credentials → redirects to correct dashboard per role
- [ ] Login with disabled account → blocked with message
- [ ] Access admin route as data_entry → 403
- [ ] Unauthenticated access → redirect to login

### Role Isolation
- [ ] Data entry sees ONLY active projects
- [ ] Data entry cannot see completed/cancelled projects
- [ ] Project manager sees only their own projects
- [ ] Admin sees all projects across all managers

### Approval Workflow
- [ ] Data entry submit → `pending` approval created
- [ ] Project manager submits directly → `approved` automatically
- [ ] Project manager approves → status changes, data becomes visible
- [ ] Project manager rejects → submitter notified (session flash)

### Data Integrity
- [ ] Disabling user → user cannot login → all their submissions remain
- [ ] Deleting project (soft) → related services and submissions preserved
- [ ] File upload → stored correctly → accessible via route

---

## Running Tests
```bash
php artisan test                          # All tests
php artisan test --filter ApprovalTest   # Specific test class
php artisan test --group=critical        # Tagged tests
./vendor/bin/pest --coverage             # With coverage report
```

## Test Database Setup
```php
// tests/TestCase.php
use Illuminate\Foundation\Testing\RefreshDatabase;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase;  // Resets DB between tests
}
```

```ini
; phpunit.xml
<env name="DB_CONNECTION" value="sqlite"/>
<env name="DB_DATABASE" value=":memory:"/>
```
