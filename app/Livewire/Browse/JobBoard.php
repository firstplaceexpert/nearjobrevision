<?php

namespace App\Livewire\Browse;

use App\Models\City;
use App\Models\JobListing;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.guest')]
class JobBoard extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';

    #[Url]
    public string $city = '';

    #[Url]
    public string $work_type = '';

    #[Url]
    public string $category = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = JobListing::active()->with('company')->latest();

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('position', 'LIKE', "%{$this->search}%")
                  ->orWhereHas('company', function ($cq) {
                      $cq->where('company_name', 'LIKE', "%{$this->search}%");
                  });
            });
        }

        if ($this->city) {
            $query->where('city', $this->city);
        }

        if ($this->work_type) {
            $query->where('work_type', $this->work_type);
        }

        if ($this->category) {
            $query->where('job_category', $this->category);
        }

        return view('livewire.browse.job-board', [
            'jobs' => $query->paginate(12),
            'cities' => City::orderBy('name')->get(),
            'workTypes' => JobListing::workTypes(),
            'jobCategories' => JobListing::jobCategories(),
        ])->title('Lowongan Kerja — NearJob');
    }
}
