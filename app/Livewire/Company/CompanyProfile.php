<?php

namespace App\Livewire\Company;

use App\Models\City;
use App\Models\JobListing;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class CompanyProfile extends Component
{
    public $company;

    public string $owner_name = '';
    public string $whatsapp = '';
    public string $company_name = '';
    public string $business_field = '';
    public string $nib = '';
    public string $address = '';
    public string $city = '';
    public string $contact_method = '';

    public function mount()
    {
        $this->company = Auth::user()->company;
        
        $this->owner_name = $this->company->owner_name ?? '';
        $this->whatsapp = $this->company->whatsapp ?? '';
        $this->company_name = $this->company->company_name ?? '';
        $this->business_field = $this->company->business_field ?? '';
        $this->nib = $this->company->nib ?? '';
        $this->address = $this->company->address ?? '';
        $this->city = $this->company->city ?? '';
        $this->contact_method = $this->company->contact_method ?? 'whatsapp';
    }

    public function save()
    {
        $this->validate([
            'owner_name'    => 'required|string|max:255',
            'whatsapp'      => 'required|string',
            'company_name'  => 'required|string|max:255',
            'business_field'=> 'required|string',
            'city'          => 'required|string',
            'contact_method'=> 'required|in:whatsapp,email',
        ]);

        $this->company->update([
            'owner_name'     => $this->owner_name,
            'whatsapp'       => $this->whatsapp,
            'company_name'   => $this->company_name,
            'business_field' => $this->business_field,
            'nib'            => $this->nib,
            'address'        => $this->address,
            'city'           => $this->city,
            'contact_method' => $this->contact_method,
        ]);

        $this->company->user->update([
            'name' => $this->owner_name,
            'whatsapp' => $this->whatsapp,
        ]);

        $this->dispatch('notify', ['message' => 'Profil usaha berhasil diperbarui!', 'type' => 'success']);
    }

    public function render()
    {
        return view('livewire.company.company-profile', [
            'cities'     => City::orderBy('name')->get(),
            'categories' => JobListing::jobCategories(),
        ])->title('Profil Usaha — NEAR JOB');
    }
}
