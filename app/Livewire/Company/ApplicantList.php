<?php

namespace App\Livewire\Company;

use App\Models\Application;
use App\Models\JobListing;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class ApplicantList extends Component
{
    public JobListing $job;

    public function mount(JobListing $jobListing)
    {
        if ($jobListing->company_id !== Auth::user()->company->id) {
            abort(403);
        }
        
        $this->job = $jobListing;
    }

    public function updateStatus(int $applicationId, string $status)
    {
        $app = Application::where('id', $applicationId)
            ->where('job_listing_id', $this->job->id)
            ->first();

        if ($app) {
            $app->update(['status' => $status]);
            $this->dispatch('notify', 'Status pelamar berhasil diperbarui.');
        }
    }

    public function render()
    {
        $applications = $this->job->applications()->with(['user.applicantProfile'])->latest()->get();

        return view('livewire.company.applicant-list', [
            'applications' => $applications,
            'statuses' => Application::statuses(),
        ])->title('Pelamar: ' . $this->job->position . ' — NEAR JOB');
    }
}
