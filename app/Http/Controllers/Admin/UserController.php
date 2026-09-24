<?php

// app/Http/Controllers/Admin/UserController.php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UserRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        $this->authorize('viewAny', User::class);
        $users = User::latest()->paginate(10);
        
        return view('admin.users.index', compact('users'));
    }

    public function create(): View
    {
        $this->authorize('create', User::class);
        return view('admin.users.create');
    }

    public function store(UserRequest $request): RedirectResponse
    {
        $this->authorize('create', User::class);
        
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);
        
        User::create($data);

        return redirect()->route('admin.users.index')
            ->with('success', 'تم إضافة المستخدم بنجاح');
    }

    public function edit(User $user): View
    {
        $this->authorize('update', $user);
        return view('admin.users.edit', compact('user'));
    }

    public function update(UserRequest $request, User $user): RedirectResponse
    {
        $this->authorize('update', $user);
        
        $data = $request->validated();
        
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')
            ->with('success', 'تم تحديث بيانات المستخدم بنجاح');
    }

    public function destroy(User $user): RedirectResponse
    {
        $this->authorize('delete', $user);
        
        if ($user->id === auth()->id()) {
            return back()->with('error', 'لا يمكنك حذف حسابك الشخصي');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'تم حذف المستخدم (نقل للمحذوفات)');
    }

    public function toggleActive(User $user): RedirectResponse
    {
        $this->authorize('update', $user);
        
        if ($user->id === auth()->id()) {
            return back()->with('error', 'لا يمكنك تعطيل حسابك الشخصي');
        }

        $user->update(['is_active' => !$user->is_active]);
        
        $status = $user->is_active ? 'تفعيل' : 'تعطيل';
        return back()->with('success', "تم {$status} الحساب بنجاح");
    }
}
