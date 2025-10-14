<div class="p-6">
    <!-- Header -->
    <div class="mb-6 flex items-center justify-between">
        <h2 class="text-2xl font-semibold text-gray-800">Manage User</h2>
        <div class="flex items-center gap-2">
            <button wire:click="save"
                class="rounded-lg bg-blue-600 px-4 py-2 text-sm text-white hover:bg-blue-700">Save</button>
            <button wire:click="resetForm"
                class="rounded-lg bg-gray-200 px-4 py-2 text-sm text-gray-800 hover:bg-gray-300">Reset</button>
        </div>
    </div>

    <!-- Tabs -->
    <div x-data="{ tab: 'profile' }" class="space-y-4">
        <div class="flex border-b border-gray-200">
            <button @click="tab='profile'"
                :class="tab === 'profile' ? 'border-b-2 border-blue-600 text-blue-600' : 'text-gray-500'"
                class="px-4 py-2 font-medium">Profile</button>
            <button @click="tab='roles'"
                :class="tab === 'roles' ? 'border-b-2 border-blue-600 text-blue-600' : 'text-gray-500'"
                class="px-4 py-2 font-medium">Roles</button>
            <button @click="tab='permissions'"
                :class="tab === 'permissions' ? 'border-b-2 border-blue-600 text-blue-600' : 'text-gray-500'"
                class="px-4 py-2 font-medium">Permissions</button>
            <button @click="tab='activity'"
                :class="tab === 'activity' ? 'border-b-2 border-blue-600 text-blue-600' : 'text-gray-500'"
                class="px-4 py-2 font-medium">Activity</button>
        </div>

        <!-- Profile Tab -->
        <div x-show="tab === 'profile'" class="space-y-6">
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div>
                    <label class="mb-1 block text-sm text-gray-600">Name</label>
                    <input type="text" wire:model="user.name"
                        class="w-full rounded-lg border-gray-300 focus:ring focus:ring-blue-100">
                </div>
                <div>
                    <label class="mb-1 block text-sm text-gray-600">Email</label>
                    <input type="email" wire:model="user.email"
                        class="w-full rounded-lg border-gray-300 focus:ring focus:ring-blue-100">
                </div>
                <div>
                    <label class="mb-1 block text-sm text-gray-600">Status</label>
                    <select wire:model="user.status"
                        class="w-full rounded-lg border-gray-300 focus:ring focus:ring-blue-100">
                        <option value="active">Active</option>
                        <option value="suspended">Suspended</option>
                    </select>
                </div>
                <div>
                    <label class="mb-1 block text-sm text-gray-600">Created At</label>
                    <input type="text" disabled value="{{ now()->format('Y-m-d H:i') }}"
                        class="w-full rounded-lg border-gray-200 bg-gray-100">
                </div>
            </div>
        </div>

        <!-- Roles Tab -->
        <div x-show="tab === 'roles'" class="space-y-3">
            <h3 class="font-semibold text-gray-700">Assign Roles</h3>
            <div class="grid grid-cols-2 gap-3 md:grid-cols-3">
                @foreach ($roles as $role)
                    <label class="flex items-center space-x-2">
                        <input type="checkbox" wire:model="userRoles" value="{{ $role->name }}"
                            class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <span>{{ ucfirst($role->name) }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        <!-- Permissions Tab -->
        <div x-show="tab === 'permissions'" class="space-y-3">
            <h3 class="font-semibold text-gray-700">Permissions</h3>
            <div class="grid grid-cols-2 gap-3 md:grid-cols-3">
                @foreach ($permissions as $perm)
                    <label class="flex items-center space-x-2">
                        <input type="checkbox" wire:model="userPermissions" value="{{ $perm->name }}"
                            class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <span>{{ ucfirst($perm->name) }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        <!-- Activity Tab -->
        <div x-show="tab === 'activity'" class="space-y-4">
            <h3 class="font-semibold text-gray-700">Recent Activity</h3>
            <div class="overflow-hidden rounded-lg border">
                <table class="w-full text-sm">
                    <thead class="bg-gray-100 text-gray-700">
                        <tr>
                            <th class="px-4 py-2 text-left">Action</th>
                            <th class="px-4 py-2 text-left">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($activity as $log)
                            <tr class="border-t">
                                <td class="px-4 py-2">{{ $log->description }}</td>
                                <td class="px-4 py-2 text-gray-500">{{ $log->created_at->diffForHumans() }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="py-4 text-center text-gray-400">No recent activity</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
