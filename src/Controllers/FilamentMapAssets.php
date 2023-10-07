<?php

namespace Humaidem\FilamentMaps\Controllers;

use Illuminate\Support\Facades\Response;

class FilamentMapAssets
{
    public function __invoke($file)
    {
        if (pathinfo($file, PATHINFO_EXTENSION) === 'png') {
            if (in_array($file, ['marker-icon.png', 'marker-shadow.png'])) {
                return Response::streamDownload(
                    function () use ($file) {
                        echo file_get_contents(__DIR__ . '/../../images/' . $file);
                    },
                    $file,
                    [
                        'Content-Type' => 'image/png',
                    ]
                );
            }
        }
        abort(404);
    }
}
