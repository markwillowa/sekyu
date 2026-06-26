<?php

namespace App\Http\Controllers\Guard;

use App\Http\Controllers\Controller;
use App\Services\Guard\ProfileCompletionService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function show(
        Request $request,
        ProfileCompletionService $completionService,
        ?string $section = 'overview',
    ): View {
        $allowedSections = [
            'overview',
            'personal',
            'education',
            'work',
            'credentials',
            'documents',
            'preferences',
        ];

        if (! in_array($section, $allowedSections, true)) {
            abort(404);
        }

        $user = $request->user()->load([
            'guardProfile.gender',
            'guardProfile.civilStatus',
            'guardProfile.contactDetails',
            'guardProfile.physicalDetail',
            'guardProfile.employmentPreference',
            'guardProfile.educations',
            'guardProfile.workExperiences',
            'guardProfile.licenses',
            'guardProfile.certifications',
            'guardProfile.skills',
            'guardProfile.languages',
            'guardProfile.trainings',
            'guardProfile.references',
            'guardProfile.availability',
            'guardProfile.medicals',
            'guardProfile.clearances',
            'guardProfile.identifications',
            'guardProfile.emergencyContacts',
            'guardProfile.firearmQualification',
            'guardProfile.specializations',
        ]);

        $profile = $user->guardProfile;
        $completion = $completionService->calculate($profile);

        return view('guard.profile.show', [
            'section' => $section,

            'user' => $user,
            'profile' => $profile,

            'completion' => $completion['percentage'],
            'completionItems' => $completion['items'],
            'completionGroups' => $completion['groups'],

            'contactDetails' => $profile?->contactDetails,
            'physicalDetail' => $profile?->physicalDetail,
            'employmentPreference' => $profile?->employmentPreference,

            'educations' => $profile?->educations ?? collect(),
            'workExperiences' => $profile?->workExperiences ?? collect(),
            'licenses' => $profile?->licenses ?? collect(),
            'certifications' => $profile?->certifications ?? collect(),
            'skills' => $profile?->skills ?? collect(),
            'languages' => $profile?->languages ?? collect(),
            'trainings' => $profile?->trainings ?? collect(),
            'references' => $profile?->references ?? collect(),
            'medicals' => $profile?->medicals ?? collect(),
            'clearances' => $profile?->clearances ?? collect(),
            'identifications' => $profile?->identifications ?? collect(),
            'emergencyContacts' => $profile?->emergencyContacts ?? collect(),
            'specializations' => $profile?->specializations ?? collect(),

            'availability' => $profile?->availability,
            'firearmQualification' => $profile?->firearmQualification,
        ]);
    }

    public function preview(Request $request): View
    {
        $user = $request->user()->load([
            'guardProfile.gender',
            'guardProfile.civilStatus',
            'guardProfile.contactDetails',
            'guardProfile.physicalDetail',
            'guardProfile.employmentPreference',
            'guardProfile.educations',
            'guardProfile.workExperiences',
            'guardProfile.licenses',
            'guardProfile.certifications',
            'guardProfile.skills',
            'guardProfile.languages',
            'guardProfile.trainings',
            'guardProfile.clearances',
            'guardProfile.identifications',
            'guardProfile.specializations',
        ]);

        $profile = $user->guardProfile;

        return view('guard.profile.preview', [
            'user' => $user,
            'profile' => $profile,
            'contactDetails' => $profile?->contactDetails,
            'physicalDetail' => $profile?->physicalDetail,
            'employmentPreference' => $profile?->employmentPreference,
            'educations' => $profile?->educations ?? collect(),
            'workExperiences' => $profile?->workExperiences ?? collect(),
            'licenses' => $profile?->licenses ?? collect(),
            'certifications' => $profile?->certifications ?? collect(),
            'skills' => $profile?->skills ?? collect(),
            'languages' => $profile?->languages ?? collect(),
            'trainings' => $profile?->trainings ?? collect(),
            'clearances' => $profile?->clearances ?? collect(),
            'identifications' => $profile?->identifications ?? collect(),
            'specializations' => $profile?->specializations ?? collect(),
        ]);
    }
}
