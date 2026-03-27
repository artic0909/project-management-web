<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        'order_id', 'project_name', 'client_name', 'emails', 'phones',
        'company_name', 'starting_date', 'plan_name', 'payment_status',
        'username', 'password', 'no_of_mail_ids', 'mail_password',
        'domain_server_book', 'full_address',
        'domain_name', 'hosting_provider', 'cms_platform', 'cms_custom',
        'no_of_pages', 'website_payment_status', 'required_features',
        'reference_websites', 'last_update_date', 'client_feedback_summary',
        'internal_notes', 'project_status', 'project_start_date',
        'expected_delivery_date', 'actual_delivery_date',
        'project_price', 'advance_payment', 'remaining_amount',
        'financial_payment_status', 'invoice_number',
        'created_by', 'created_by_type'
    ];

    protected $casts = [
        'emails' => 'array',
        'phones' => 'array',
        'starting_date' => 'date',
        'last_update_date' => 'date',
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

    public function feedbacks()
    {
        return $this->hasMany(ClientFeedback::class);
    }

    public function createdBy()
    {
        return $this->morphTo('created_by', 'created_by_type', 'created_by');
    }
}
