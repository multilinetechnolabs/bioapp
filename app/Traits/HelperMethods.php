<?php

namespace App\Traits;

use Auth;

trait HelperMethods
{
    public static function getTableName()
    {
        return with(new static)->getTable();
    }

    public function awsAssetsUrl($file_path)
    {
        $sourceUrl = env('APP_WEB_ASSETS_URL') ?: env('AWS_S3_URL');
        $normalizedPath = preg_replace('#/+#', '/', '/'.ltrim((string) $file_path, '/'));

        return rtrim((string) $sourceUrl, '/').$normalizedPath;
    }

    public function creator()
    {
        $creation_event = $this->audits()->where(['event' => 'created'])->first();
        if (!empty($creation_event)) {
            return $creation_event->user;
        }
    }

    public function deletable()
    {
        $actor = Auth::user();
        if (empty($actor)) {
            return false;
        }

        $condition = $this->user_id == $actor->id;
        $condition = $condition || $this->creator() == $actor;
        $condition = $condition || $actor->isAdmin();
        
        return $condition;
    }

    public function editable()
    {
        $actor = Auth::user();
        if (empty($actor)) {
            return false;
        }

        $condition = $this->user_id == $actor->id;
        $condition = $condition || $this->creator() == $actor;
        $condition = $condition || $actor->isAdmin();

        return $condition;
    }

    public function originator()
    {
        $audit_event = $this->audits()->latest()->first();
        if (!empty($audit_event)) {
            return $audit_event->user;
        }
    }

    public function getDeletableAttribute()
    {
        return $this->deletable();
    }

    public function getEditableAttribute()
    {
        return $this->editable();
    }
}
