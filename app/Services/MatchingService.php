<?php

namespace App\Services;

use App\Models\ApplicantProfile;
use App\Models\JobListing;
use App\Models\User;
use Illuminate\Support\Collection;

class MatchingService
{
    /**
     * Calculate distance between two lat/lng points using the Haversine formula.
     * Returns distance in kilometers.
     */
    public static function haversineDistance(
        float $lat1,
        float $lng1,
        float $lat2,
        float $lng2
    ): float {
        $earthRadius = 6371; // km

        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);

        $a = sin($dLat / 2) * sin($dLat / 2)
            + cos(deg2rad($lat1)) * cos(deg2rad($lat2))
            * sin($dLng / 2) * sin($dLng / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }

    /**
     * Get matching job listings for an applicant.
     * Filters by: radius, skills, education level, and excludes already-swiped jobs.
     */
    public function getMatchingJobs(User $applicant, int $limit = 10): Collection
    {
        $profile = $applicant->applicantProfile;

        if (!$profile || !$profile->isComplete()) {
            return collect();
        }

        // Get IDs of jobs already swiped
        $swipedJobIds = $applicant->swipeHistories()->pluck('job_listing_id')->toArray();

        // Get active job listings, excluding already-swiped ones
        $jobs = JobListing::active()
            ->with('company')
            ->whereNotIn('id', $swipedJobIds)
            ->get();

        // Filter by matching criteria
        $matchedJobs = $jobs->filter(function (JobListing $job) use ($profile) {
            // 1. Check distance (Haversine)
            if ($profile->latitude && $profile->longitude && $job->latitude && $job->longitude) {
                $distance = self::haversineDistance(
                    (float) $profile->latitude,
                    (float) $profile->longitude,
                    (float) $job->latitude,
                    (float) $job->longitude
                );

                if ($distance > $job->radius_km) {
                    return false;
                }

                // Store distance for display
                $job->distance_km = round($distance, 1);
            }

            // 2. Check minimum education
            if ($profile->education_level && $job->min_education) {
                $applicantRank = ApplicantProfile::educationRank($profile->education_level);
                $requiredRank = ApplicantProfile::educationRank($job->min_education);

                if ($applicantRank < $requiredRank) {
                    return false;
                }
            }

            // 3. Check required skills (at least one matching skill)
            if (!empty($job->required_skills) && is_array($job->required_skills)) {
                $applicantSkills = array_map('strtolower', $profile->skills ?? []);
                $requiredSkills = array_map('strtolower', $job->required_skills);

                $matchingSkills = array_intersect($applicantSkills, $requiredSkills);

                if (empty($matchingSkills) && !empty($requiredSkills)) {
                    return false;
                }
            }

            return true;
        });

        // Sort by distance (closest first), then by newest
        return $matchedJobs->sortBy([
            ['distance_km', 'asc'],
            ['created_at', 'desc'],
        ])->take($limit)->values();
    }
}
