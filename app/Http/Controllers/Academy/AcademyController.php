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

        $attendanceStats = Student::forCompany()
            ->with(['attendances' => function($q) {
                $q->where('date', '>=', now()->startOfMonth()->format('Y-m-d'));
            }, 'groups'])
            ->get()
            ->map(function($student) {
                $todayStr = now()->format('Y-m-d');
                $startOfWeek = now()->startOfWeek()->format('Y-m-d');
                $startOfMonth = now()->startOfMonth()->format('Y-m-d');
                
                $todayAtt = $student->attendances->where('date', $todayStr)->first();
                $weeklyPresent = $student->attendances->where('date', '>=', $startOfWeek)
                                                      ->whereIn('status', ['present', 'late'])->count();
                $monthlyPresent = $student->attendances->where('date', '>=', $startOfMonth)
                                                       ->whereIn('status', ['present', 'late'])->count();
                $monthlyAbsent = $student->attendances->where('date', '>=', $startOfMonth)
                                                      ->where('status', 'absent')->count();

                return [
                    'id' => $student->id,
                    'name' => $student->name,
                    'groups' => $student->groups->pluck('name')->implode(', '),
                    'today' => $todayAtt ? $todayAtt->status : 'none',
                    'weekly_present' => $weeklyPresent,
                    'monthly_present' => $monthlyPresent,
                    'monthly_absent' => $monthlyAbsent,
                ];
            });

        return view('dashboards.academy.index', compact('studentsCount', 'groupsCount', 'coursesCount', 'roomsCount', 'recentStudents', 'activeGroups', 'attendanceStats'));
    }
}
