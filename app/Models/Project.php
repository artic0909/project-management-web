<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        'order_id', 'project_name', 'client_name', 'emails', 'phones',
        'company_name', 'starting_date', 'plan_name', 'payment_status', 'payment_status_id',
        'username', 'password', 'no_of_mail_ids', 'mail_password',
        'domain_server_book', 'full_address', 'domain_name', 'hosting_provider',
        'cms_platform', 'no_of_pages', 'cms_custom', 'required_features',
        'reference_websites', 'website_payment_status', 'project_status', 'project_status_id',
        'project_start_date', 'expected_delivery_date', 'actual_delivery_date',
        'financial_payment_status', 'invoice_number', 'order_id', 'created_by', 'created_by_type'
    ];

    protected $casts = [
        'emails' => 'array',
        'phones' => 'array',
        'starting_date' => 'date',
        'project_start_date' => 'date',
        'expected_delivery_date' => 'date',
        'actual_delivery_date' => 'date',
        'project_price' => 'decimal:2',
        'advance_payment' => 'decimal:2',
        'remaining_amount' => 'decimal:2',
    ];

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
}
