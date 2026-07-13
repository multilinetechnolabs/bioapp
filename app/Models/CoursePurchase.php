<?php

namespace App\Models;

use Watson\Validating\ValidatingTrait;

class CoursePurchase extends Base
{
    use ValidatingTrait;

    protected $fillable = [
        'user_id', 'course_id', 'freemius_subscription_id', 'status', 'starts_at', 'ends_at', 'cancelled_at',
    ];

    protected $rules = [
        'course_id' => 'required|integer|exists:courses,id',
        'user_id' => 'required|integer|exists:users,id',
        'starts_at' => 'nullable|date|before_or_equal:ends_at',
        'ends_at' => 'nullable|date|after_or_equal:starts_at',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    public static function rules($id = null)
    {
        return (new static)->rules;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    protected $appends = ['course', 'user'];

    public function getCourseAttribute()
    {
        return $this->course()->first();
    }

    public function getUserAttribute()
    {
        return $this->user()->first();
    }

    public function payment()
    {
        return $this->morphOne(Payment::class, 'resource');
    }

    // Named isCurrentlyValid() (not isValid()) deliberately — Watson\Validating\ValidatingTrait
    // defines its own isValid() that it calls internally before every save(). Reusing that name
    // here silently overrides the trait's real validation check with this expiry check instead,
    // which breaks create()/update() for any record whose ends_at is in the past.
    //
    // 'cancelled' only means it won't renew — same as app Subscription and Freemius's
    // own license behavior, access holds until ends_at either way.
    public function isCurrentlyValid(): bool
    {
        return in_array($this->status, ['active', 'cancelled'], true)
            && !empty($this->ends_at) && $this->ends_at->isFuture();
    }

    public static function userHasAccess(?int $userId, ?int $courseId): bool
    {
        if (!$userId || !$courseId) {
            return false;
        }

        return self::where('user_id', $userId)
            ->where('course_id', $courseId)
            ->whereIn('status', ['active', 'cancelled'])
            ->where('ends_at', '>=', now())
            ->exists();
    }

    /**
     * Any currently-valid course purchase for this user, regardless of which course
     * (there's only one course today, but this stays correct if that ever changes).
     */
    public static function userHasAnyAccess(?int $userId): bool
    {
        if (!$userId) {
            return false;
        }

        return self::where('user_id', $userId)
            ->whereIn('status', ['active', 'cancelled'])
            ->where('ends_at', '>=', now())
            ->exists();
    }
}
