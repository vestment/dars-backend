<?php

namespace App\Http\Controllers;

use App\Helpers\VideoStream;
use App\Models\Course;
use App\Models\Media;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;
class StreamController extends Controller
{
     public function stream($encryptedId)
    {
      $mediaId = Crypt::decryptString($encryptedId);
          $media = Media::find($mediaId);
     
         $filename = $media->url;
        // dd(url());
        $videosDir = public_path();
        if (file_exists($filePath = $videosDir . "/" . $filename)) {
            $stream = new VideoStream($filePath);
            return response()->stream(function () use ($stream) {
                $stream->start();
            });
        }
        return response("File doesn't exists", 404);
    }

    public function streamer()
    {
        return 'Hello from Streamer Package!';
    }
}
