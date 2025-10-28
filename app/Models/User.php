<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'uid',
        'name',
        'role',
        'username',
        'phone',
        'withdraw_acc_number',
        'password',
        'withdraw_password',
        'invitation_code',
        'invited_user_id',
        'balance',
        'amount',
        'status',
        'country',
        'state',
        'city',
        'village',
        'area',
        'address',
        'remember_token',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'withdraw_password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function setPasswordAttribute($value)
    {
        if ($value) {
            $this->attributes['password'] = bcrypt($value);
        }
    }

    public function setWithdrawPasswordAttribute($value)
    {
        if ($value) {
            $this->attributes['withdraw_password'] = bcrypt($value);
        }
    }
    public function getAuthIdentifierName()
    {
        return 'username';
    }

    public function frozenAmount()
    {
        return $this->hasMany(FrozenAmount::class);
    }
    public function order()
    {
        return $this->hasMany(Order::class);
    }
    public function assignPackage()
    {
        return $this->hasOne(AssignPackage::class);
    }
    public function assignLevel()
    {
        return $this->hasOne(AssignLevel::class);
    }

    public function cashouts()
    {
        return $this->hasMany(Cashout::class);
    }

    public function paymentmethod()
    {
        return $this->hasOne(Paymentmethod::class);
    }

    public function cashins()
    {
        return $this->hasMany(Cashin::class);
    }
    public function assignTrialTask()
    {
        return $this->hasMany(AssignedTrialTask::class);
    }
    public function assignTask()
    {
        return $this->hasMany(AssignTask::class);
    }
    public function invitationCode()
    {
        return $this->hasMany(InvitationCode::class);
    }
    public function gift()
    {
        return $this->hasMany(Gift::class);
    }
    public function bonusHistroy()
    {
        return $this->hasMany(BonusHistroy::class);
    }
    public function trialTaskAssign()
    {
        return $this->hasOne(AssignedTrialTask::class);
    }
    public function inviteUser()
    {
        return $this->hasOne(InvitationCode::class, 'code', 'invitation_code');
    }
    public function reserveAmount()
    {
        return $this->hasOne(FrozenAmount::class);
    }
    public function rttOrder()
    {
        return $this->hasMany(RTTOrder::class);
    }
    public function rttAssignTask()
    {
        return $this->hasMany(RTTAssignTask::class);
    }
    public function invitedUserID()
    {
        return $this->hasOne(__CLASS__, 'id', 'invited_user_id');
    }
}
