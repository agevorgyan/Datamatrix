<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'phone', 'marketing_consent', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'marketing_consent' => 'boolean',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    public function labelSettings()
    {
        return $this->hasMany(LabelSetting::class);
    }

    public function defaultLabelSetting()
    {
        return $this->hasOne(LabelSetting::class)->where('is_default', true)->withDefault([
            'setting_name' => 'Default XP-356B 20x30mm',
            'width_mm' => 20.0,
            'height_mm' => 30.0,
            'margin_mm' => 1.0,
            'orientation' => 'portrait',
            'product_font_size' => 9,
            'product_font_bold' => true,
            'product_pos_x' => 1.5,
            'product_pos_y' => 2.0,
            'last5_font_size' => 11,
            'last5_font_bold' => true,
            'last5_pos_x' => 1.5,
            'last5_pos_y' => 7.0,
            'datamatrix_size' => 15.0,
            'datamatrix_pos_x' => 2.5,
            'datamatrix_pos_y' => 12.0,
            'is_default' => true,
        ]);
    }

    public function printJobs()
    {
        return $this->hasMany(PrintJob::class);
    }
}
