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

    public function create()
    {
        return view('room-categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:room_categories,name|max:255',
        ]);

        RoomCategory::create([
            'name' => $request->name,
        ]);

        $notification = array(
            'message' => 'Room category added',
        );

        return redirect()->back()->with($notification);
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

        $notification = array(
            'message' => 'Category updated',
        );

        return redirect()->back()->with($notification);
    }

    public function destroy($id)
    {
        $category = RoomCategory::findOrFail($id);

        // Check if the category has associated rooms
//        if ($category->rooms()->count() > 0) {
//            $notification = array(
//                'message' => "Category in use - can't delete.",
//                'alert-type' => 'error'
//            );
//
//            return redirect()->back()->with($notification);
//        }

        $category->delete();

        $notification = array(
            'message' => 'Category deleted.',
        );

        return redirect()->back()->with($notification);
    }


}
