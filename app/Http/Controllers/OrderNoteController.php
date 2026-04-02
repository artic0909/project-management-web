<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderNote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderNoteController extends Controller
{
    private function getCurrentUser()
    {
        if (Auth::guard('admin')->check()) {
            return Auth::guard('admin')->user();
        } elseif (Auth::guard('sale')->check()) {
            return Auth::guard('sale')->user();
        }
        return Auth::user();
    }

    public function store(Request $request, Order $order)
    {
        $request->validate([
            'notes' => 'required|string',
        ]);

        $user = $this->getCurrentUser();

        OrderNote::create([
            'order_id' => $order->id,
            'notes' => $request->notes,
            'created_by' => $user->id,
            'created_by_type' => get_class($user),
            'updated_by' => $user->id,
            'updated_by_type' => get_class($user),
        ]);

        return redirect()->back()->with('success', 'Note added successfully!');
    }

    public function update(Request $request, OrderNote $note)
    {
        $request->validate([
            'notes' => 'required|string',
        ]);

        $user = $this->getCurrentUser();

        $note->update([
            'notes' => $request->notes,
            'updated_by' => $user->id,
            'updated_by_type' => get_class($user),
        ]);

        return redirect()->back()->with('success', 'Note updated successfully!');
    }

    public function destroy(OrderNote $note)
    {
        $note->delete();
        return redirect()->back()->with('success', 'Note deleted successfully!');
    }
}
