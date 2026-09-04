<?php

namespace App\Livewire\Auth;

use App\Models\ApplicantProfile;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.guest')]
class RegisterApplicant extends Component
{
    public int $step = 1;

    // Step 1 — Identitas
    public string $name = '';
    public string $nik = '';
    public string $whatsapp = '';
    public string $date_of_birth = '';
    public string $email = '';
    public string $password = '';

    // Step 2 — Profil Kerja
    public string $city = '';
    public string $education_level = 'sma';
    public string $education_institution = '';
    public string $field_of_study = '';
    public string $work_experience = '';
    public array  $skills = [];
    public string $newSkill = '';
    public string $salary_expectation = '';

    public string $nikError = '';
    public string $ageError = '';

    public function nextStep(): void
    {
        if ($this->step === 1) {
            $this->validateStep1();
            if (!$this->nikError && !$this->ageError) {
                $this->step = 2;
            }
        }
    }

    protected function validateStep1(): void
    {
        $this->nikError = '';
        $this->ageError = '';

        $this->validate([
            'name'          => 'required|string|max:255',
            'nik'           => 'required|digits:16',
            'whatsapp'      => 'required|min:10|max:15',
            'date_of_birth' => 'required|date',
            'email'         => 'required|email|unique:users,email',
            'password'      => 'required|min:6',
        ], [
            'name.required'          => 'Nama lengkap wajib diisi.',
            'nik.required'           => 'NIK wajib diisi.',
            'nik.digits'             => 'NIK harus 16 digit.',
            'email.unique'           => 'Email ini sudah digunakan.',
            'password.min'           => 'Kata sandi minimal 6 karakter.',
        ]);

        // Cek NIK unik
        if (User::where('nik', $this->nik)->exists()) {
            $this->nikError = 'NIK ini sudah terdaftar. Silakan masuk menggunakan akun yang sudah ada.';
            return;
        }

        // Cek usia minimal 17 tahun
        $age = \Carbon\Carbon::parse($this->date_of_birth)->age;
        if ($age < 17) {
            $this->ageError = 'Near Job saat ini hanya dapat digunakan oleh pengguna berusia minimal 17 tahun.';
        }
    }

    public function addSkill(): void
    {
        $s = trim($this->newSkill);
        if ($s && !in_array($s, $this->skills)) {
            $this->skills[] = $s;
        }
        $this->newSkill = '';
    }

    public function removeSkill(int $i): void
    {
        unset($this->skills[$i]);
        $this->skills = array_values($this->skills);
    }

    public function register(): void
    {
        try {
            $this->validateStep1();
            if ($this->nikError || $this->ageError) {
                $this->step = 1;
                return;
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->step = 1;
            throw $e;
        }
        
        $this->validate([
            'city'                  => 'required|string',
            'education_level'       => 'required|string',
            'education_institution' => 'required|string|max:255',
        ], [
            'city.required'                  => 'Kota domisili wajib diisi.',
            'education_institution.required' => 'Nama sekolah/universitas wajib diisi.',
        ]);

        $user = User::create([
            'name'          => $this->name,
            'email'         => $this->email,
            'password'      => Hash::make($this->password),
            'role'          => 'applicant',
            'nik'           => $this->nik,
            'whatsapp'      => $this->whatsapp,
            'date_of_birth' => $this->date_of_birth,
        ]);

        ApplicantProfile::create([
            'user_id'               => $user->id,
            'whatsapp'              => $this->whatsapp,
            'education_level'       => $this->education_level,
            'education_institution' => $this->education_institution,
            'field_of_study'        => $this->field_of_study,
            'work_experience'       => $this->work_experience,
            'skills'                => $this->skills,
            'salary_expectation'    => $this->salary_expectation,
            'contact_email'         => $this->email,
            'city'                  => $this->city,
            'is_active'             => true,
            'application_credits'   => 3,
        ]);

        Auth::login($user);
        session()->regenerate();

        $this->redirect(route('applicant.map'), navigate: true);
    }

    public function render()
    {
        return view('livewire.auth.register-applicant', [
            'cities'           => \App\Models\City::orderBy('name')->get(),
            'educationLevels'  => ApplicantProfile::educationLevels(),
        ])->title('Daftar Pencari Kerja — NEAR JOB');
    }
}
