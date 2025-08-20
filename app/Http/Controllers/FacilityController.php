<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Facility;

class FacilityController extends Controller
{
    // Store new facility
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'icon' => 'nullable|string|max:255'
        ]);

        $maxPosition = Facility::max('position') ?? 0;

        Facility::create([
            'name' => $request->name,
            'icon' => $request->icon,
            'position' => $maxPosition + 1,
        ]);

        return notification('Facility added.', 'success');
    }

    // Show edit form
    public function edit($id)
    {
        $facility = Facility::findOrFail($id);
        return view('admin.facilities.edit', compact('facility'));
    }

    // Update facility
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'icon' => 'nullable|string|max:255'
        ]);

        $facility = Facility::findOrFail($id);
        $facility->update($request->only('name', 'icon'));

        return notification('Facility updated.', 'success');
    }

    // Reorder facility
    public function reorder(Request $request)
    {
        try {
            foreach ($request->order as $item) {
                \App\Models\Facility::where('id', $item['id'])
                    ->update(['position' => $item['position']]);
            }

            return response()->json([
                'message' => 'Facilities rearranged',
                'type' => 'success',
                'status' => 'success',
                'reload' => false // No need to reload since it's a dynamic reorder
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to rearrange facilities: ' . $e->getMessage(),
                'type' => 'error',
                'status' => 'error'
            ], 500);
        }
    }


    // Delete facility
    public function destroy($id)
    {
        $facility = Facility::findOrFail($id);

        if ($facility->rooms()->count() > 0) {
        return notification("Facility in use - can't delete.");
        }

        $facility->delete();

        return notification('Facility updated.', 'success');

    }
}
