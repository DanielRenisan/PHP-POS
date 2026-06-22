<?php

namespace App\Http\Controllers;

use App\Models\Station;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class StationController extends Controller
{
    public function index()
    {
        if (!auth()->user()->can('station.view') && !auth()->user()->can('station.create')) {
            abort(403, 'Unauthorized action.');
        }

        $stations = Station::orderBy('display_order')->orderBy('id')->get();

        return view('station.index', compact('stations'));
    }

    public function store(Request $request)
    {
        if (!auth()->user()->can('station.create')) {
            abort(403, 'Unauthorized action.');
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:191'],
            'code' => ['nullable', 'string', 'max:16', 'unique:stations,code'],
            'ticket_name' => ['nullable', 'string', 'max:191'],
            'description' => ['nullable', 'string', 'max:255'],
            'display_order' => ['nullable', 'integer'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        try {
            $code = strtoupper($data['code'] ?? '') ?: Station::generateUniqueCode($data['name']);
            $ticketName = $data['ticket_name'] ?? ($data['name'] . ' Order Ticket');

            Station::create([
                'name' => $data['name'],
                'code' => $code,
                'ticket_name' => $ticketName,
                'slug' => Station::generateSlug($data['name']),
                'description' => $data['description'] ?? null,
                'display_order' => $data['display_order'] ?? 0,
                'is_active' => (bool) ($data['is_active'] ?? true),
                'created_by' => auth()->id(),
            ]);

            $output = ['success' => 1, 'msg' => __('Added Success')];
        } catch (\Throwable $e) {
            Log::emergency('Station store failed: ' . $e->getMessage());
            $output = ['success' => 0, 'msg' => __('Something Went Wrong')];
        }

        return redirect()->route('stations.index')->with('status', $output);
    }

    public function update(Request $request, int $id)
    {
        if (!auth()->user()->can('station.update')) {
            abort(403, 'Unauthorized action.');
        }

        $station = Station::findOrFail($id);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:191'],
            'code' => ['required', 'string', 'max:16', Rule::unique('stations', 'code')->ignore($station->id)],
            'ticket_name' => ['required', 'string', 'max:191'],
            'description' => ['nullable', 'string', 'max:255'],
            'display_order' => ['nullable', 'integer'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        try {
            $station->update([
                'name' => $data['name'],
                'code' => strtoupper($data['code']),
                'ticket_name' => $data['ticket_name'],
                'description' => $data['description'] ?? null,
                'display_order' => $data['display_order'] ?? $station->display_order,
                'is_active' => (bool) ($data['is_active'] ?? false),
            ]);

            $output = ['success' => 1, 'msg' => __('Updated Success')];
        } catch (\Throwable $e) {
            Log::emergency('Station update failed: ' . $e->getMessage());
            $output = ['success' => 0, 'msg' => __('Something Went Wrong')];
        }

        return redirect()->route('stations.index')->with('status', $output);
    }

    public function destroy(int $id)
    {
        if (!auth()->user()->can('station.delete')) {
            abort(403, 'Unauthorized action.');
        }

        try {
            Station::findOrFail($id)->delete();
            $output = ['success' => true, 'msg' => __('Deleted')];
        } catch (\Throwable $e) {
            Log::emergency('Station destroy failed: ' . $e->getMessage());
            $output = ['success' => false, 'msg' => __('Something Went Wrong')];
        }

        return $output;
    }
}
