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

        return redirect_with_notification('Facility added.');
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

        return redirect_with_notification('Facility updated.');
    }

    // Reorder facility
    public function reorder(Request $request)
    {
        foreach ($request->order as $item) {
            \App\Models\Facility::where('id', $item['id'])
                ->update(['position' => $item['position']]);
        }

        return response()->json(['status' => 'success']);
    }


    // Delete facility
    public function destroy($id)
    {
        $facility = Facility::findOrFail($id);

//        if ($facility->rooms()->count() > 0) {

//        return redirect_with_notification("Facility in use - can't delete.");
//        }

        $facility->delete();

        return redirect_with_notification('Facility updated.');

    }
}
