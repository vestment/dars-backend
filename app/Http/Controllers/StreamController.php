<?php

namespace App\Http\Controllers;

use App\Helpers\VideoStream;
use App\Models\Course;

class StreamController extends Controller
{
    public function stream($course)
    {
        $cours = Course::find($course);
        if ($cours) {

            $filename = $cours->mediaVideo->url;
        }
        else {
            $filename = 'storage/uploads/'.$course;
        }
        $videosDir = public_path();
        if (file_exists($filePath = $videosDir . "/" . $filename)) {
            $stream = new VideoStream($filePath);
            return response()->stream(function () use ($stream) {
                $stream->start();
            });
        }
        return response("File doesn't exists", 404);
    }

    public
    function streamer()
    {
        return 'Hello from Streamer Package!';
    }
}
