<?php

namespace App\Http\Controllers\Guard;

use App\Http\Controllers\Controller;
use App\Models\MasterCivilStatus;
use App\Models\MasterGender;
use App\Models\MasterLanguage;
use App\Models\MasterLanguageProficiency;
use App\Models\MasterLicenseType;
use App\Models\MasterRelationship;
use App\Models\MasterSkill;
use App\Models\MasterSkillLevel;
use App\Models\MasterTrainingType;
use App\Models\MasterSpecialization;
use App\Models\MasterClearanceType;
use App\Models\MasterIdentificationType;
use App\Models\GuardTraining;
use App\Models\GuardSpecialization;
use App\Models\GuardClearance;
use App\Models\GuardMedical;
use App\Models\GuardIdentification;
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
            'guardProfile.clearances.clearanceType',
            'guardProfile.identifications.identificationType',
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

            'genders' => MasterGender::all(),
            'civilStatuses' => MasterCivilStatus::all(),
            'relationships' => MasterRelationship::all(),
            'licenseTypes' => MasterLicenseType::all(),
            'allSkills' => MasterSkill::all(),
            'skillLevels' => MasterSkillLevel::all(),
            'allLanguages' => MasterLanguage::all(),
            'proficiencies' => MasterLanguageProficiency::all(),
            'trainingTypes' => MasterTrainingType::all(),
            'allSpecializations' => MasterSpecialization::all(),
            'clearanceTypes' => MasterClearanceType::all(),
            'identificationTypes' => MasterIdentificationType::all(),
        ]);
    }

    public function updateBasicInformation(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'suffix' => 'nullable|string|max:255',
            'birth_date' => 'required|date',
            'master_gender_id' => 'required|exists:master_genders,id',
            'master_civil_status_id' => 'required|exists:master_civil_statuses,id',
            'nationality' => 'required|string|max:255',
        ]);

        $profile = $request->user()->guardProfile;
        $profile->update($validated);

        return back()->with('success', 'Basic information updated successfully.');
    }

    public function updateContactDetails(Request $request)
    {
        $validated = $request->validate([
            'mobile_number' => 'required|string|max:255',
            'alternate_mobile_number' => 'nullable|string|max:255',
            'current_address' => 'required|string',
            'permanent_address' => 'nullable|string', // Note: permanent_address is not in $fillable in the model I saw earlier, let me re-check
        ]);

        $profile = $request->user()->guardProfile;
        $profile->contactDetails()->updateOrCreate(
            ['guard_profile_id' => $profile->id],
            $validated
        );

        return back()->with('success', 'Contact details updated successfully.');
    }

    public function updatePhysicalDetails(Request $request)
    {
        $validated = $request->validate([
            'height_cm' => 'required|numeric|min:0',
            'weight_kg' => 'required|numeric|min:0',
            'blood_type' => 'nullable|string|max:10',
            'body_type' => 'nullable|string|max:255',
        ]);

        $profile = $request->user()->guardProfile;
        $profile->physicalDetail()->updateOrCreate(
            ['guard_profile_id' => $profile->id],
            $validated
        );

        return back()->with('success', 'Physical details updated successfully.');
    }

    public function storeEmergencyContact(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'master_relationship_id' => 'required|exists:master_relationships,id',
            'mobile_number' => 'required|string|max:255',
            'alternate_mobile_number' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
        ]);

        $profile = $request->user()->guardProfile;
        $profile->emergencyContacts()->create($validated);

        return back()->with('success', 'Emergency contact added successfully.');
    }

    public function updateEmergencyContact(Request $request, \App\Models\GuardEmergencyContact $contact)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'master_relationship_id' => 'required|exists:master_relationships,id',
            'mobile_number' => 'required|string|max:255',
            'alternate_mobile_number' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
        ]);

        $contact->update($validated);

        return back()->with('success', 'Emergency contact updated successfully.');
    }

    public function deleteEmergencyContact(\App\Models\GuardEmergencyContact $contact)
    {
        $contact->delete();

        return back()->with('success', 'Emergency contact deleted successfully.');
    }

    public function storeEducation(Request $request)
    {
        $validated = $request->validate([
            'school_name' => 'required|string|max:255',
            'level' => 'required|string|max:255',
            'course_or_strand' => 'nullable|string|max:255',
            'started_year' => 'nullable|integer|min:1900|max:'.date('Y'),
            'ended_year' => 'nullable|integer|min:1900|max:'.(date('Y') + 10),
            'description' => 'nullable|string',
            'attachment' => 'nullable|file|mimes:jpeg,png,jpg,gif,pdf|max:5120',
        ]);

        $profile = $request->user()->guardProfile;
        $education = $profile->educations()->create($validated);

        if ($request->hasFile('attachment')) {
            $education->addMediaFromRequest('attachment')
                ->toMediaCollection('attachments');
        }

        return back()->with('success', 'Education record added successfully.');
    }

    public function updateEducation(Request $request, \App\Models\GuardEducationalBackground $education)
    {
        $validated = $request->validate([
            'school_name' => 'required|string|max:255',
            'level' => 'required|string|max:255',
            'course_or_strand' => 'nullable|string|max:255',
            'started_year' => 'nullable|integer|min:1900|max:'.date('Y'),
            'ended_year' => 'nullable|integer|min:1900|max:'.(date('Y') + 10),
            'description' => 'nullable|string',
            'attachment' => 'nullable|file|mimes:jpeg,png,jpg,gif,pdf|max:5120',
        ]);

        $education->update($validated);

        if ($request->hasFile('attachment')) {
            $education->addMediaFromRequest('attachment')
                ->toMediaCollection('attachments');
        }

        return back()->with('success', 'Education record updated successfully.');
    }

    public function deleteEducation(\App\Models\GuardEducationalBackground $education)
    {
        $education->delete();

        return back()->with('success', 'Education record deleted successfully.');
    }

    public function storeCertification(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'issuer' => 'required|string|max:255',
            'issued_at' => 'nullable|date',
            'expires_at' => 'nullable|date',
            'credential_id' => 'nullable|string|max:255',
            'attachment' => 'nullable|file|mimes:jpeg,png,jpg,gif,pdf|max:5120',
        ]);

        $profile = $request->user()->guardProfile;
        $certification = $profile->certifications()->create([
            'name' => $validated['name'],
            'issuing_organization' => $validated['issuer'],
            'issued_at' => $validated['issued_at'],
            'expires_at' => $validated['expires_at'],
            'credential_number' => $validated['credential_id'],
        ]);

        if ($request->hasFile('attachment')) {
            $certification->addMediaFromRequest('attachment')
                ->toMediaCollection('certificates');
        }

        return back()->with('success', 'Certification added successfully.');
    }

    public function updateCertification(Request $request, \App\Models\GuardCertification $certification)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'issuer' => 'required|string|max:255',
            'issued_at' => 'nullable|date',
            'expires_at' => 'nullable|date',
            'credential_id' => 'nullable|string|max:255',
            'attachment' => 'nullable|file|mimes:jpeg,png,jpg,gif,pdf|max:5120',
        ]);

        $certification->update([
            'name' => $validated['name'],
            'issuing_organization' => $validated['issuer'],
            'issued_at' => $validated['issued_at'],
            'expires_at' => $validated['expires_at'],
            'credential_number' => $validated['credential_id'],
        ]);

        if ($request->hasFile('attachment')) {
            $certification->addMediaFromRequest('attachment')
                ->toMediaCollection('certificates');
        }

        return back()->with('success', 'Certification updated successfully.');
    }

    public function deleteCertification(\App\Models\GuardCertification $certification)
    {
        $certification->delete();

        return back()->with('success', 'Certification deleted successfully.');
    }

    public function updateFirearmQualification(Request $request)
    {
        $validated = $request->validate([
            'is_firearm_qualified' => 'required|boolean',
            'firearm_type' => 'nullable|string|max:255',
            'permit_number' => 'nullable|string|max:255',
            'issued_at' => 'nullable|date',
            'expires_at' => 'nullable|date',
        ]);

        $profile = $request->user()->guardProfile;

        $profile->firearmQualification()->updateOrCreate(
            ['guard_profile_id' => $profile->id],
            $validated
        );

        return back()->with('success', 'Firearm qualification updated successfully.');
    }

    public function storeWorkExperience(Request $request)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'started_at' => 'required|date',
            'ended_at' => 'nullable|date',
            'is_current' => 'nullable|boolean',
            'description' => 'nullable|string',
        ]);

        $validated['is_current'] = $request->has('is_current');

        $profile = $request->user()->guardProfile;
        $profile->workExperiences()->create($validated);

        return back()->with('success', 'Work experience added successfully.');
    }

    public function updateWorkExperience(Request $request, \App\Models\GuardWorkExperience $experience)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'started_at' => 'required|date',
            'ended_at' => 'nullable|date',
            'is_current' => 'nullable|boolean',
            'description' => 'nullable|string',
        ]);

        $validated['is_current'] = $request->has('is_current');

        $experience->update($validated);

        return back()->with('success', 'Work experience updated successfully.');
    }

    public function deleteWorkExperience(\App\Models\GuardWorkExperience $experience)
    {
        $experience->delete();

        return back()->with('success', 'Work experience deleted successfully.');
    }

    public function storeLicense(Request $request)
    {
        $validated = $request->validate([
            'master_license_type_id' => 'required|exists:master_license_types,id',
            'license_number' => 'required|string|max:255',
            'issued_at' => 'required|date',
            'expires_at' => 'nullable|date',
            'issuing_authority' => 'nullable|string|max:255',
            'attachment' => 'nullable|file|mimes:jpeg,png,jpg,gif,pdf|max:5120',
        ]);

        $profile = $request->user()->guardProfile;
        $license = $profile->licenses()->create($validated);

        if ($request->hasFile('attachment')) {
            $license->addMediaFromRequest('attachment')
                ->toMediaCollection('licenses');
        }

        return back()->with('success', 'License added successfully.');
    }

    public function updateLicense(Request $request, \App\Models\GuardLicense $license)
    {
        $validated = $request->validate([
            'master_license_type_id' => 'required|exists:master_license_types,id',
            'license_number' => 'required|string|max:255',
            'issued_at' => 'required|date',
            'expires_at' => 'nullable|date',
            'issuing_authority' => 'nullable|string|max:255',
            'attachment' => 'nullable|file|mimes:jpeg,png,jpg,gif,pdf|max:5120',
        ]);

        $license->update($validated);

        if ($request->hasFile('attachment')) {
            $license->addMediaFromRequest('attachment')
                ->toMediaCollection('licenses');
        }

        return back()->with('success', 'License updated successfully.');
    }

    public function deleteLicense(\App\Models\GuardLicense $license)
    {
        $license->delete();

        return back()->with('success', 'License deleted successfully.');
    }

    public function storeSkill(Request $request)
    {
        $validated = $request->validate([
            'master_skill_id' => 'required|exists:master_skills,id',
            'master_skill_level_id' => 'required|exists:master_skill_levels,id',
            'years_of_experience' => 'nullable|integer|min:0',
        ]);

        $profile = $request->user()->guardProfile;
        $profile->skills()->create($validated);

        return back()->with('success', 'Skill added successfully.');
    }

    public function updateSkill(Request $request, \App\Models\GuardSkill $skill)
    {
        $validated = $request->validate([
            'master_skill_id' => 'required|exists:master_skills,id',
            'master_skill_level_id' => 'required|exists:master_skill_levels,id',
            'years_of_experience' => 'nullable|integer|min:0',
        ]);

        $skill->update($validated);

        return back()->with('success', 'Skill updated successfully.');
    }

    public function deleteSkill(\App\Models\GuardSkill $skill)
    {
        $skill->delete();

        return back()->with('success', 'Skill deleted successfully.');
    }

    public function storeLanguage(Request $request)
    {
        $validated = $request->validate([
            'master_language_id' => 'required|exists:master_languages,id',
            'master_language_proficiency_id' => 'required|exists:master_language_proficiencies,id',
        ]);

        $profile = $request->user()->guardProfile;
        $profile->languages()->create($validated);

        return back()->with('success', 'Language added successfully.');
    }

    public function updateLanguage(Request $request, \App\Models\GuardLanguage $language)
    {
        $validated = $request->validate([
            'master_language_id' => 'required|exists:master_languages,id',
            'master_language_proficiency_id' => 'required|exists:master_language_proficiencies,id',
        ]);

        $language->update($validated);

        return back()->with('success', 'Language updated successfully.');
    }

    public function deleteLanguage(\App\Models\GuardLanguage $language)
    {
        $language->delete();

        return back()->with('success', 'Language deleted successfully.');
    }

    public function storeTraining(Request $request)
    {
        $validated = $request->validate([
            'master_training_type_id' => 'required|exists:master_training_types,id',
            'title' => 'required|string|max:255',
            'training_provider' => 'nullable|string|max:255',
            'completed_at' => 'nullable|date',
            'hours' => 'nullable|integer|min:0',
            'certificate_number' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'attachment' => 'nullable|file|mimes:jpeg,png,jpg,gif,pdf|max:5120',
        ]);

        $training = $request->user()->guardProfile->trainings()->create($validated);

        if ($request->hasFile('attachment')) {
            $training->addMediaFromRequest('attachment')
                ->toMediaCollection('trainings');
        }

        return back()->with('success', 'Training added successfully.');
    }

    public function updateTraining(Request $request, GuardTraining $training)
    {
        $validated = $request->validate([
            'master_training_type_id' => 'required|exists:master_training_types,id',
            'title' => 'required|string|max:255',
            'training_provider' => 'nullable|string|max:255',
            'completed_at' => 'nullable|date',
            'hours' => 'nullable|integer|min:0',
            'certificate_number' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'attachment' => 'nullable|file|mimes:jpeg,png,jpg,gif,pdf|max:5120',
        ]);

        $training->update($validated);

        if ($request->hasFile('attachment')) {
            $training->addMediaFromRequest('attachment')
                ->toMediaCollection('trainings');
        }

        return back()->with('success', 'Training updated successfully.');
    }

    public function deleteTraining(GuardTraining $training)
    {
        $training->delete();

        return back()->with('success', 'Training removed successfully.');
    }

    public function storeSpecialization(Request $request)
    {
        $validated = $request->validate([
            'master_specialization_id' => 'required|exists:master_specializations,id',
            'years_of_experience' => 'nullable|integer|min:0',
            'description' => 'nullable|string',
        ]);

        // Check if already exists
        $exists = $request->user()->guardProfile->specializations()
            ->where('master_specialization_id', $validated['master_specialization_id'])
            ->exists();

        if ($exists) {
            return back()->with('error', 'You have already added this specialization.');
        }

        $request->user()->guardProfile->specializations()->create($validated);

        return back()->with('success', 'Specialization added successfully.');
    }

    public function deleteSpecialization(GuardSpecialization $specialization)
    {
        $specialization->delete();

        return back()->with('success', 'Specialization removed successfully.');
    }

    public function storeClearance(Request $request)
    {
        $validated = $request->validate([
            'master_clearance_type_id' => 'required|exists:master_clearance_types,id',
            'clearance_number' => 'nullable|string|max:255',
            'issued_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after_or_equal:issued_at',
            'issuing_office' => 'nullable|string|max:255',
            'attachment' => 'nullable|file|mimes:jpeg,png,jpg,gif,pdf|max:5120',
        ]);

        $clearance = $request->user()->guardProfile->clearances()->create($validated);

        if ($request->hasFile('attachment')) {
            $clearance->addMediaFromRequest('attachment')
                ->toMediaCollection('clearances');
        }

        return back()->with('success', 'Clearance added successfully.');
    }

    public function updateClearance(Request $request, GuardClearance $clearance)
    {
        $validated = $request->validate([
            'master_clearance_type_id' => 'required|exists:master_clearance_types,id',
            'clearance_number' => 'nullable|string|max:255',
            'issued_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after_or_equal:issued_at',
            'issuing_office' => 'nullable|string|max:255',
            'attachment' => 'nullable|file|mimes:jpeg,png,jpg,gif,pdf|max:5120',
        ]);

        $clearance->update($validated);

        if ($request->hasFile('attachment')) {
            $clearance->addMediaFromRequest('attachment')
                ->toMediaCollection('clearances');
        }

        return back()->with('success', 'Clearance updated successfully.');
    }

    public function deleteClearance(GuardClearance $clearance)
    {
        $clearance->delete();

        return back()->with('success', 'Clearance removed successfully.');
    }

    public function storeMedical(Request $request)
    {
        $validated = $request->validate([
            'certificate_type' => 'required|string|max:255',
            'clinic_or_hospital' => 'nullable|string|max:255',
            'physician_name' => 'nullable|string|max:255',
            'issued_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after_or_equal:issued_at',
            'is_fit_to_work' => 'boolean',
            'attachment' => 'nullable|file|mimes:jpeg,png,jpg,gif,pdf|max:5120',
        ]);

        $medical = $request->user()->guardProfile->medicals()->create($validated);

        if ($request->hasFile('attachment')) {
            $medical->addMediaFromRequest('attachment')
                ->toMediaCollection('medical');
        }

        return back()->with('success', 'Medical record added successfully.');
    }

    public function updateMedical(Request $request, GuardMedical $medical)
    {
        $validated = $request->validate([
            'certificate_type' => 'required|string|max:255',
            'clinic_or_hospital' => 'nullable|string|max:255',
            'physician_name' => 'nullable|string|max:255',
            'issued_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after_or_equal:issued_at',
            'is_fit_to_work' => 'boolean',
            'attachment' => 'nullable|file|mimes:jpeg,png,jpg,gif,pdf|max:5120',
        ]);

        $medical->update($validated);

        if ($request->hasFile('attachment')) {
            $medical->addMediaFromRequest('attachment')
                ->toMediaCollection('medical');
        }

        return back()->with('success', 'Medical record updated successfully.');
    }

    public function deleteMedical(GuardMedical $medical)
    {
        $medical->delete();

        return back()->with('success', 'Medical record removed successfully.');
    }

    public function storeIdentification(Request $request)
    {
        $validated = $request->validate([
            'master_identification_type_id' => 'required|exists:master_identification_types,id',
            'id_type' => 'nullable|string|max:255',
            'id_number' => 'required|string|max:255',
            'issued_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after_or_equal:issued_at',
            'issuing_authority' => 'nullable|string|max:255',
            'attachment' => 'nullable|file|mimes:jpeg,png,jpg,gif,pdf|max:5120',
        ]);

        $identification = $request->user()->guardProfile->identifications()->create($validated);

        if ($request->hasFile('attachment')) {
            $identification->addMediaFromRequest('attachment')
                ->toMediaCollection('identifications');
        }

        return back()->with('success', 'Identification added successfully.');
    }

    public function updateIdentification(Request $request, GuardIdentification $identification)
    {
        $validated = $request->validate([
            'master_identification_type_id' => 'required|exists:master_identification_types,id',
            'id_type' => 'nullable|string|max:255',
            'id_number' => 'required|string|max:255',
            'issued_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after_or_equal:issued_at',
            'issuing_authority' => 'nullable|string|max:255',
            'attachment' => 'nullable|file|mimes:jpeg,png,jpg,gif,pdf|max:5120',
        ]);

        $identification->update($validated);

        if ($request->hasFile('attachment')) {
            $identification->addMediaFromRequest('attachment')
                ->toMediaCollection('identifications');
        }

        return back()->with('success', 'Identification updated successfully.');
    }

    public function deleteIdentification(GuardIdentification $identification)
    {
        $identification->delete();

        return back()->with('success', 'Identification removed successfully.');
    }

    public function updateDocuments(Request $request)
    {
        $request->validate([
            'profile_photo' => 'nullable|file|mimes:jpeg,png,jpg,gif,pdf|max:2048',
            'resume' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
        ]);

        $profile = $request->user()->guardProfile;

        if ($request->hasFile('profile_photo')) {
            $profile->addMediaFromRequest('profile_photo')
                ->toMediaCollection('profile-photo');
        }

        if ($request->hasFile('resume')) {
            $profile->addMediaFromRequest('resume')
                ->toMediaCollection('resume');
        }

        return back()->with('success', 'Documents updated successfully.');
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
