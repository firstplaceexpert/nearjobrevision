<?php

namespace App\Livewire\Auth;

use App\Models\Company;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.guest')]
class RegisterCompany extends Component
{
    public string $owner_name = '';
    public string $nik = '';
    public string $whatsapp = '';
    public string $email = '';
    public string $password = '';
    public string $company_name = '';
    public string $business_field = 'fnb';
    public string $nib = '';
    public string $address = '';
    public string $city = '';
    public string $contact_method = 'whatsapp';
    public string $nikError = '';

    public function register(): void
    {
        $this->nikError = '';

        $this->validate([
            'owner_name'    => 'required|string|max:255',
            'nik'           => 'required|digits:16',
            'whatsapp'      => 'required|min:10|max:15',
            'email'         => 'required|email|unique:users,email',
            'password'      => 'required|min:6',
            'company_name'  => 'required|string|max:255',
            'business_field'=> 'required|string',
            'city'          => 'required|string',
            'contact_method'=> 'required|in:whatsapp,email',
        ], [
            'nik.digits'    => 'NIK harus 16 digit.',
            'email.unique'  => 'Email ini sudah digunakan.',
            'city.required' => 'Kota wajib diisi.',
        ]);

        if (User::where('nik', $this->nik)->exists()) {
            $this->nikError = 'NIK ini sudah terdaftar. Silakan masuk menggunakan akun yang sudah ada.';
            return;
        }

        $user = User::create([
            'name'     => $this->owner_name,
            'email'    => $this->email,
            'password' => Hash::make($this->password),
            'role'     => 'company',
            'nik'      => $this->nik,
            'whatsapp' => $this->whatsapp,
        ]);

        Company::create([
            'user_id'        => $user->id,
            'owner_name'     => $this->owner_name,
            'nik'            => $this->nik,
            'whatsapp'       => $this->whatsapp,
            'company_name'   => $this->company_name,
            'business_field' => $this->business_field,
            'nib'            => $this->nib,
            'address'        => $this->address,
            'city'           => $this->city,
            'contact_email'  => $this->email,
            'contact_method' => $this->contact_method,
            'agreed_to_terms'=> true,
        ]);

        Auth::login($user);
        session()->regenerate();
        $this->redirect(route('company.dashboard'), navigate: true);
    }

    public function render()
    {
        return view('livewire.auth.register-company', [
            'cities'     => \App\Models\City::orderBy('name')->get(),
            'categories' => \App\Models\JobListing::jobCategories(),
        ])->title('Daftar Pemberi Kerja — NEAR JOB');
    }
}
