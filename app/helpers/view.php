<?php

function view($name, $data = []) {
    extract($data);

    $path = __DIR__ . '/../../resources/views/' . $name . '.php';

    if (!file_exists($path)) {
        die("View file not found: " . $path);
    }

    include $path;
}
