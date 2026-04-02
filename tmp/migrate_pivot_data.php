<?php

use App\Models\Lead;
use App\Models\Order;
use App\Models\Project;

echo "Migrating Leads...\n";
Lead::chunk(100, function ($leads) {
    foreach ($leads as $lead) {
        if (!empty($lead->service_id)) {
            $lead->services()->syncWithoutDetaching([$lead->service_id]);
        }
        if (!empty($lead->source_id)) {
            $lead->sources()->syncWithoutDetaching([$lead->source_id]);
        }
    }
});

echo "Migrating Orders...\n";
Order::chunk(100, function ($orders) {
    foreach ($orders as $order) {
        if (!empty($order->service_id)) {
            $order->services()->syncWithoutDetaching([$order->service_id]);
        }
        // Orders table doesn't have source_id column
    }
});

echo "Migrating Projects...\n";
Project::chunk(100, function ($projects) {
    foreach ($projects as $project) {
        if (!empty($project->service_id)) {
            $project->services()->syncWithoutDetaching([$project->service_id]);
        }
        if (!empty($project->source_id)) {
            $project->sources()->syncWithoutDetaching([$project->source_id]);
        }
    }
});

echo "Data migration completed successfully.\n";
