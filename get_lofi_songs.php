<?php
header(header: 'Content-Type: application/json');

$lofiDir = 'src/lofi/';
$songs = [];

// Check if directory exists
if (is_dir(filename: $lofiDir)) {
    // Get all files in the lofi directory
    $files = scandir(directory: $lofiDir);
    
    foreach ($files as $file) {
        // Only include MP3 files and exclude . and .. directories
        if ($file !== '.' && $file !== '..' && pathinfo(path: $file, flags: PATHINFO_EXTENSION) === 'mp3') {
            $songs[] = $file;
        }
    }
}

// Return JSON response
echo json_encode(value: $songs);
?>