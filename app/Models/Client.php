<?php

namespace App\Models;

use Watson\Validating\Injectors\UniqueWithInjector;
use Watson\Validating\ValidatingTrait;

class Client extends Base
{
    use ValidatingTrait, UniqueWithInjector;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'first_name', 'last_name', 'email', 'address', 'phone_no', 'emergency_contact_person',
        'emergency_contact_number', 'date_of_birth', 'session_cost_type', 'session_cost', 'session_paid',
        'gender'
    ];
        
    protected $rules = [
        'user_id' => 'required|integer|exists:users,id',
        'first_name' => 'required|string',
        'last_name' => 'required|string',
        'email' => 'required|email|unique_with:clients,first_name,last_name,date_of_birth',
        'address' => 'required|string',
        'phone_no' => 'required|string',
        'date_of_birth' => 'required|date',
        'emergency_contact_person' => 'required|string',
        'emergency_contact_number' => 'required|string',
        'session_cost_type' => 'required|string',
        'session_cost' => 'required|numeric',
        'session_paid' => 'required|boolean',
        'gender' =>  'required|string'
    ];

    public static function rules($id = null)
    {
        return (new static)->rules;
    }

    public static function boot()
    {
        parent::boot();

        static::deleting(function ($client) {
            foreach ($client->clientPairs()->get() as $clientPair) {
                $clientPair->delete();
            }

            foreach ($client->consentForms()->get() as $consentForm) {
                $consentForm->delete();
            }

            foreach ($client->medicalNotes()->get() as $medicalNote) {
                $medicalNote->delete();
            }

            foreach ($client->scanSessions()->get() as $scanSession) {
                $scanSession->delete();
            }
        });
    }

    public function clientPairs()
    {
        return $this->hasMany(ClientPair::class);
    }

    public function medicalNotes()
    {
        return $this->hasMany(MedicalNote::class);
    }

    public function consentForms()
    {
        return $this->hasMany(ConsentForm::class);
    }

    public function pairs()
    {
        return $this->belongsToMany(Pair::class, 'client_pairs');
    }

    public function scanSessions()
    {
        return $this->hasMany(ScanSession::class);
    }

    public function scanSessionIds()
    {
        return $this->scanSessions()->get()->pluck('id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function age()
    {
        return \Carbon\Carbon::parse($this->date_of_birth)->age;
    }

    public function emergencyDetails()
    {
        return "{$this->emergency_contact_person} ({$this->emergency_contact_number})";
    }

    public function name()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function sessionDetails()
    {
        $session_type = ucFirst($this->session_cost_type);
        return "{$session_type} (\${$this->session_cost})";
    }

    protected $appends = ['name', 'age', 'emergencyDetails', 'sessionDetails', 'user'];

    public function getAgeAttribute()
    {
        return $this->age();
    }

    public function getEmergencyDetailsAttribute()
    {
        return $this->emergencyDetails();
    }

    public function getNameAttribute()
    {
        return $this->name();
    }

    public function getSessionDetailsAttribute()
    {
        return $this->sessionDetails();
    }

    public function getUserAttribute()
    {
        return $this->user()->first();
    }
}
