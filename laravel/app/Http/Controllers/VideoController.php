<?php
namespace App\Http\Controllers;

use App\Models\Experiment;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;
class VideoController extends Controller
{
   
    public function segment(int $experiment, string $file)
{
    $path = "experiments/{$experiment}/{$file}";

    if (!Storage::disk('s3')->exists($path)) {
        abort(404);
    }

    return new StreamedResponse(function () use ($path) {
        $stream = Storage::disk('s3')->readStream($path);
        fpassthru($stream);
        fclose($stream);
    }, 200, [
        'Content-Type'              => 'video/mp2t',
        'Cache-Control'             => 'no-store',
        'Access-Control-Allow-Origin'      => config('app.url'),
        'Access-Control-Allow-Credentials' => 'true',
    ]);
}


}
