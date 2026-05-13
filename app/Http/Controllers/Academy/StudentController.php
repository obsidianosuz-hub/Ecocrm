<?php

namespace App\Http\Controllers\Academy;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Student;
use App\Models\Group;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $query = Student::forCompany();
        
        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('phone', 'like', '%' . $request->search . '%');
        }

        $students = $query->latest()->paginate(15);
        $groups = Group::forCompany()->get();

        return view('academy.students.index', compact('students', 'groups'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'address' => 'nullable|string',
        ]);

        $student = Student::create([
            'company_id' => auth()->user()->company_id,
            'name' => $request->name,
            'phone' => $request->phone,
            'address' => $request->address,
            'status' => 'active',
        ]);

        if ($request->has('group_id')) {
            $student->groups()->attach($request->group_id);
        }

        return redirect()->back()->with('success', 'O\'quvchi muvaffaqiyatli qo\'shildi!');
    }

    public function update(Request $request, Student $student)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
        ]);

        $student->update($request->only('name', 'phone', 'address', 'status'));

        return redirect()->back()->with('success', 'O\'quvchi ma\'lumotlari yangilandi.');
    }

    public function destroy(Student $student)
    {
        $student->delete();
        return redirect()->back()->with('success', 'O\'quvchi tizimdan o\'chirildi.');
    }

    public function search(Request $request)
    {
        $search = $request->get('q');
        $students = Student::forCompany()
            ->where('name', 'like', "%$search%")
            ->orWhere('phone', 'like', "%$search%")
            ->limit(10)
            ->get();

        return response()->json($students);
    }

    public function addToGroup(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'group_id' => 'required|exists:groups,id',
        ]);

        $student = Student::find($request->student_id);
        $student->groups()->syncWithoutDetaching([$request->group_id]);

        return response()->json(['success' => true, 'message' => 'O\'quvchi guruhga qo\'shildi.']);
    }
}
