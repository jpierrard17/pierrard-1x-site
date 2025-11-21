<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
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

    /**
     * Get the user's settings.
     */
    public function settings(): HasMany
    {
        return $this->hasMany(Setting::class);
    }

    /**
     * Get the Hevy API key from settings.
     */
    public function getHevyApiKeyAttribute(): ?string
    {
        return $this->settings()->where('key', 'hevy_api_key')->first()?->value;
    }

    /**
     * Set the Hevy API key in settings.
     */
    public function setHevyApiKeyAttribute(?string $value): void
    {
        if ($value) {
            $this->settings()->updateOrCreate(
                ['key' => 'hevy_api_key'],
                ['value' => $value, 'type' => 'api_key']
            );
        } else {
            $this->settings()->where('key', 'hevy_api_key')->delete();
        }
    }
    /**
     * Get the Strava access token from settings.
     */
    public function getStravaAccessTokenAttribute(): ?string
    {
        return $this->settings()->where('key', 'strava_access_token')->value('value');
    }

    /**
     * Set the Strava access token in settings.
     */
    public function setStravaAccessTokenAttribute(?string $value): void
    {
        if ($value) {
            $this->settings()->updateOrCreate(
                ['key' => 'strava_access_token'],
                ['value' => $value, 'type' => 'token']
            );
        } else {
            $this->settings()->where('key', 'strava_access_token')->delete();
        }
    }

    /**
     * Get the Strava refresh token from settings.
     */
    public function getStravaRefreshTokenAttribute(): ?string
    {
        return $this->settings()->where('key', 'strava_refresh_token')->value('value');
    }

    /**
     * Set the Strava refresh token in settings.
     */
    public function setStravaRefreshTokenAttribute(?string $value): void
    {
        if ($value) {
            $this->settings()->updateOrCreate(
                ['key' => 'strava_refresh_token'],
                ['value' => $value, 'type' => 'token']
            );
        } else {
            $this->settings()->where('key', 'strava_refresh_token')->delete();
        }
    }
}
