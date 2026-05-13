<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $pendingCompanies = \App\Models\Company::where('status', 'pending')->with('users')->get();
        $activeCompanies = \App\Models\Company::where('status', 'active')->withCount('users')->get();
        
        return view('dashboards.master', compact('pendingCompanies', 'activeCompanies'));
    }

    public function approveCompany($id)
    {
        $company = \App\Models\Company::findOrFail($id);
        $company->update(['status' => 'active']);
        
        // Approve the admin user associated with this company
        \App\Models\User::where('company_id', $company->id)
            ->where('role', 'admin')
            ->update(['approval_status' => 'approved']);
            
        return back()->with('success', 'Kompaniya va uning administratori muvaffaqiyatli tasdiqlandi!');
    }

    public function suspendCompany($id)
    {
        $company = \App\Models\Company::findOrFail($id);
        $company->update(['status' => 'suspended']);
        
        \App\Models\User::where('company_id', $company->id)
            ->update(['status' => 'blocked']);
            
        return back()->with('success', 'Kompaniya faoliyati vaqtinchalik to\'xtatildi!');
    }

    public function destroyCompany($id)
    {
        $company = \App\Models\Company::findOrFail($id);
        
        \App\Models\User::where('company_id', $company->id)->delete();
        $company->delete();
            
        return back()->with('success', 'Kompaniya butunlay o\'chirildi!');
    }
}
