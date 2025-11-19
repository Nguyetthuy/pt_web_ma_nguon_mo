<?php

require_once __DIR__ . '/../models/Course.php';
require_once __DIR__ . '/../helpers/view.php';

class CourseController
{
    // ============================
    // HIỂN THỊ DANH SÁCH KHÓA HỌC
    // ============================
    public function index()
    {
        $courses = Course::all();

        view('courses/index', [
            'courses' => $courses
        ]);
    }

    // ============================
    // HIỂN THỊ CHI TIẾT 1 KHÓA HỌC
    // ============================
    public function detail()
    {
        if (!isset($_GET['id'])) {
            die("Thiếu ID khóa học");
        }

        $id = $_GET['id'];

        $course = Course::find($id);

        if (!$course) {
            die("Không tìm thấy khóa học");
        }

        view('courses/detail', [
            'course' => $course
        ]);
    }
}
