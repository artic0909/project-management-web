<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        'order_id', 'project_code', 'project_name', 'client_name', 'first_name', 'last_name', 'emails', 'phones',
        'company_name', 'starting_date', 'plan_name', 'payment_status', 'payment_status_id',
        'username', 'password', 'no_of_mail_ids', 'mail_password',
        'domain_server_book', 'full_address', 'city', 'state', 'zip_code', 'domain_name', 'hosting_provider',
        'cms_platform', 'no_of_pages', 'cms_custom', 'required_features', 'extra_features',
        'reference_websites', 'website_payment_status', 'project_status', 'project_status_id',
        'project_start_date', 'expected_delivery_date', 'actual_delivery_date', 'order_date_create',
        'domain_provider_name', 'domain_renewal_price', 'hosting_provider_name', 'hosting_renewal_price', 'primary_domain_name',
        'financial_payment_status', 'invoice_number', 'created_by', 'created_by_type'
    ];

    protected $casts = [
        'emails' => 'array',
        'phones' => 'array',
        'starting_date' => 'date',
        'project_start_date' => 'date',
        'expected_delivery_date' => 'date',
        'actual_delivery_date' => 'date',
        'order_date_create' => 'date',
        'project_price' => 'decimal:2',
        'advance_payment' => 'decimal:2',
        'remaining_amount' => 'decimal:2',
        'domain_renewal_price' => 'decimal:2',
        'hosting_renewal_price' => 'decimal:2',
    ];

    protected static function booted()
    {
        static::creating(function ($project) {
            if (empty($project->project_code)) {
                $lastProject = self::orderBy('id', 'desc')->first();
                $lastId = $lastProject ? $lastProject->id : 0;
                $project->project_code = 'PROJ-' . str_pad($lastId + 1, 2, '0', STR_PAD_LEFT);
            }
        });
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function assignments()
    {
        return $this->hasMany(ProjectAssign::class);
    }

    public function developers()
    {
        return $this->belongsToMany(Developer::class, 'project_assigns', 'project_id', 'assigned_to');
    }

    public function salesPersons()
    {
        return $this->belongsToMany(Sale::class, 'project_sale_assigns', 'project_id', 'sale_id');
    }

    public function services()
    {
        return $this->belongsToMany(Service::class, 'project_service');
    }

    public function plans()
    {
        return $this->belongsToMany(Plan::class, 'project_plan');
    }

    public function sources()
    {
        return $this->belongsToMany(Source::class, 'project_source');
    }

    public function feedbacks()
    {
        return $this->hasMany(ClientFeedback::class);
    }

    public function projectStatus()
    {
        return $this->belongsTo(Status::class, 'project_status_id');
    }

    public function paymentStatus()
    {
        return $this->belongsTo(Status::class, 'payment_status_id');
    }

    public function createdBy()
    {
        return $this->morphTo(null, 'created_by_type', 'created_by');
    }

    public function tasks()
    {
        return $this->hasMany(ProjectTask::class);
    }

    public function taskAssignments()
    {
        return $this->hasMany(ProjectTaskAssign::class);
    }
}
