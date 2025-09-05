<?php

namespace App\Livewire\Admin;

use App\Dto\User as UserDto;
use App\Models\User;
// ACTIVE | DEACTIVATED
// ADMIN | STANDARD
use App\Services\Contracts\UserService;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class UsersIndex extends Component
{
    use WithPagination;

    // Toolbar
    public string $search = '';

    // Modal state
    public bool $showCreate = false;

    public bool $showEdit = false;

    public ?string $editingId = null;

    // Form fields
    public string $name = '';

    public string $email = '';

    public string $role = 'admin';

    public string $status = 'active';

    protected function rules(): array
    {
        $uniqueEmail = Rule::unique('users', 'email');
        if ($this->editingId) {
            $uniqueEmail = $uniqueEmail->ignore($this->editingId);
        }

        return [
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:255', $uniqueEmail],
            'role' => ['required', Rule::in(['admin', 'standard'])],
            'status' => ['required', Rule::in(['active', 'deactivated'])],
        ];
    }

    /** Fake search – UI only */
    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function openCreate(): void
    {
        $this->resetForm();
        $this->showCreate = true;
    }

    public function saveCreate(UserService $userService): void
    {
        $validated = $this->validate();

        $user = $userService->store(UserDto::from($validated));

        $this->showCreate = false;
        session()->flash('ok', "User $user->name has been created");
    }

    public function openEdit(string $userId): void
    {
        $user = User::findOrFail($userId);
        $this->editingId = (string) $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role = strtolower($user->role->name);
        $this->status = strtolower($user->status->name);
        $this->showEdit = true;
    }

    public function saveEdit(UserService $userService): void
    {
        $this->validate();
        $validated = $this->validate();

        $user = $userService->update(
            UserDto::from($validated),
            User::findOrFail($this->editingId)
        );

        $this->showEdit = false;
        session()->flash('ok', "User $user->name has been updated");
    }

    public function toggleStatus(string $userId, UserService $userService): void
    {
        $user = $userService->toggleStatus(User::findOrFail($userId));

        session()->flash('ok', "User $user->name status has been changed");
    }

    public function resetForm(): void
    {
        $this->reset(['editingId', 'name', 'email', 'role', 'status']);
        $this->role = 'admin';
        $this->status = 'active';
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function getUsersProperty()
    {
        // NOTE: search is fake (no filtering), but keeping structure ready
        return User::query()
            ->orderBy('name')
            ->paginate(10);
    }

    public function render()
    {
        return view('livewire.admin.users-index', [
            'users' => $this->users,
        ]);
    }
}
