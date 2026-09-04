<?php

namespace App\Livewire\Applicant;

use App\Models\JobListing;
use App\Models\Application;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class JobDetail extends Component
{
    public JobListing $job;

    public function mount(JobListing $jobListing)
    {
        $this->job = $jobListing;
        
        // Calculate distance from default Banyuwangi center
        $this->job->distance = $this->calculateDistance(-8.2192, 114.3692, $this->job->latitude, $this->job->longitude);
    }

    public function applyForJob(): void
    {
        $user = Auth::user();
        $profile = $user->applicantProfile;

        // Cek apakah sudah pernah melamar
        if (Application::where('user_id', $user->id)->where('job_listing_id', $this->job->id)->exists()) {
            $this->dispatch('notify', 'Anda sudah melamar pekerjaan ini.');
            return;
        }

        // Cek kredit lamaran
        if ($profile->application_credits <= 0) {
            $this->dispatch('notify', 'Kredit lamaran Anda habis. Silakan beli kredit di profil Anda.');
            return;
        }

        // Kurangi kredit
        $profile->decrement('application_credits');
        $this->dispatch('credits-updated', credits: $profile->fresh()->application_credits);

        // Buat record lamaran
        Application::create([
            'user_id' => $user->id,
            'job_listing_id' => $this->job->id,
            'status' => 'menunggu',
            'contact_method' => $this->job->contact_method,
            'application_date' => now(),
        ]);

        // Generate pesan WhatsApp / Email
        if ($this->job->contact_method === 'whatsapp') {
            $message = "Halo {$this->job->company->owner_name}, saya {$user->name} melihat lowongan {$this->job->position} di Near Job. Apakah lowongan ini masih tersedia?";
            $waNumber = preg_replace('/[^0-9]/', '', $this->job->contact_whatsapp);
            if (str_starts_with($waNumber, '0')) {
                $waNumber = '62' . substr($waNumber, 1);
            }
            $url = "https://wa.me/{$waNumber}?text=" . urlencode($message);
            $this->redirect($url);
        } else {
            $subject = "Lamaran Pekerjaan: {$this->job->position} - {$user->name}";
            $body = "Yth. HRD {$this->job->company->company_name},\n\nSaya mendapatkan informasi lowongan {$this->job->position} dari Near Job...\n\nSalam,\n{$user->name}";
            $url = "mailto:{$this->job->contact_email}?subject=" . rawurlencode($subject) . "&body=" . rawurlencode($body);
            $this->redirect($url);
        }
    }
    
    private function calculateDistance($lat1, $lon1, $lat2, $lon2): float
    {
        if (!$lat1 || !$lon1 || !$lat2 || !$lon2) return 0;
        
        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        
        return round($miles * 1.609344, 1);
    }

    public function render()
    {
        $hasApplied = Application::where('user_id', Auth::id())->where('job_listing_id', $this->job->id)->exists();
        $credits = Auth::user()->applicantProfile?->application_credits ?? 0;
        
        return view('livewire.applicant.job-detail', compact('hasApplied', 'credits'))
            ->title($this->job->position . ' — ' . $this->job->company->company_name);
    }
}
