<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\File;
use Symfony\Component\Yaml\Yaml;

class ContactController extends Controller
{
    public function index()
    {
        $contactContent = $this->loadFrontMatter('content/contact/contact.md') ?: [];

        return view('contact', compact('contactContent'));
    }

    public function hotel()
    {
        $contactContent = $this->loadFrontMatter('content/contact/contact.md') ?: [];
        $hotelInfo = $contactContent['hotel_info'] ?? [];

        return view('getRooms', ['hotelInfo' => $hotelInfo]);
    }

    public function apartment()
    {
        $contactContent = $this->loadFrontMatter('content/contact/contact.md') ?: [];
        $apartmentInfo = $contactContent['apartment_info'] ?? [];

        return view('getApartments', ['apartmentInfo' => $apartmentInfo]);
    }



    /**
     * Safe loader for markdown front matter.
     */
    private function loadFrontMatter(string $relativePath): array
    {
        $path = storage_path('app/' . ltrim($relativePath, '/'));

        if (! File::exists($path)) {
            return [];
        }

        $raw = File::get($path) ?? '';

        if (preg_match('/^---\s*\R(.*?)\R---\s*\R?/s', $raw, $m)) {
            return Yaml::parse($m[1]) ?? [];
        }

        return Yaml::parse($raw) ?? [];
    }
}
