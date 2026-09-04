<?php

namespace App\Livewire\Applicant;

use App\Models\ApplicantProfile;
use App\Models\City;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('components.layouts.app')]
class ProfileForm extends Component
{
    use WithFileUploads;

    public ApplicantProfile $profile;

    // Form fields
    public string $name = '';
    public string $whatsapp = '';
    public string $email = '';
    public string $city = '';
    public string $education_level = '';
    public string $education_institution = '';
    public string $field_of_study = '';
    public string $work_experience = '';
    public string $salary_expectation = '';
    public string $profile_picture = '';
    public string $cover_picture = '';
    
    // File upload properties (dari Galeri / HP / Komputer)
    public $avatarFile = null;
    public $coverFile = null;

    public array $skills = [];
    public string $newSkill = '';

    public function mount()
    {
        $user = Auth::user();
        $this->profile = $user->applicantProfile ?? new ApplicantProfile();

        $this->name = $user->name;
        $this->email = $user->email;
        $this->whatsapp = $this->profile->whatsapp ?? $user->whatsapp ?? '';
        $this->city = $this->profile->city ?? '';
        $this->education_level = $this->profile->education_level ?? 'sma';
        $this->education_institution = $this->profile->education_institution ?? '';
        $this->field_of_study = $this->profile->field_of_study ?? '';
        $this->work_experience = $this->profile->work_experience ?? '';
        $this->salary_expectation = $this->profile->salary_expectation ?? '';
        $this->skills = $this->profile->skills ?? [];
        $this->profile_picture = $this->profile->photo_url ?? '';
        $this->cover_picture = $this->profile->cover_picture_url ?? '';
    }

    public function updatedAvatarFile(): void
    {
        $this->validate([
            'avatarFile' => 'image|max:5120', // max 5MB
        ], [
            'avatarFile.image' => 'File foto profil harus berupa gambar yang valid (JPG, PNG, WEBP, dll).',
            'avatarFile.max'   => 'Ukuran foto profil maksimal 5MB.',
        ]);

        $path = $this->avatarFile->store('profiles', 'public');
        $this->profile->update(['photo' => $path]);
        $this->profile_picture = asset('storage/' . $path);
        $this->avatarFile = null;

        $this->dispatch('notify', ['message' => 'Foto profil berhasil diupload dari galeri!', 'type' => 'success']);
    }

    public function updatedCoverFile(): void
    {
        $this->validate([
            'coverFile' => 'image|max:8192', // max 8MB
        ], [
            'coverFile.image' => 'File foto sampul harus berupa gambar yang valid (JPG, PNG, WEBP, dll).',
            'coverFile.max'   => 'Ukuran foto sampul maksimal 8MB.',
        ]);

        $path = $this->coverFile->store('covers', 'public');
        $this->profile->update(['cover_picture' => $path]);
        $this->cover_picture = asset('storage/' . $path);
        $this->coverFile = null;

        $this->dispatch('notify', ['message' => 'Foto sampul berhasil diupload dari galeri!', 'type' => 'success']);
    }

    public function selectAvatar(string $url): void
    {
        $this->profile_picture = $url;
        $this->profile->update(['photo' => $url]);
        $this->dispatch('notify', ['message' => 'Foto profil berhasil diperbarui!', 'type' => 'success']);
    }

    public function removeAvatar(): void
    {
        $this->profile_picture = '';
        $this->profile->update(['photo' => null]);
        $this->dispatch('notify', ['message' => 'Foto profil dihapus. Profil menggunakan inisial nama.', 'type' => 'info']);
    }

    public function selectCover(string $url): void
    {
        $this->cover_picture = $url;
        $this->profile->update(['cover_picture' => $url]);
        $this->dispatch('notify', ['message' => 'Foto sampul berhasil diperbarui!', 'type' => 'success']);
    }

    public function removeCover(): void
    {
        $this->cover_picture = '';
        $this->profile->update(['cover_picture' => null]);
        $this->dispatch('notify', ['message' => 'Foto sampul di-reset ke tampilan default.', 'type' => 'info']);
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

    public function save(): void
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'whatsapp' => 'required|string',
            'city' => 'required|string',
            'education_level' => 'required|string',
            'education_institution' => 'required|string|max:255',
        ]);

        $user = Auth::user();
        $user->update([
            'name' => $this->name,
            'whatsapp' => $this->whatsapp,
        ]);

        $updateData = [
            'whatsapp' => $this->whatsapp,
            'city' => $this->city,
            'education_level' => $this->education_level,
            'education_institution' => $this->education_institution,
            'field_of_study' => $this->field_of_study,
            'work_experience' => $this->work_experience,
            'salary_expectation' => $this->salary_expectation,
            'skills' => $this->skills,
        ];

        if ($this->profile_picture && (str_starts_with($this->profile_picture, 'http://') || str_starts_with($this->profile_picture, 'https://'))) {
            $updateData['photo'] = $this->profile_picture;
        }

        if ($this->cover_picture && (str_starts_with($this->cover_picture, 'http://') || str_starts_with($this->cover_picture, 'https://'))) {
            $updateData['cover_picture'] = $this->cover_picture;
        }

        $this->profile->update($updateData);

        $this->dispatch('notify', ['message' => 'Profil berhasil disimpan!', 'type' => 'success']);
    }

    public function buyCredit(): void
    {
        // Mock payment — simulate adding 1 credit
        $this->profile->increment('application_credits');
        $this->dispatch('credits-updated', credits: $this->profile->fresh()->application_credits);
        $this->dispatch('notify', ['message' => 'Pembayaran berhasil! 1 kredit lamaran telah ditambahkan.', 'type' => 'success']);
    }

    public function render()
    {
        return view('livewire.applicant.profile-form', [
            'cities' => City::orderBy('name')->get(),
            'educationLevels' => ApplicantProfile::educationLevels(),
            'credits' => $this->profile->fresh()->application_credits ?? 0,
            'cv_generated' => $this->profile->fresh()->cv_generated ?? false,
        ])->title('Profil Pelamar — NEAR JOB');
    }
}
