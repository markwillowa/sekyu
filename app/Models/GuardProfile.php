<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class GuardProfile extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'user_id',
        'first_name',
        'middle_name',
        'last_name',
        'suffix',
        'headline',
        'summary',
        'birth_date',
        'master_gender_id',
        'master_civil_status_id',
        'nationality',
    ];

    protected $casts = [
        'birth_date' => 'date',
    ];

    public function gender(): BelongsTo
    {
        return $this->belongsTo(MasterGender::class, 'master_gender_id');
    }

    public function civilStatus(): BelongsTo
    {
        return $this->belongsTo(
            MasterCivilStatus::class,
            'master_civil_status_id'
        );
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function contactDetails(): HasOne
    {
        return $this->hasOne(GuardContactDetail::class);
    }

    public function physicalDetail(): HasOne
    {
        return $this->hasOne(GuardPhysicalDetail::class);
    }

    public function employmentPreference(): HasOne
    {
        return $this->hasOne(GuardEmploymentPreference::class);
    }

    public function educations(): HasMany
    {
        return $this->hasMany(GuardEducationalBackground::class);
    }

    public function workExperiences(): HasMany
    {
        return $this->hasMany(GuardWorkExperience::class);
    }

    public function licenses(): HasMany
    {
        return $this->hasMany(GuardLicense::class);
    }

    public function certifications(): HasMany
    {
        return $this->hasMany(GuardCertification::class);
    }

    public function skills(): HasMany
    {
        return $this->hasMany(GuardSkill::class);
    }

    public function languages(): HasMany
    {
        return $this->hasMany(GuardLanguage::class);
    }

    public function trainings(): HasMany
    {
        return $this->hasMany(GuardTraining::class);
    }

    public function references(): HasMany
    {
        return $this->hasMany(GuardReference::class);
    }

    public function availability(): HasOne
    {
        return $this->hasOne(GuardAvailability::class);
    }

    public function medicals(): HasMany
    {
        return $this->hasMany(GuardMedical::class);
    }

    public function clearances(): HasMany
    {
        return $this->hasMany(GuardClearance::class);
    }

    public function identifications(): HasMany
    {
        return $this->hasMany(GuardIdentification::class);
    }

    public function emergencyContacts(): HasMany
    {
        return $this->hasMany(GuardEmergencyContact::class);
    }

    public function firearmQualification(): HasOne
    {
        return $this->hasOne(GuardFirearmQualification::class);
    }

    public function specializations(): HasMany
    {
        return $this->hasMany(GuardSpecialization::class);
    }

    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection('profile-photo')
            ->singleFile();

        $this
            ->addMediaCollection('resume')
            ->singleFile();
    }
}
