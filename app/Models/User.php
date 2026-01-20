<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'psp_number',
        'taxi_driver_id',
        'user_type',
        'document_dashboard_path',
        'document_identity_path',
        'document_payment_receipt_path',
        'payment_type',
        'terms_agreed',
        'share_certificate_agreed',
        'application_status',
        'certificate_path',
        'certificate_number',
        'certificate_issued_date',
    ];

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
            'terms_agreed' => 'boolean',
            'share_certificate_agreed' => 'boolean',
            'certificate_issued_date' => 'datetime',
        ];
    }
}
