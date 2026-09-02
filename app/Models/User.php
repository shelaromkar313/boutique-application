<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'role',
        'referral_code',
        'commission_rate',
        'earnings',
        'balance',
        'upi_id',
    ];

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isSalesAssociate(): bool
    {
        return in_array($this->role, ['sales_associate', 'associate', 'sales_executive'], true);
    }

    public function isCustomer(): bool
    {
        return $this->role === 'customer' || empty($this->role);
    }

    public function referralSales()
    {
        return $this->hasMany(\App\Models\ReferralSale::class, 'associate_id');
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}

