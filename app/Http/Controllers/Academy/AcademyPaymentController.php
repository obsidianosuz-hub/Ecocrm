<?php

namespace App\Http\Controllers\Academy;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Group;
use App\Models\AcademyPayment;
use App\Models\Transaction;

class AcademyPaymentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'group_id' => 'required|exists:groups,id',
            'amount' => 'required|numeric|min:1',
            'payment_method' => 'required|in:cash,card',
            'month' => 'required|string',
            'year' => 'required|integer',
        ]);

        $student = Student::find($request->student_id);
        $group = Group::with('course')->find($request->group_id);
        $coursePrice = $group->course->price ?? 0;

        $payment = AcademyPayment::create([
            'company_id' => auth()->user()->company_id,
            'student_id' => $request->student_id,
            'group_id' => $request->group_id,
            'cashier_id' => auth()->id(),
            'amount' => $request->amount,
            'payment_method' => $request->payment_method,
            'comment' => $request->comment,
            'month' => $request->month,
            'year' => $request->year,
            'is_full_payment' => ($request->amount >= $coursePrice)
        ]);

        // Update student balance
        $student->balance += $request->amount;
        $student->save();

        // Register as a general transaction (Academy Income)
        Transaction::create([
            'company_id' => auth()->user()->company_id,
            'user_id' => auth()->id(),
            'type' => 'income',
            'amount' => $request->amount,
            'payment_method' => $request->payment_method,
            'description' => "O'quv to'lovi: {$student->name} - {$group->name} ({$request->month}/{$request->year})",
        ]);

        return redirect()->back()->with('success', 'To\'lov muvaffaqiyatli qabul qilindi.');
    }

    public function history($studentId)
    {
        $payments = AcademyPayment::where('student_id', $studentId)
            ->with(['group', 'cashier'])
            ->latest()
            ->get();
        return response()->json($payments);
    }
}
