<?php

namespace Database\Seeders;

use App\Models\Application;
use App\Models\ApplicantProfile;
use App\Models\Company;
use App\Models\JobListing;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        // ============================================================
        // APPLICANT DEMO — BUDI (pelamar utama di Yogyakarta)
        // email: budi@demo.com | password: password
        // ============================================================
        $budi = User::create([
            'name'          => 'Budi Santoso',
            'email'         => 'budi@demo.com',
            'password'      => Hash::make('password'),
            'role'          => 'applicant',
            'nik'           => '3402011505010001',
            'whatsapp'      => '081234567890',
            'date_of_birth' => '2001-05-15',
        ]);
        ApplicantProfile::create([
            'user_id'               => $budi->id,
            'whatsapp'              => '081234567890',
            'education_level'       => 'sma',
            'education_institution' => 'SMAN 3 Yogyakarta',
            'field_of_study'        => 'IPS',
            'work_experience'       => "1. Kasir Minimarket Indomaret (1 tahun)\n2. Pramusaji Restoran Padang (6 bulan)",
            'skills'                => ['Pelayanan pelanggan', 'Kasir', 'MS Excel', 'Komunikasi'],
            'salary_expectation'    => 'Rp2.500.000 – Rp3.000.000',
            'contact_email'         => 'budi@demo.com',
            'city'                  => 'Yogyakarta',
            'latitude'              => -7.7956,
            'longitude'             => 110.3695,
            'is_active'             => true,
            'application_credits'   => 3,
        ]);

        // ============================================================
        // APPLICANT DEMO 2 — SARI (kredit habis untuk demo)
        // email: sari@demo.com | password: password
        // ============================================================
        $sari = User::create([
            'name'          => 'Sari Wulandari',
            'email'         => 'sari@demo.com',
            'password'      => Hash::make('password'),
            'role'          => 'applicant',
            'nik'           => '3402014904020002',
            'whatsapp'      => '082345678901',
            'date_of_birth' => '2000-04-09',
        ]);
        ApplicantProfile::create([
            'user_id'               => $sari->id,
            'whatsapp'              => '082345678901',
            'education_level'       => 'sma',
            'education_institution' => 'SMK Negeri 2 Yogyakarta',
            'field_of_study'        => 'Tata Boga',
            'work_experience'       => 'Magang di Hotel Grand Mercure Yogyakarta (3 bulan)',
            'skills'                => ['Memasak', 'Pelayanan', 'Kebersihan'],
            'salary_expectation'    => 'Rp2.000.000 – Rp2.500.000',
            'contact_email'         => 'sari@demo.com',
            'city'                  => 'Yogyakarta',
            'latitude'              => -7.8011,
            'longitude'             => 110.3750,
            'is_active'             => true,
            'application_credits'   => 0,
        ]);

        // ============================================================
        // EMPLOYER DEMO — Berbagai bisnis di Yogyakarta
        // email: [nama]@demo.com | password: password
        // ============================================================

        // Employer 1 — Warung Gudeg Bu Yanti (FnB)
        $userWarung = User::create(['name' => 'Yanti Kusuma', 'email' => 'yanti@demo.com', 'password' => Hash::make('password'), 'role' => 'company', 'nik' => '3402015506780003', 'whatsapp' => '081298765432']);
        $warungGudeg = Company::create([
            'user_id' => $userWarung->id, 'owner_name' => 'Yanti Kusuma', 'nik' => '3402015506780003',
            'whatsapp' => '6281298765432', 'company_name' => 'Warung Gudeg Bu Yanti',
            'business_field' => 'fnb', 'address' => 'Jl. Malioboro No. 23, Yogyakarta',
            'city' => 'Yogyakarta', 'latitude' => -7.7928, 'longitude' => 110.3651,
            'contact_email' => 'yanti@demo.com', 'contact_method' => 'whatsapp', 'agreed_to_terms' => true,
        ]);

        // Employer 2 — Toko Batik Sekar (Retail)
        $userBatik = User::create(['name' => 'Agus Setiawan', 'email' => 'agus@demo.com', 'password' => Hash::make('password'), 'role' => 'company', 'nik' => '3402016607890004', 'whatsapp' => '085712345678']);
        $tokoBatik = Company::create([
            'user_id' => $userBatik->id, 'owner_name' => 'Agus Setiawan', 'nik' => '3402016607890004',
            'whatsapp' => '6285712345678', 'company_name' => 'Toko Batik Sekar',
            'business_field' => 'retail', 'address' => 'Jl. Tirtodipuran No. 7, Yogyakarta',
            'city' => 'Yogyakarta', 'latitude' => -7.8102, 'longitude' => 110.3600,
            'contact_email' => 'agus@demo.com', 'contact_method' => 'whatsapp', 'agreed_to_terms' => true,
        ]);

        // Employer 3 — Hotel Mutiara Yogya (Jasa)
        $userHotel = User::create(['name' => 'Dewi Rahayu', 'email' => 'dewi@demo.com', 'password' => Hash::make('password'), 'role' => 'company', 'nik' => '3402017708900005', 'whatsapp' => '087823456789']);
        $hotelMutiara = Company::create([
            'user_id' => $userHotel->id, 'owner_name' => 'Dewi Rahayu', 'nik' => '3402017708900005',
            'whatsapp' => '6287823456789', 'company_name' => 'Hotel Mutiara Yogya', 'nib' => '0123456789012',
            'business_field' => 'jasa', 'address' => 'Jl. Prawirotaman No. 15, Yogyakarta',
            'city' => 'Yogyakarta', 'latitude' => -7.8200, 'longitude' => 110.3700,
            'contact_email' => 'dewi@demo.com', 'contact_method' => 'whatsapp', 'agreed_to_terms' => true,
        ]);

        // Employer 4 — CV Karya Mandiri (Produksi)
        $userPabrik = User::create(['name' => 'Hendra Gunawan', 'email' => 'hendra@demo.com', 'password' => Hash::make('password'), 'role' => 'company', 'nik' => '3402018809010006', 'whatsapp' => '081345678901']);
        $cvKarya = Company::create([
            'user_id' => $userPabrik->id, 'owner_name' => 'Hendra Gunawan', 'nik' => '3402018809010006',
            'whatsapp' => '6281345678901', 'company_name' => 'CV Karya Mandiri', 'nib' => '9876543210123',
            'business_field' => 'produksi', 'address' => 'Kawasan Industri Berbah, Sleman',
            'city' => 'Yogyakarta', 'latitude' => -7.7700, 'longitude' => 110.4300,
            'contact_email' => 'hendra@demo.com', 'contact_method' => 'email', 'agreed_to_terms' => true,
        ]);

        // Employer 5 — PT Nusantara Logistik (Logistik)
        $userLogistik = User::create(['name' => 'Rini Susanti', 'email' => 'rini@demo.com', 'password' => Hash::make('password'), 'role' => 'company', 'nik' => '3402019910120007', 'whatsapp' => '082456789012']);
        $ptLogistik = Company::create([
            'user_id' => $userLogistik->id, 'owner_name' => 'Rini Susanti', 'nik' => '3402019910120007',
            'whatsapp' => '6282456789012', 'company_name' => 'PT Nusantara Logistik', 'nib' => '1122334455667',
            'business_field' => 'logistik', 'address' => 'Jl. Ring Road Utara No. 88, Sleman',
            'city' => 'Yogyakarta', 'latitude' => -7.7500, 'longitude' => 110.3900,
            'contact_email' => 'rini@demo.com', 'contact_method' => 'email', 'agreed_to_terms' => true,
        ]);

        // Employer 6 — CV Bangun Jaya (Konstruksi)
        $userKonstr = User::create(['name' => 'Eko Prasetyo', 'email' => 'eko@demo.com', 'password' => Hash::make('password'), 'role' => 'company', 'nik' => '3402010011130008', 'whatsapp' => '083567890123']);
        $cvBangun = Company::create([
            'user_id' => $userKonstr->id, 'owner_name' => 'Eko Prasetyo', 'nik' => '3402010011130008',
            'whatsapp' => '6283567890123', 'company_name' => 'CV Bangun Jaya',
            'business_field' => 'konstruksi', 'address' => 'Jl. Godean KM 5, Sleman',
            'city' => 'Yogyakarta', 'latitude' => -7.7850, 'longitude' => 110.3200,
            'contact_email' => 'eko@demo.com', 'contact_method' => 'whatsapp', 'agreed_to_terms' => true,
        ]);

        // Employer 7 — Klinik Sehat Sejahtera (Jasa/Administrasi)
        $userKlinik = User::create(['name' => 'Fitri Amalia', 'email' => 'fitri@demo.com', 'password' => Hash::make('password'), 'role' => 'company', 'nik' => '3402011112140009', 'whatsapp' => '084678901234']);
        $klinikSehat = Company::create([
            'user_id' => $userKlinik->id, 'owner_name' => 'Fitri Amalia', 'nik' => '3402011112140009',
            'whatsapp' => '6284678901234', 'company_name' => 'Klinik Sehat Sejahtera', 'nib' => '5544332211009',
            'business_field' => 'jasa', 'address' => 'Jl. Kaliurang KM 8, Sleman',
            'city' => 'Yogyakarta', 'latitude' => -7.7600, 'longitude' => 110.3850,
            'contact_email' => 'fitri@demo.com', 'contact_method' => 'email', 'agreed_to_terms' => true,
        ]);

        // Employer 8 — Bengkel Maju Motor (Teknisi)
        $userBengkel = User::create(['name' => 'Doni Purnomo', 'email' => 'doni@demo.com', 'password' => Hash::make('password'), 'role' => 'company', 'nik' => '3402012213150010', 'whatsapp' => '085789012345']);
        $bengkelMaju = Company::create([
            'user_id' => $userBengkel->id, 'owner_name' => 'Doni Purnomo', 'nik' => '3402012213150010',
            'whatsapp' => '6285789012345', 'company_name' => 'Bengkel Maju Motor',
            'business_field' => 'jasa', 'address' => 'Jl. Bantul No. 42, Bantul',
            'city' => 'Yogyakarta', 'latitude' => -7.8400, 'longitude' => 110.3500,
            'contact_email' => 'doni@demo.com', 'contact_method' => 'whatsapp', 'agreed_to_terms' => true,
        ]);

        // ============================================================
        // JOB LISTINGS — Mewakili SEMUA kategori dan tipe kerja
        // ============================================================

        // 1. FnB + Full Time + WhatsApp
        $job1 = JobListing::create([
            'company_id' => $warungGudeg->id, 'position' => 'Pramusaji',
            'description' => 'Melayani tamu dengan ramah, mencatat pesanan, menyajikan makanan dan minuman, serta menjaga kebersihan meja makan.',
            'qualifications' => 'Minimal SMP. Ramah, cekatan, dan mau belajar. Tidak perlu pengalaman.',
            'required_skills' => ['Pelayanan', 'Ramah', 'Cepat'], 'city' => 'Yogyakarta',
            'latitude' => -7.7930, 'longitude' => 110.3653,
            'work_type' => 'full_time', 'job_category' => 'fnb', 'min_education' => 'sma',
            'salary_min' => 2000000, 'salary_max' => 2500000, 'work_duration' => 'Tetap',
            'work_hours' => '09.00 – 21.00 (shift)', 'contact_method' => 'whatsapp',
            'contact_whatsapp' => '6281298765432', 'quota' => 3, 'status' => 'active',
        ]);

        // 2. Retail + Part Time + WhatsApp
        $job2 = JobListing::create([
            'company_id' => $tokoBatik->id, 'position' => 'SPG / Pramuniaga Paruh Waktu',
            'description' => 'Membantu melayani pelanggan di toko batik, menjelaskan produk, menjaga kerapian display, dan memproses transaksi.',
            'qualifications' => 'Minimal SMA. Berpenampilan menarik, komunikatif, dan suka berinteraksi dengan orang.',
            'required_skills' => ['Komunikasi', 'Pelayanan pelanggan', 'Rapi'], 'city' => 'Yogyakarta',
            'latitude' => -7.8105, 'longitude' => 110.3602,
            'work_type' => 'part_time', 'job_category' => 'retail', 'min_education' => 'sma',
            'salary_min' => 1200000, 'salary_max' => 1800000, 'work_duration' => 'Paruh waktu (akhir pekan)',
            'work_hours' => 'Sabtu–Minggu 09.00 – 18.00', 'contact_method' => 'whatsapp',
            'contact_whatsapp' => '6285712345678', 'quota' => 2, 'status' => 'active',
        ]);

        // 3. Jasa + Full Time + WhatsApp
        $job3 = JobListing::create([
            'company_id' => $hotelMutiara->id, 'position' => 'Resepsionis Hotel',
            'description' => 'Menyambut tamu, proses check-in/check-out, melayani pertanyaan tamu, dan berkoordinasi dengan departemen lain.',
            'qualifications' => 'Minimal D3 semua jurusan. Berpenampilan menarik, fasih berbahasa Indonesia. Diutamakan berpengalaman.',
            'required_skills' => ['Komunikasi', 'MS Office', 'Pelayanan tamu', 'Bahasa Inggris dasar'], 'city' => 'Yogyakarta',
            'latitude' => -7.8205, 'longitude' => 110.3703,
            'work_type' => 'full_time', 'job_category' => 'jasa', 'min_education' => 'd3',
            'salary_min' => 2800000, 'salary_max' => 3500000, 'work_duration' => 'Tetap',
            'work_hours' => 'Shift: 07.00–15.00 / 15.00–23.00 / 23.00–07.00', 'contact_method' => 'whatsapp',
            'contact_whatsapp' => '6287823456789', 'quota' => 1, 'status' => 'active',
        ]);

        // 4. Produksi + Kontrak + Email
        $job4 = JobListing::create([
            'company_id' => $cvKarya->id, 'position' => 'Operator Mesin Jahit',
            'description' => 'Mengoperasikan mesin jahit industri untuk produksi pakaian, memastikan kualitas jahitan sesuai standar, dan memenuhi target produksi harian.',
            'qualifications' => 'Minimal SMA/SMK. Terampil mengoperasikan mesin jahit industri. Berpengalaman minimal 1 tahun diutamakan.',
            'required_skills' => ['Menjahit', 'Mesin jahit industri', 'Teliti', 'Target-oriented'], 'city' => 'Yogyakarta',
            'latitude' => -7.7705, 'longitude' => 110.4302,
            'work_type' => 'kontrak', 'job_category' => 'produksi', 'min_education' => 'sma',
            'salary_min' => 2500000, 'salary_max' => 3200000, 'work_duration' => 'Kontrak 6 bulan (dapat diperpanjang)',
            'work_hours' => '07.00 – 16.00 (Senin–Sabtu)', 'contact_method' => 'email',
            'contact_email' => 'hendra@demo.com', 'quota' => 5, 'status' => 'active',
        ]);

        // 5. Logistik + Harian + Email
        $job5 = JobListing::create([
            'company_id' => $ptLogistik->id, 'position' => 'Kuli Angkut / Porter Harian',
            'description' => 'Membantu kegiatan bongkar muat barang di gudang, mengoperasikan forklift manual, dan menyusun barang di area penyimpanan.',
            'qualifications' => 'Minimal SMP. Sehat jasmani, kuat secara fisik, dan siap bekerja outdoor.',
            'required_skills' => ['Fisik kuat', 'Bongkar muat', 'Teliti'], 'city' => 'Yogyakarta',
            'latitude' => -7.7505, 'longitude' => 110.3905,
            'work_type' => 'harian', 'job_category' => 'logistik', 'min_education' => 'sma',
            'salary_min' => 120000, 'salary_max' => 150000, 'work_duration' => 'Harian (sesuai kebutuhan)',
            'work_hours' => '07.00 – 16.00', 'contact_method' => 'email',
            'contact_email' => 'rini@demo.com', 'quota' => 4, 'status' => 'active',
        ]);

        // 6. Konstruksi + Harian + WhatsApp
        $job6 = JobListing::create([
            'company_id' => $cvBangun->id, 'position' => 'Tukang Bangunan / Kuli Bangunan',
            'description' => 'Membantu pekerjaan konstruksi ringan meliputi pengecoran, plesteran, pemasangan bata, dan pekerjaan finishing bangunan.',
            'qualifications' => 'Diutamakan berpengalaman di bidang bangunan. Sehat jasmani dan kuat secara fisik.',
            'required_skills' => ['Konstruksi', 'Plesteran', 'Fisik prima'], 'city' => 'Yogyakarta',
            'latitude' => -7.7855, 'longitude' => 110.3205,
            'work_type' => 'harian', 'job_category' => 'konstruksi', 'min_education' => 'sma',
            'salary_min' => 130000, 'salary_max' => 180000, 'work_duration' => 'Harian (proyek)',
            'work_hours' => '07.00 – 17.00', 'contact_method' => 'whatsapp',
            'contact_whatsapp' => '6283567890123', 'quota' => 6, 'status' => 'active',
        ]);

        // 7. Administrasi + Full Time + Email
        $job7 = JobListing::create([
            'company_id' => $klinikSehat->id, 'position' => 'Admin & Resepsionis Klinik',
            'description' => 'Mendaftarkan pasien, mengelola jadwal dokter, mengarsip rekam medis, mengelola kasir klinik, dan membantu administrasi umum klinik.',
            'qualifications' => 'Minimal D3 semua jurusan. Mahir MS Office (Word, Excel). Teliti, jujur, dan mampu bekerja di bawah tekanan.',
            'required_skills' => ['MS Office', 'Administrasi', 'Teliti', 'Pelayanan pasien'], 'city' => 'Yogyakarta',
            'latitude' => -7.7605, 'longitude' => 110.3852,
            'work_type' => 'full_time', 'job_category' => 'administrasi', 'min_education' => 'd3',
            'salary_min' => 2500000, 'salary_max' => 3000000, 'work_duration' => 'Tetap',
            'work_hours' => '08.00 – 17.00 (Senin–Sabtu)', 'contact_method' => 'email',
            'contact_email' => 'fitri@demo.com', 'quota' => 2, 'status' => 'active',
        ]);

        // 8. Teknisi + Full Time + WhatsApp
        $job8 = JobListing::create([
            'company_id' => $bengkelMaju->id, 'position' => 'Mekanik Sepeda Motor',
            'description' => 'Melakukan servis rutin dan perbaikan sepeda motor, mendiagnosa kerusakan kendaraan, mengganti suku cadang, dan memberikan rekomendasi perawatan kepada pelanggan.',
            'qualifications' => 'Minimal SMK Teknik Otomotif. Berpengalaman servis motor minimal 1 tahun. Diutamakan lulusan SMK bidang otomotif.',
            'required_skills' => ['Mekanik motor', 'Servis kendaraan', 'Diagnosa kerusakan', 'Otomotif'], 'city' => 'Yogyakarta',
            'latitude' => -7.8405, 'longitude' => 110.3502,
            'work_type' => 'full_time', 'job_category' => 'teknisi', 'min_education' => 'sma',
            'salary_min' => 2800000, 'salary_max' => 3500000, 'work_duration' => 'Tetap',
            'work_hours' => '08.00 – 17.00 (Senin–Sabtu)', 'contact_method' => 'whatsapp',
            'contact_whatsapp' => '6285789012345', 'quota' => 3, 'status' => 'active',
        ]);

        // 9. Lainnya + Part Time + WhatsApp
        $job9 = JobListing::create([
            'company_id' => $warungGudeg->id, 'position' => 'Kurir Antar Makanan (Part Time)',
            'description' => 'Mengantarkan pesanan makanan ke pelanggan menggunakan motor pribadi. Bekerja pada jam makan siang dan malam hari.',
            'qualifications' => 'Memiliki SIM C dan motor pribadi. Hafal jalan di area Yogyakarta. Bertanggung jawab dan tepat waktu.',
            'required_skills' => ['Berkendara motor', 'SIM C', 'Navigasi', 'Tanggung jawab'], 'city' => 'Yogyakarta',
            'latitude' => -7.7932, 'longitude' => 110.3658,
            'work_type' => 'part_time', 'job_category' => 'lainnya', 'min_education' => 'sma',
            'salary_min' => 1500000, 'salary_max' => 2500000, 'work_duration' => 'Part time (fleksibel)',
            'work_hours' => '10.00–14.00 & 17.00–21.00', 'contact_method' => 'whatsapp',
            'contact_whatsapp' => '6281298765432', 'quota' => 2, 'status' => 'active',
        ]);

        // 10. Retail + Kontrak + WhatsApp (tambahan untuk variasi radius)
        $job10 = JobListing::create([
            'company_id' => $tokoBatik->id, 'position' => 'Kasir Toko',
            'description' => 'Melayani pembayaran pelanggan, mencatat penjualan harian, mengelola kas kecil, dan membuat laporan transaksi.',
            'qualifications' => 'Minimal SMA/SMK. Jujur, teliti, dan cekatan. Pengalaman sebagai kasir diutamakan.',
            'required_skills' => ['Kasir', 'Teliti', 'Jujur', 'Aritmatika'], 'city' => 'Yogyakarta',
            'latitude' => -7.8108, 'longitude' => 110.3607,
            'work_type' => 'kontrak', 'job_category' => 'retail', 'min_education' => 'sma',
            'salary_min' => 2000000, 'salary_max' => 2500000, 'work_duration' => 'Kontrak 3 bulan',
            'work_hours' => '09.00 – 17.00', 'contact_method' => 'whatsapp',
            'contact_whatsapp' => '6285712345678', 'quota' => 1, 'status' => 'active',
        ]);

        // ============================================================
        // SAMPLE APPLICATIONS for Budi (riwayat lamaran demo)
        // ============================================================
        Application::create([
            'user_id'          => $budi->id,
            'job_listing_id'   => $job1->id,
            'status'           => 'dihubungi',
            'contact_method'   => 'whatsapp',
            'application_date' => now()->subDays(3)->toDateString(),
        ]);
        Application::create([
            'user_id'          => $budi->id,
            'job_listing_id'   => $job7->id,
            'status'           => 'menunggu',
            'contact_method'   => 'email',
            'application_date' => now()->subDays(1)->toDateString(),
        ]);
    }
}
