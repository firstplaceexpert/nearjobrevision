<?php

namespace App\Livewire\Company;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class Dashboard extends Component
{
    public function render()
    {
        $company = Auth::user()->company;
        $jobs = $company->jobListings()->withCount('applications')->latest()->take(3)->get();
        
        $totalJobs = $company->jobListings()->count();
        $totalApplicants = $company->jobListings()->withCount('applications')->get()->sum('applications_count');

        return view('livewire.company.dashboard', [
            'company' => $company,
            'recentJobs' => $jobs,
            'totalJobs' => $totalJobs,
            'totalApplicants' => $totalApplicants,
        ])->title('Dashboard Pemberi Kerja — NEAR JOB');
    }
}
