<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
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
        $announcements = Announcement::latest()->get();
        return view('admin.announcements.index', compact('announcements'));
    }

    /**
     * Show the form for creating a new announcement
     */
    public function create()
    {
        return view('admin.announcements.create');
    }

    /**
     * Store a newly created announcement in database
     */
    public function store(Request $request)
    {
        // Validate the form data
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'content' => 'required|string',
            'cta_text' => 'required|string|max:100',
            'cta_link' => 'required|url',
            'border_color' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'is_published' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // If this announcement is being published, unpublish all others
        if ($request->has('is_published') && $request->is_published == 1) {
            Announcement::where('is_published', 1)->update(['is_published' => 0]);
        }

        // Create the announcement
        $announcement = Announcement::create([
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'content' => $request->content, // This contains the rich text HTML from Quill
            'cta_text' => $request->cta_text,
            'cta_link' => $request->cta_link,
            'border_color' => $request->border_color,
            'is_published' => $request->has('is_published') ? 1 : 0,
        ]);

//        return redirect()->route('announcements')
//            ->with('success', 'Announcement created! 🎉');

        return redirect()->back()->with([
            'message' => "Announcement created! 🎉",
            'type' => 'success'
        ]);
    }

    /**
     * Display the specified announcement
     */
    public function show($id)
    {
        $announcement = Announcement::findOrFail($id);
        return view('admin.announcements.show', compact('announcement'));
    }

    /**
     * Show the form for editing the specified announcement
     */
    public function edit($id)
    {
        $announcement = Announcement::findOrFail($id);
        return view('admin.announcements.edit', compact('announcement'));
    }

    /**
     * Update the specified announcement in database
     */
    public function update(Request $request, $id)
    {
        $announcement = Announcement::findOrFail($id);

        // Validate the form data
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'content' => 'required|string',
            'cta_text' => 'required|string|max:100',
            'cta_link' => 'required|url',
            'border_color' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'is_published' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // If this announcement is being published, unpublish all others
        if ($request->has('is_published') && $request->is_published == 1) {
            Announcement::where('id', '!=', $id)
                ->where('is_published', 1)
                ->update(['is_published' => 0]);
        }

        // Update the announcement
        $announcement->update([
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'content' => $request->content,
            'cta_text' => $request->cta_text,
            'cta_link' => $request->cta_link,
            'border_color' => $request->border_color,
            'is_published' => $request->has('is_published') ? 1 : 0,
        ]);

//        return redirect()->route('announcements')
//            ->with('success', 'Announcement updated! ✨');

        return redirect()->back()->with([
            'message' => "Announcement updated! ✨",
            'type' => 'success'
        ]);
    }

    /**
     * Remove the specified announcement from database
     */
    public function destroy($id)
    {
        $announcement = Announcement::findOrFail($id);
        $announcement->delete();

        return redirect()->route('announcements')
            ->with('success', 'Announcement deleted!');
    }

    /**
     * Toggle publication status
     */
    public function togglePublish($id)
    {
        try {
            $announcement = Announcement::findOrFail($id);

            if ($announcement->is_published) {
                // Unpublish
                $announcement->update(['is_published' => 0]);
                $message = 'Announcement unpublished!';
            } else {
                // Publish this one and unpublish all others
                Announcement::where('is_published', 1)->update(['is_published' => 0]);
                $announcement->update(['is_published' => 1]);
                $message = 'Announcement published! 🎉';
            }

            clearPublishedAnnouncementCache();

            return response()->json([
                'success' => true,
                'message' => $message
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Unpublish All annuoncements
     */

    public function unpublishAll()
    {
        try {
            Announcement::where('is_published', 1)->update(['is_published' => 0]);

            clearPublishedAnnouncementCache();

            return response()->json([
                'success' => true,
                'message' => 'All announcements unpublished'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
}
