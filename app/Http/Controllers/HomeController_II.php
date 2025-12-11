<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\File;
use Symfony\Component\Yaml\Yaml;

class HomeController extends Controller
{
    public function index()
    {
        $heroContent  = $this->loadFrontMatter('content/home/hero.md') ?: [];
        $aboutContent1 = $this->loadFrontMatter('content/home/about_1.md') ?: [];
        $aboutContent2 = $this->loadFrontMatter('content/home/about_2.md') ?: [];
        $featuresContent = $this->loadFrontMatter('content/home/features.md') ?: [];

        return view('index.index', compact('heroContent', 'aboutContent1', 'aboutContent2', 'featuresContent'));
    }
    public function about()
    {
        $aboutContent1 = $this->loadFrontMatter('content/home/about_1.md') ?: [];
        $aboutContent2 = $this->loadFrontMatter('content/home/about_2.md') ?: [];
        $featuresContent = $this->loadFrontMatter('content/home/features.md') ?: [];
        $citibarContent = $this->loadFrontMatter('content/home/gallery.md') ?: [];

        return view('about_page', compact( 'aboutContent1', 'aboutContent2', 'featuresContent', 'citibarContent'));
    }
    /**
     * Load a markdown file from storage/app and parse YAML front matter (if present).
     */
    private function loadFrontMatter(string $relativePath): array
    {
        $path = storage_path('app/' . ltrim($relativePath, '/'));

        if (! File::exists($path)) {
            // File not found: return empty array so blades can null-coalesce
            return [];
        }

        $raw = File::get($path) ?? '';

        // Try to extract YAML front matter delimited by ---
        if (preg_match('/^---\s*\R(.*?)\R---\s*\R?/s', $raw, $m)) {
            $frontMatter = Yaml::parse($m[1]) ?? [];
            // Optional body if you ever need it:
            // $frontMatter['body'] = trim(substr($raw, strlen($m[0])));
            return $frontMatter;
        }

        // No front matter: attempt to parse the whole file as YAML
        return Yaml::parse($raw) ?? [];
    }
}




