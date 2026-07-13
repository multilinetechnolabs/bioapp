<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

use Laravel\Passport\HasApiTokens;

use OwenIt\Auditing\Contracts\Auditable;

use Spatie\Permission\Traits\HasRoles;

use Carbon\Carbon;

use Watson\Validating\Injectors\UniqueWithInjector;
use Watson\Validating\ValidatingTrait;

use App\Traits\HelperMethods;
use Storage;
use Illuminate\Support\Arr;

class User extends Authenticatable implements Auditable, MustVerifyEmail
{
    use HasApiTokens, HasRoles, Notifiable;

    use \OwenIt\Auditing\Auditable;

    use ValidatingTrait, UniqueWithInjector;

    use HelperMethods;

    protected $auditStrict = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'business', 'location', 'privacy', 'company_name', 'first_name',
        'last_name', 'phone_no', 'fax_no', 'alternate_email', 'billing_title', 'address', 'country', 'zip',
        'age', 'logo', 'profile_picture', 'username'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token'
    ];

    public static function boot()
    {
        parent::boot();

        static::deleting(function ($user) {
            foreach ($user->friends_as_requester()->get() as $friend) {
                $friend->delete();
            }

            foreach ($user->friends_as_request_me()->get() as $friend) {
                $friend->delete();
            }

            foreach ($user->subscriptions()->get() as $subscription) {
                $subscription->delete();
            }
        });
    }
    
    protected $rules = [
        'name' => 'required|string|max:100|unique_with:users,username,email',
        'email' => 'required|email|max:100|unique_with:users,username',
        'username' => 'required|string|min:8|unique:users,username',
        'privacy' => 'nullable|boolean',
        'business' => 'nullable|string|max:191',
        'location' => 'nullable|string|max:191',
        'company_name' => 'nullable|string',
        'first_name' => 'nullable|string|max:50',
        'last_name' => 'nullable|string|max:50',
        'phone_no' => 'nullable|string|max:50',
        'fax_no' => 'nullable|string|max:50',
        'alternate_email' => 'nullable|string|max:50',
        'billing_title' => 'nullable|string|max:50',
        'address' => 'nullable|string',
        'country' => 'nullable|string',
        'zip' => 'nullable|integer',
        'age'  => 'nullable|integer',
        'logo' => 'nullable|string|max:191',
        'profile_picture' => 'nullable|string|max:191'
    ];

    public static function rules($id = null)
    {
        return (new static)->rules;
    }

    public function bookmarks()
    {
        return $this->hasMany(Bookmark::class);
    }

    public function friends_as_requester()
    {
        return $this->hasMany(User\Friend::class);
    }

    public function friends_as_request_me()
    {
        return $this->hasMany(User\Friend::class, 'friend_id', 'id');
    }

    public function friends()
    {
        $friends = \App\Models\User\Friend::where('accepted', true)->where(function ($query) {
            return $query->where('user_id', $this->id)->orWhere('friend_id', $this->id);
        });
        return $friends;
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function plans()
    {
        return $this->belongsToMany(Plan::class, 'subscriptions');
    }

    public function posts()
    {
        return $this->hasMany(Post::class, 'post_author');
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function courseSubscriptions()
    {
        return $this->hasMany(CoursePurchase::class);
    }

    public function currentSubscription()
    {
        // Access is gated by the paid period (ends_at), which mirrors the Freemius
        // license expiration. A 'cancelled' subscription only means it won't renew,
        // so it must keep access until ends_at — same as Freemius keeps the license
        // active until expiration.
        return $this->subscriptions
            ->whereIn('status', ['active', 'cancelled'])
            ->where('ends_at', '>=', \Carbon\Carbon::now())
            ->first();
    }

    public function currentCoursePurchase()
    {
        return $this->courseSubscriptions
            ->whereIn('status', ['active', 'cancelled'])
            ->where('ends_at', '>=', \Carbon\Carbon::now())
            ->first();
    }

    // TESTING SITE ONLY — see BYPASS_EMAIL_VERIFICATION in config/app.php. When unset
    // (the default), this behaves exactly like Laravel's normal hasVerifiedEmail().
    // When enabled, every user is treated as already verified: this also stops
    // Laravel's own SendEmailVerificationNotification listener from firing at all
    // (it already checks hasVerifiedEmail() before sending), so registration no
    // longer breaks on environments where outgoing mail isn't working. Must never
    // be set on the live production site — see config/app.php for why.
    public function hasVerifiedEmail()
    {
        if (config('app.bypass_email_verification')) {
            return true;
        }

        return ! is_null($this->email_verified_at);
    }

    // App-wide feature access (Body Scan, Chakra Scan, BioConnect, Data Cache, Media,
    // Playlist, Posts, Scan Sessions — everything behind the `subscriber` middleware).
    // Either a valid app Subscription OR a valid course purchase unlocks all of it.
    // This is intentionally NOT the same gate as course access itself — a plain app
    // Subscription must never unlock the course; only CoursePaymentGate controls that,
    // checking CoursePurchase alone.
    public function hasValidSubscription()
    {
        if ($this->isAdmin()) {
            return true;
        }

        if (! empty($this->currentSubscription())) {
            return true;
        }

        return ! empty($this->currentCoursePurchase());
    }

    public function friendRequests()
    {
        $friends = \App\Models\User\Friend::where('accepted', false)->where('friend_id', $this->id);
        return $friends;
    }

    public function friendIds()
    {
        if (empty($this->friends())) {
            return [];
        }
        $ids = [];
        array_push($ids, $this->friends()->get()->pluck('friend_id'));
        array_push($ids, $this->friends()->get()->pluck('user_id'));
        return Arr::flatten($ids);
    }

    public function requestedFriendIds()
    {
        if (empty($this->friendRequests())) {
            return [];
        }
        return $this->friendRequests()->get()->pluck('friend_id');
    }
    
    public function isAdmin()
    {
        return $this->hasRole('administrator');
    }

    public function isBlogger()
    {
        return $this->hasRole('blogger');
    }

    public function isPractitioner()
    {
        return $this->hasRole('practitioner');
        ;
    }

    public function isTherapist()
    {
        return $this->hasRole('therapist');
    }

    public function awsAssetsUrl($file_path)
    {
        $source_url = env('APP_WEB_ASSETS_URL') ?? env('AWS_S3_URL');
        return $source_url.$file_path;
    }

    public function logoUrl()
    {
        $partial_url = "users/uid-".$this->id."/logos/".$this->logo;
        if (!empty($this->logo) && \Storage::exists($partial_url)) {
            return $this->awsAssetsUrl('/'.$partial_url);
        } else {
            return asset('/images/iconimages/file_not_found.png');
        }
    }

    public function profilePictureUrl()
    {
        $partial_url = "users/uid-".$this->id."/profile_pictures/".$this->profile_picture;
        if (!empty($this->profile_picture) && \Storage::exists($partial_url)) {
            return $this->awsAssetsUrl('/'.$partial_url);
        } else {
            return asset('/images/iconimages/load.png');
        }
    }

    public function apiToken($tokenName = '')
    {
        if ($tokenName == '') {
            $tokenName = $this->email;
        }
        return $this->createToken($tokenName)->accessToken;
    }

    public function type()
    {
        if (empty($this->roles)) {
            return;
        }

        return $this->roles->first()['name'];
    }

    protected $appends = ['type', 'profilePictureUrl', 'logoUrl', 'friendIds', 'requestedFriendIds',
                          'deletable', 'editable'];

    public function getTypeAttribute()
    {
        return $this->type();
    }

    public function getProfilePictureUrlAttribute()
    {
        return $this->profilePictureUrl();
    }

    public function getLogoUrlAttribute()
    {
        return $this->logoUrl();
    }

    public function getFriendIdsAttribute()
    {
        return $this->friendIds();
    }

    public function getRequestedFriendIdsAttribute()
    {
        return $this->requestedFriendIds();
    }
}
