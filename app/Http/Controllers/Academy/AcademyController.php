<?php

namespace App\Http\Controllers\Academy;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Group;
use App\Models\Course;
use App\Models\Room;
use App\Models\Schedule;

class AcademyController extends Controller
{
    public function index()
    {
        if (!in_array(auth()->user()->role, ['admin', 'cashier'])) {
            abort(403, 'Sizda bu bo\'limga kirish huquqi yo\'q!');
        }
        $studentsCount = Student::forCompany()->count();
        $groupsCount = Group::forCompany()->count();
        $coursesCount = Course::forCompany()->count();
        $roomsCount = Room::forCompany()->count();

        $recentStudents = Student::forCompany()->latest()->take(5)->get();
        $activeGroups = Group::forCompany()->with('course', 'room', 'teacher')->latest()->take(5)->get();

        return view('dashboards.academy.index', compact('studentsCount', 'groupsCount', 'coursesCount', 'roomsCount', 'recentStudents', 'activeGroups'));
    }
}
