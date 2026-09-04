<?php

namespace App\Livewire\Applicant;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class ApplicationHistory extends Component
{
    public function render()
    {
        $applications = Auth::user()->applications()->with(['jobListing.company'])->latest('application_date')->get();

        return view('livewire.applicant.application-history', [
            'applications' => $applications,
        ])->title('Riwayat Lamaran — NEAR JOB');
    }
}
