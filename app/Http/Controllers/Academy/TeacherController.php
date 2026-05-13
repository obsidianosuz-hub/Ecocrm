<?php

namespace App\Http\Controllers\Academy;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Group;

class TeacherController extends Controller
{
    public function myStudents()
    {
        $teacher = auth()->user();
        $groups = Group::forCompany()
            ->where('teacher_id', $teacher->id)
            ->with(['students', 'course'])
            ->get();
            
        return view('dashboards.teacher_students', compact('groups'));
    }

    public function storeGrade(Request $request)
    {
        $request->validate([
            'group_id' => 'required|exists:groups,id',
            'student_id' => 'required|exists:students,id',
            'grade' => 'required|integer',
            'knowledge_level' => 'nullable|string',
            'comment' => 'nullable|string',
        ]);

        \App\Models\Grade::create([
            'company_id' => auth()->user()->company_id,
            'teacher_id' => auth()->id(),
            'group_id' => $request->group_id,
            'student_id' => $request->student_id,
            'grade' => $request->grade,
            'knowledge_level' => $request->knowledge_level,
            'comment' => $request->comment,
            'date' => now()->toDateString(),
        ]);

        return response()->json(['success' => true, 'message' => 'Baho muvaffaqiyatli kiritildi!']);
    }

    public function storeTopic(Request $request)
    {
        $request->validate([
            'group_id' => 'required|exists:groups,id',
            'topic' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        \App\Models\LessonTopic::create([
            'company_id' => auth()->user()->company_id,
            'teacher_id' => auth()->id(),
            'group_id' => $request->group_id,
            'topic' => $request->topic,
            'description' => $request->description,
            'date' => now()->toDateString(),
        ]);

        return response()->json(['success' => true, 'message' => 'Mavzu muvaffaqiyatli saqlandi!']);
    }
}
