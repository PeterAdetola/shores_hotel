<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\File;
use Symfony\Component\Yaml\Yaml;

class CitibarController extends Controller
{
    public function index()
    {
        $citibarContent = $this->loadFrontMatter('content/citibar/citibar.md') ?: [];

        return view('citibar', compact('citibarContent'));
    }

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
