<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DownloadController extends Controller
{
    public function download($filename): StreamedResponse
    {
        // Assuming the files are stored in the 'public' disk
        return Storage::disk('public')->download('ticket-attachments/' . $filename);
    }
}
