<?php

namespace App\Livewire\Company;

use App\Models\City;
use App\Models\JobListing;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class JobListingForm extends Component
{
    public ?JobListing $job = null;
    public bool $isEdit = false;

    // Form fields
    public string $position = '';
    public string $job_category = 'lainnya';
    public string $work_type = 'full_time';
    public string $min_education = 'sma';
    public string $city = '';
    public string $salary_min = '';
    public string $salary_max = '';
    public string $work_duration = '';
    public string $work_hours = '';
    public string $description = '';
    public string $qualifications = '';
    
    public array $required_skills = [];
    public string $newSkill = '';

    public string $contact_method = 'whatsapp';
    public string $contact_whatsapp = '';
    public string $contact_email = '';
    public int $quota = 1;
    public string $status = 'active';

    public function mount(?JobListing $jobListing = null)
    {
        if ($jobListing && $jobListing->exists) {
            $this->authorizeJob($jobListing);
            $this->job = $jobListing;
            $this->isEdit = true;
            $this->fillForm();
        } else {
            $company = Auth::user()->company;
            $this->city = $company->city;
            $this->contact_method = $company->contact_method;
            $this->contact_whatsapp = $company->whatsapp;
            $this->contact_email = $company->contact_email;
            $this->quota = 1;
        }
    }

    protected function authorizeJob(JobListing $job)
    {
        if ($job->company_id !== Auth::user()->company->id) {
            abort(403);
        }
    }

    protected function fillForm()
    {
        $this->position = $this->job->position;
        $this->job_category = $this->job->job_category;
        $this->work_type = $this->job->work_type;
        $this->min_education = $this->job->min_education;
        $this->city = $this->job->city;
        $this->salary_min = $this->job->salary_min ?? '';
        $this->salary_max = $this->job->salary_max ?? '';
        $this->work_duration = $this->job->work_duration ?? '';
        $this->work_hours = $this->job->work_hours ?? '';
        $this->description = $this->job->description;
        $this->qualifications = $this->job->qualifications;
        $this->required_skills = $this->job->required_skills ?? [];
        $this->contact_method = $this->job->contact_method;
        $this->contact_whatsapp = $this->job->contact_whatsapp ?? '';
        $this->contact_email = $this->job->contact_email ?? '';
        $this->quota = (int) ($this->job->quota ?? 1);
        $this->status = $this->job->status;
    }

    public function addSkill(): void
    {
        $s = trim($this->newSkill);
        if ($s && !in_array($s, $this->required_skills)) {
            $this->required_skills[] = $s;
        }
        $this->newSkill = '';
    }

    public function removeSkill(int $i): void
    {
        unset($this->required_skills[$i]);
        $this->required_skills = array_values($this->required_skills);
    }

    public function save()
    {
        $this->validate([
            'position' => 'required|string|max:255',
            'job_category' => 'required|string',
            'work_type' => 'required|string',
            'min_education' => 'required|string',
            'city' => 'required|string',
            'description' => 'required|string',
            'qualifications' => 'required|string',
            'contact_method' => 'required|in:whatsapp,email',
            'quota' => 'required|integer|min:1|max:999',
            'status' => 'required|in:active,filled,closed',
        ]);

        $data = [
            'position' => $this->position,
            'job_category' => $this->job_category,
            'work_type' => $this->work_type,
            'min_education' => $this->min_education,
            'city' => $this->city,
            'salary_min' => $this->salary_min ?: null,
            'salary_max' => $this->salary_max ?: null,
            'work_duration' => $this->work_duration,
            'work_hours' => $this->work_hours,
            'description' => $this->description,
            'qualifications' => $this->qualifications,
            'required_skills' => $this->required_skills,
            'contact_method' => $this->contact_method,
            'contact_whatsapp' => $this->contact_whatsapp,
            'contact_email' => $this->contact_email,
            'quota' => (int) $this->quota,
            'status' => $this->status,
        ];

        // Temukan koordinat kota secara sederhana
        $cityModel = City::where('name', $this->city)->first();
        if ($cityModel) {
            $data['latitude'] = $cityModel->latitude;
            $data['longitude'] = $cityModel->longitude;
        } else {
            // Default ke Banyuwangi jika tidak ketemu
            $data['latitude'] = -8.2192;
            $data['longitude'] = 114.3692;
        }

        if ($this->isEdit) {
            $this->job->update($data);
            $msg = 'Lowongan berhasil diperbarui!';
        } else {
            $data['company_id'] = Auth::user()->company->id;
            $this->job = JobListing::create($data);
            $msg = 'Lowongan berhasil diposting!';
        }

        session()->flash('notify', $msg);
        return $this->redirect(route('company.jobs'), navigate: true);
    }

    public function render()
    {
        return view('livewire.company.job-listing-form', [
            'cities' => City::orderBy('name')->get(),
            'categories' => JobListing::jobCategories(),
            'workTypes' => JobListing::workTypes(),
            'educationLevels' => \App\Models\ApplicantProfile::educationLevels(),
        ])->title(($this->isEdit ? 'Edit' : 'Buat') . ' Lowongan — NEAR JOB');
    }
}
