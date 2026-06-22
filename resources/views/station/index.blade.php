@extends('layouts.app_rest')

@section('content')
<div class="animate__animated p-6" :class="[$store.app.animation]">
    <div>
        <ul class="flex space-x-2 rtl:space-x-reverse">
            <li>
                <a href="{{ action('Auth\LoginController@dashboard') }}" class="text-primary hover:underline">Dashboard</a>
            </li>
            <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                <span>Stations</span>
            </li>
        </ul>

        <div class="grid grid-cols-1 gap-4 pt-5">
            <div class="panel border-[#e0e6ed] px-0 dark:border-[#1b2e4b]">
                <div class="px-5">
                    <div class="flex items-center justify-between mb-5">
                        <h2 class="text-lg font-semibold">Order Ticket Stations</h2>
                        @can('station.create')
                        <button type="button" class="btn btn-primary" onclick="document.getElementById('createStationForm').classList.toggle('hidden')">
                            + Add Station
                        </button>
                        @endcan
                    </div>

                    @if(session('status'))
                        <div class="p-3 mb-4 rounded {{ session('status')['success'] ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            {{ session('status')['msg'] ?? '' }}
                        </div>
                    @endif

                    @can('station.create')
                    <form id="createStationForm" method="POST" action="{{ route('stations.store') }}" class="hidden mb-6 p-4 border rounded">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                            <div>
                                <label class="block text-sm">Name *</label>
                                <input type="text" name="name" class="form-input" required placeholder="Kitchen / Bar / Dessert">
                            </div>
                            <div>
                                <label class="block text-sm">Code</label>
                                <input type="text" name="code" class="form-input" maxlength="16" placeholder="KOT, BOT, DOT (auto if blank)">
                            </div>
                            <div>
                                <label class="block text-sm">Ticket Name</label>
                                <input type="text" name="ticket_name" class="form-input" placeholder="Kitchen Order Ticket">
                            </div>
                            <div>
                                <label class="block text-sm">Display Order</label>
                                <input type="number" name="display_order" class="form-input" value="0">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm">Description</label>
                                <input type="text" name="description" class="form-input">
                            </div>
                            <div class="flex items-center gap-2">
                                <input type="hidden" name="is_active" value="0">
                                <input type="checkbox" name="is_active" value="1" id="is_active_create" checked>
                                <label for="is_active_create">Active</label>
                            </div>
                        </div>
                        <div class="mt-3 flex gap-2">
                            <button type="submit" class="btn btn-primary">Save Station</button>
                            <button type="button" class="btn btn-outline-secondary" onclick="document.getElementById('createStationForm').classList.add('hidden')">Cancel</button>
                        </div>
                    </form>
                    @endcan

                    <div class="overflow-x-auto">
                        <table class="w-full table-auto">
                            <thead>
                                <tr class="border-b">
                                    <th class="text-left p-2">Name</th>
                                    <th class="text-left p-2">Code</th>
                                    <th class="text-left p-2">Ticket Name</th>
                                    <th class="text-left p-2">Slug</th>
                                    <th class="text-left p-2">Order</th>
                                    <th class="text-left p-2">Active</th>
                                    <th class="text-left p-2">Display Screen</th>
                                    <th class="text-left p-2">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($stations as $station)
                                    <tr class="border-b">
                                        <td class="p-2">{{ $station->name }}</td>
                                        <td class="p-2"><code>{{ $station->code }}</code></td>
                                        <td class="p-2">{{ $station->ticket_name }}</td>
                                        <td class="p-2">{{ $station->slug }}</td>
                                        <td class="p-2">{{ $station->display_order }}</td>
                                        <td class="p-2">
                                            <span class="badge {{ $station->is_active ? 'bg-success' : 'bg-secondary' }}">
                                                {{ $station->is_active ? 'Yes' : 'No' }}
                                            </span>
                                        </td>
                                        <td class="p-2">
                                            <a href="{{ route('station-display.index', $station->slug) }}" class="text-primary hover:underline" target="_blank">Open</a>
                                        </td>
                                        <td class="p-2">
                                            @can('station.update')
                                            <button type="button" class="btn btn-sm btn-outline-primary"
                                                onclick="document.getElementById('edit-{{ $station->id }}').classList.toggle('hidden')">
                                                Edit
                                            </button>
                                            @endcan
                                            @can('station.delete')
                                            <form method="POST" action="{{ route('stations.destroy', $station->id) }}" class="inline" onsubmit="return confirm('Delete this station?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                            </form>
                                            @endcan
                                        </td>
                                    </tr>
                                    @can('station.update')
                                    <tr id="edit-{{ $station->id }}" class="hidden bg-gray-50">
                                        <td colspan="8" class="p-3">
                                            <form method="POST" action="{{ route('stations.update', $station->id) }}">
                                                @csrf
                                                @method('PUT')
                                                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                                    <div>
                                                        <label class="block text-sm">Name</label>
                                                        <input type="text" name="name" class="form-input" value="{{ $station->name }}" required>
                                                    </div>
                                                    <div>
                                                        <label class="block text-sm">Code</label>
                                                        <input type="text" name="code" class="form-input" value="{{ $station->code }}" required maxlength="16">
                                                    </div>
                                                    <div>
                                                        <label class="block text-sm">Ticket Name</label>
                                                        <input type="text" name="ticket_name" class="form-input" value="{{ $station->ticket_name }}" required>
                                                    </div>
                                                    <div>
                                                        <label class="block text-sm">Display Order</label>
                                                        <input type="number" name="display_order" class="form-input" value="{{ $station->display_order }}">
                                                    </div>
                                                    <div class="md:col-span-2">
                                                        <label class="block text-sm">Description</label>
                                                        <input type="text" name="description" class="form-input" value="{{ $station->description }}">
                                                    </div>
                                                    <div class="flex items-center gap-2">
                                                        <input type="hidden" name="is_active" value="0">
                                                        <input type="checkbox" name="is_active" value="1" id="is_active_{{ $station->id }}" {{ $station->is_active ? 'checked' : '' }}>
                                                        <label for="is_active_{{ $station->id }}">Active</label>
                                                    </div>
                                                </div>
                                                <div class="mt-3 flex gap-2">
                                                    <button type="submit" class="btn btn-primary">Update</button>
                                                    <button type="button" class="btn btn-outline-secondary" onclick="document.getElementById('edit-{{ $station->id }}').classList.add('hidden')">Cancel</button>
                                                </div>
                                            </form>
                                        </td>
                                    </tr>
                                    @endcan
                                @empty
                                    <tr><td colspan="8" class="p-4 text-center text-gray-500">No stations yet. Create your first one.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
