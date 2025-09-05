<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-6" x-data="{ }">

    {{-- Flash --}}
    @if (session('ok'))
        <div class="mb-4 rounded-xl border p-3">
            <flux:text>{{ session('ok') }}</flux:text>
        </div>
    @endif

    {{-- Toolbar --}}
    <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <flux:heading size="md">Users</flux:heading>
        <div class="flex items-center gap-2">
            <flux:input placeholder="Search users…" wire:model.live="search" />
            <flux:button wire:click="openCreate">Create user</flux:button>
        </div>
    </div>

    {{-- List --}}
    <div class="rounded-xl border overflow-hidden">
        <div class="grid grid-cols-12 px-4 py-2 text-xs font-medium">
            <div class="col-span-4">Name</div>
            <div class="col-span-4">Email</div>
            <div class="col-span-2">Role</div>
            <div class="col-span-2 text-right">Actions</div>
        </div>

        @forelse ($users as $user)
            <div class="grid grid-cols-12 items-center px-4 py-3 border-t">
                <div class="col-span-4">
                    <flux:text class="font-medium">{{ $user->name }}</flux:text>
                    <div class="mt-0.5">
                        @if(strtolower($user->status->name) === 'active')
                            <flux:badge>Active</flux:badge>
                        @else
                            <flux:badge>Blocked</flux:badge>
                        @endif
                    </div>
                </div>
                <div class="col-span-4">
                    <flux:text>{{ $user->email }}</flux:text>
                </div>
                <div class="col-span-2">
                    <flux:text>{{ ucfirst(strtolower($user->role->name)) }}</flux:text>
                </div>
                <div class="col-span-2 flex justify-end gap-2">
                    <flux:button size="sm" variant="subtle" wire:click="openEdit('{{ $user->id }}')">Edit</flux:button>
                    <flux:button size="sm" variant="subtle" wire:click="toggleStatus('{{ $user->id }}')">
                        {{ strtolower($user->status->name) === 'active' ? 'Block' : 'Activate' }}
                    </flux:button>
                </div>
            </div>
        @empty
            <div class="p-6">
                <flux:text>No users found.</flux:text>
            </div>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $users->onEachSide(1)->links() }}
    </div>

    {{-- CREATE MODAL (Tailwind + Alpine shell) --}}
    <div
        x-cloak
        x-show="$wire.showCreate"
        x-transition.opacity
        class="fixed inset-0 z-50 flex items-center justify-center"
        aria-modal="true" role="dialog"
    >
        <div class="absolute inset-0 bg-black/30" @click="$wire.showCreate=false"></div>

        <div class="relative w-full max-w-lg rounded-xl border bg-white dark:bg-zinc-800 p-6 shadow-lg">
            <flux:heading size="md">Create user</flux:heading>

            <div class="mt-4 space-y-4">
                <flux:field>
                    <flux:label for="name">Name</flux:label>
                    <flux:input id="name" wire:model.defer="name" />
                    <flux:error name="name" />
                </flux:field>

                <flux:field>
                    <flux:label for="email">Email</flux:label>
                    <flux:input id="email" type="email" wire:model.defer="email" />
                    <flux:error name="email" />
                </flux:field>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <flux:field>
                        <flux:label for="role">Role</flux:label>
                        <flux:select id="role" wire:model.defer="role">
                            <option value="admin">Admin</option>
                            <option value="standard">Standard</option>
                        </flux:select>
                        <flux:error name="role" />
                    </flux:field>

                    <flux:field>
                        <flux:label for="status">Status</flux:label>
                        <flux:select id="status" wire:model.defer="status">
                            <option value="active">Active</option>
                            <option value="deactivated">Blocked</option>
                        </flux:select>
                        <flux:error name="status" />
                    </flux:field>
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-2">
                <flux:button variant="subtle" @click="$wire.showCreate=false">Cancel</flux:button>
                <flux:button wire:click="saveCreate">Save</flux:button>
            </div>
        </div>
    </div>

    {{-- EDIT MODAL --}}
    <div
        x-cloak
        x-show="$wire.showEdit"
        x-transition.opacity
        class="fixed inset-0 z-50 flex items-center justify-center"
        aria-modal="true" role="dialog"
    >
        <div class="absolute inset-0 bg-black/30" @click="$wire.showEdit=false"></div>

        <div class="relative w-full max-w-lg rounded-xl border bg-white dark:bg-zinc-800 p-6 shadow-lg">
            <flux:heading size="md">Edit user</flux:heading>

            <div class="mt-4 space-y-4">
                <flux:field>
                    <flux:label for="name_e">Name</flux:label>
                    <flux:input id="name_e" wire:model.defer="name" />
                    <flux:error name="name" />
                </flux:field>

                <flux:field>
                    <flux:label for="email_e">Email</flux:label>
                    <flux:input id="email_e" type="email" wire:model.defer="email" />
                    <flux:error name="email" />
                </flux:field>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <flux:field>
                        <flux:label for="role_e">Role</flux:label>
                        <flux:select id="role_e" wire:model.defer="role">
                            <option value="admin">Admin</option>
                            <option value="standard">Standard</option>
                        </flux:select>
                        <flux:error name="role" />
                    </flux:field>

                    <flux:field>
                        <flux:label for="status_e">Status</flux:label>
                        <flux:select id="status_e" wire:model.defer="status">
                            <option value="active">Active</option>
                            <option value="deactivated">Blocked</option>
                        </flux:select>
                        <flux:error name="status" />
                    </flux:field>
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-2">
                <flux:button variant="subtle" @click="$wire.showEdit=false">Cancel</flux:button>
                <flux:button wire:click="saveEdit">Save</flux:button>
            </div>
        </div>
    </div>

</div>
