<?php

namespace App\Services\Guard;

use App\Models\GuardProfile;

class ProfileCompletionService
{
    public function calculate(?GuardProfile $profile): array
    {
        if (! $profile) {
            return [
                'percentage' => 0,
                'items' => [],
                'groups' => [],
            ];
        }

        $items = [
            [
                'group' => 'personal',
                'label' => 'Basic Information',
                'complete' => filled($profile->first_name)
                    && filled($profile->last_name)
                    && filled($profile->birth_date)
                    && filled($profile->master_gender_id)
                    && filled($profile->master_civil_status_id)
                    && filled($profile->nationality),
            ],
            [
                'group' => 'personal',
                'label' => 'Contact Details',
                'complete' => filled($profile->contactDetails?->mobile_number),
            ],
            [
                'group' => 'personal',
                'label' => 'Physical Details',
                'complete' => filled($profile->physicalDetail),
            ],
            [
                'group' => 'personal',
                'label' => 'Emergency Contacts',
                'complete' => $profile->emergencyContacts->isNotEmpty(),
            ],
            [
                'group' => 'education',
                'label' => 'Educational Background',
                'complete' => $profile->educations->isNotEmpty(),
            ],
            [
                'group' => 'work',
                'label' => 'Work Experience',
                'complete' => $profile->workExperiences->isNotEmpty(),
            ],
            [
                'group' => 'work',
                'label' => 'Skills',
                'complete' => $profile->skills->isNotEmpty(),
            ],
            [
                'group' => 'work',
                'label' => 'Languages',
                'complete' => $profile->languages->isNotEmpty(),
            ],
            [
                'group' => 'work',
                'label' => 'Specializations',
                'complete' => $profile->specializations->isNotEmpty(),
            ],
            [
                'group' => 'credentials',
                'label' => 'Licenses',
                'complete' => $profile->licenses->isNotEmpty(),
            ],
            [
                'group' => 'credentials',
                'label' => 'Trainings',
                'complete' => $profile->trainings->isNotEmpty(),
            ],
            [
                'group' => 'credentials',
                'label' => 'Certifications',
                'complete' => $profile->certifications->isNotEmpty(),
            ],
            [
                'group' => 'credentials',
                'label' => 'Firearm Qualification',
                'complete' => filled($profile->firearmQualification),
            ],
            [
                'group' => 'documents',
                'label' => 'Clearances',
                'complete' => $profile->clearances->isNotEmpty(),
            ],
            [
                'group' => 'documents',
                'label' => 'Medical Records',
                'complete' => $profile->medicals->isNotEmpty(),
            ],
            [
                'group' => 'documents',
                'label' => 'Identifications',
                'complete' => $profile->identifications->isNotEmpty(),
            ],
            [
                'group' => 'documents',
                'label' => 'Resume',
                'complete' => $profile->hasMedia('resume'),
            ],
            [
                'group' => 'preferences',
                'label' => 'Employment Preferences',
                'complete' => filled($profile->employmentPreference),
            ],
            [
                'group' => 'preferences',
                'label' => 'Availability',
                'complete' => filled($profile->availability),
            ],
        ];

        $completed = collect($items)
            ->where('complete', true)
            ->count();

        $groups = collect($items)
            ->groupBy('group')
            ->map(function ($groupItems, string $key) {
                $completedGroupItems = $groupItems
                    ->where('complete', true)
                    ->count();

                return [
                    'key' => $key,
                    'total' => $groupItems->count(),
                    'completed' => $completedGroupItems,
                    'percentage' => round(
                        ($completedGroupItems / $groupItems->count()) * 100
                    ),
                    'items' => $groupItems->values(),
                ];
            })
            ->toArray();

        return [
            'percentage' => round(($completed / count($items)) * 100),
            'items' => $items,
            'groups' => $groups,
        ];
    }
}
