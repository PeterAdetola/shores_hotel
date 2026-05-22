<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class LlmsFullController extends Controller
{
    public function index(): Response
    {
        // 1. Get ALL files recursively inside storage/app/content
        if (!Storage::exists('content')) {
            return response("# Content directory missing\n", 404)
                ->header('Content-Type', 'text/plain');
        }

        $allFiles = Storage::allFiles('content');

        // 2. Filter out only the markdown (.md) files
        $mdFiles = array_filter($allFiles, function ($path) {
            return pathinfo($path, PATHINFO_EXTENSION) === 'md';
        });

        // 3. Document Header for the LLM
        $output = "# Full System Context\n";
        $output .= "> Continuous text document compiled dynamically from modular content streams.\n\n---\n\n";

        // 4. Loop through the nested layout
        foreach ($mdFiles as $filePath) {
            // Get the directory group (e.g., "home", "citibar", "contact")
            $directoryGroup = basename(dirname($filePath));
            $filename = pathinfo($filePath, PATHINFO_FILENAME);

            // Clean names up nicely for the header section
            $cleanGroup = Str::of($directoryGroup)->title();
            $cleanFile = Str::of($filename)->replace(['_', '-'], ' ')->title();

            // Read the file contents safely using Laravel Storage
            $content = Storage::get($filePath);

            // Append with a clear, readable nested structure
            $output .= "## Section: {$cleanGroup} - {$cleanFile}\n";
            $output .= "\n\n";
            $output .= trim($content) . "\n\n";
            $output .= "---\n\n";
        }

        // 5. Send back the raw text response
        return response($output, 200)
            ->header('Content-Type', 'text/plain; charset=UTF-8');
    }
}
