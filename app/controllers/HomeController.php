<?php
require_once __DIR__ . '/../models/Course.php';
require_once __DIR__ . '/../models/Slideshows.php';

class HomeController {

    public function index() {
        $slides = Slideshows::all();
        $courses = Course::all();

        include __DIR__ . '/../../resources/views/home.php';
    }
}
