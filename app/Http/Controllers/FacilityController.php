<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RoomFacility;

class FacilityController extends Controller
{
    // Store new facility
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'icon' => 'nullable|string|max:255'
        ]);

        RoomFacility::create($request->only('name', 'icon'));

        $notification = array(
            'message' => 'Facility added.',
        );

        return redirect()->back()->with($notification);
    }

    // Show edit form
    public function edit($id)
    {
        $facility = RoomFacility::findOrFail($id);
        return view('admin.facilities.edit', compact('facility'));
    }

    // Update facility
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'icon' => 'nullable|string|max:255'
        ]);

        $facility = RoomFacility::findOrFail($id);
        $facility->update($request->only('name', 'icon'));

        $notification = array(
            'message' => 'Facility updated.',
        );

        return redirect()->back()->with($notification);
    }

    // Reorder facility
    public function reorder(Request $request)
    {
        foreach ($request->order as $item) {
            \App\Models\RoomFacility::where('id', $item['id'])
                ->update(['position' => $item['position']]);
        }

        return response()->json(['status' => 'success']);
    }


    // Delete facility
    public function destroy($id)
    {
        $facility = RoomFacility::findOrFail($id);
        $facility->delete();

        $notification = array(
            'message' => 'Facility deleted.',
        );

        return redirect()->back()->with($notification);
    }
}
