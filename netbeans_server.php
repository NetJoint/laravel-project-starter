<?php

$uri = urldecode(
        parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)
);
$file = __DIR__ . '/public' . $uri;
if ($uri !== '/' && file_exists($file)) {    
    $type = mime_content_type($file);
    if(substr($type,0,4) == 'text'){        
        $ext = pathinfo($file, PATHINFO_EXTENSION);
        $type = 'text/'.$ext;
    }
    header('Content-Type: ' . $type);
    echo file_get_contents($file);
    exit;
} else {
    require_once __DIR__ . '/public/index.php';
}