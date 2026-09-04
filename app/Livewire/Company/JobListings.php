<?php

namespace App\Livewire\Company;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class JobListings extends Component
{
    public function render()
    {
        $company = Auth::user()->company;
        $jobs = $company->jobListings()->withCount('applications')->latest()->get();

        return view('livewire.company.job-listings', [
            'jobs' => $jobs,
        ])->title('Lowongan Saya — NEAR JOB');
    }
}
