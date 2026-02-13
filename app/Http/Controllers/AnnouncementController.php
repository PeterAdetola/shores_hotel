<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AnnouncementController extends Controller
{
    /**
     * Display a listing of announcements
     */
    public function index()
    {
        $announcements = Announcement::orderBy('order')->orderBy('created_at', 'desc')->get();
        return view('admin.announcements.announcemnt_index', compact('announcements'));
    }

    /**
     * Show the form for creating a new announcement
     */
    public function create()
    {
        return view('admin.announcements.create');
    }

    /**
     * Store a newly created announcement
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string',
            'content' => 'required|string',
            'discount_weekday' => 'nullable|string',
            'discount_weekend' => 'nullable|string',
            'features' => 'nullable|string',
            'cta_text' => 'required|string|max:255',
            'cta_link' => 'required|string|max:255',
            'border_color' => 'nullable|string|max:50',
            'primary_emoji' => 'nullable|string|max:10',
            'is_published' => 'boolean',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->all();

        // Convert features string to array if provided
        if ($request->has('features') && !empty($request->features)) {
            $data['features'] = array_filter(array_map('trim', explode("\n", $request->features)));
        }

        Announcement::create($data);

        return redirect()->route('announcements.announcemnt_index')
            ->with('success', 'Announcement created successfully!');
    }

    /**
     * Show the form for editing an announcement
     */
    public function edit($id)
    {
        $announcement = Announcement::findOrFail($id);
        return view('admin.announcements.edit', compact('announcement'));
    }

    /**
     * Update the specified announcement
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:announcements,id',
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string',
            'content' => 'required|string',
            'discount_weekday' => 'nullable|string',
            'discount_weekend' => 'nullable|string',
            'features' => 'nullable|string',
            'cta_text' => 'required|string|max:255',
            'cta_link' => 'required|string|max:255',
            'border_color' => 'nullable|string|max:50',
            'primary_emoji' => 'nullable|string|max:10',
            'is_published' => 'boolean',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $announcement = Announcement::findOrFail($request->id);

        $data = $request->except('id');

        // Convert features string to array if provided
        if ($request->has('features') && !empty($request->features)) {
            $data['features'] = array_filter(array_map('trim', explode("\n", $request->features)));
        }

        $announcement->update($data);

        return redirect()->route('announcements.announcemnt_index')
            ->with('success', 'Announcement updated successfully!');
    }

    /**
     * Toggle publish status
     */
    public function togglePublish($id)
    {
        $announcement = Announcement::findOrFail($id);

        if ($announcement->is_published) {
            // Unpublish this announcement
            $announcement->update(['is_published' => false]);
            $message = 'Announcement unpublished successfully!';
        } else {
            // Publish this announcement (will auto-unpublish others via boot method)
            $announcement->update(['is_published' => true]);
            $message = 'Announcement published successfully!';
        }

        return redirect()->back()->with('success', $message);
    }

    /**
     * Unpublish all announcements
     */
    public function unpublishAll()
    {
        Announcement::where('is_published', true)->update(['is_published' => false]);

        return redirect()->back()->with('success', 'All announcements unpublished successfully!');
    }

    /**
     * Update order of announcements
     */
    public function updateOrder(Request $request)
    {
        $order = $request->input('order', []);

        foreach ($order as $index => $id) {
            Announcement::where('id', $id)->update(['order' => $index]);
        }

        return response()->json(['success' => true]);
    }

    /**
     * Remove the specified announcement
     */
    public function destroy($id)
    {
        $announcement = Announcement::findOrFail($id);
        $announcement->delete();

        return redirect()->route('announcements.announcemnt_index')
            ->with('success', 'Announcement deleted successfully!');
    }

    /**
     * Get active announcement for frontend display
     */
    public function getActive()
    {
        $announcement = Announcement::active()->first();
        return $announcement;
    }
}
