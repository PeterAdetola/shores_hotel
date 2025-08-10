<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RoomCategory;

class RoomCategoryController extends Controller
{
//    public function index()
//    {
//        $categories = RoomCategory::all();
//        return view('admin.room.manage_rooms', compact('categories'));
//    }

//    public function create()
//    {
//        return view('room-categories.create');
//    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:room_categories,name|max:255',
        ]);

        RoomCategory::create([
            'name' => $request->name,
        ]);

        return redirect_with_notification('Room category added.', 'success');
    }
//
//    public function edit($id)
//    {
//        $category = RoomCategory::findOrFail($id);
//        return view('admin.room.edit_category', compact('category'));
//    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $category = RoomCategory::findOrFail($id);
        $category->name = $request->name;
        $category->save();

        return redirect_with_notification('Category updated.', 'success');
    }

    public function destroy($id)
    {
        $category = RoomCategory::findOrFail($id);

        // Check if the category has associated rooms
        if ($category->rooms()->count() > 0) {
            $notification = array(
                'message' => "Category in use - can't delete.",
                'alert-type' => 'error'
            );

        return redirect_with_notification("Category in use - can't delete.", 'error');

        }

        $category->delete();

        return redirect_with_notification('Category deleted.', 'success');
    }


}
