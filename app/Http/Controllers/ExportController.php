<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\AcademyPayment;
use App\Models\Student;
use App\Models\Client;
use Barryvdh\DomPDF\Facade\Pdf;

class ExportController extends Controller
{
    public function dailyReport(Request $request)
    {
        $date = $request->get('date', now()->format('Y-m-d'));
        
        $transactions = Transaction::forCompany()
            ->whereDate('created_at', $date)
            ->get();
            
        $academyPayments = AcademyPayment::forCompany()
            ->whereDate('created_at', $date)
            ->with(['student', 'group'])
            ->get();

        $summary = [
            'total_income' => $transactions->where('type', 'income')->sum('amount'),
            'total_expense' => $transactions->where('type', 'expense')->sum('amount'),
            'cash_on_hand' => $transactions->where('payment_method', 'cash')->where('type', 'income')->sum('amount') - 
                             $transactions->where('payment_method', 'cash')->where('type', 'expense')->sum('amount'),
            'card_on_hand' => $transactions->where('payment_method', 'card')->where('type', 'income')->sum('amount') - 
                             $transactions->where('payment_method', 'card')->where('type', 'expense')->sum('amount'),
        ];

        $viewData = compact('transactions', 'academyPayments', 'summary', 'date');

        // Note: Using PDF facade requires the package to be installed.
        // If the package installation fails, we might need to fallback to a basic HTML-to-Print view.
        if (class_exists(\Barryvdh\DomPDF\Facade\Pdf::class)) {
            $pdf = Pdf::loadView('reports.daily', $viewData);
            return $pdf->download("daily_report_{$date}.pdf");
        }

        return view('reports.daily', $viewData);
    }

    public function exportClients()
    {
        $clients = Client::forCompany()->get();
        if (class_exists(\Barryvdh\DomPDF\Facade\Pdf::class)) {
            $pdf = Pdf::loadView('reports.clients', compact('clients'));
            return $pdf->download("clients_list.pdf");
        }
        return view('reports.clients', compact('clients'));
    }

    public function exportTransactions()
    {
        $transactions = Transaction::forCompany()->latest()->take(100)->get();
        if (class_exists(\Barryvdh\DomPDF\Facade\Pdf::class)) {
            $pdf = Pdf::loadView('reports.transactions', compact('transactions'));
            return $pdf->download("transactions_fcc.pdf");
        }
        return view('reports.transactions', compact('transactions'));
    }
}
