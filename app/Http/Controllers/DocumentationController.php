<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;

class DocumentationController extends AbstractController
{
    public function agent($path = 'index.html'): Response
    {
        return $this->doc('doc/user-doc/site/', $path);
    }

    public function admin($path = 'index.html'): Response
    {
        return $this->doc('doc/admin/site/', $path);
    }

    protected function doc($doc_folder, $requested_path): Response
    {
        $base_path = base_path($doc_folder . $requested_path);
        if (is_dir($base_path)) {
            $base_path .= '/index.html';
        }

        // Fetch mime type
        $extension = pathinfo($base_path, PATHINFO_EXTENSION);
        $mime_type = match($extension) {
            'js' => 'text/javascript',
            'css' => 'text/css',
            default => mime_content_type($base_path),
        };

        return response(file_get_contents($base_path))
            ->header('Cache-Control', 'max-age=3600') // cache for one hour
            ->header('Content-Type', $mime_type);
    }
}
