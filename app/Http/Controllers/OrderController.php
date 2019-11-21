<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index() {
        $order = new \App\Order();
        $orders = $order->all();

        if (\View::exists('orders.list')) {
            return view('orders.list')
                ->with('orders', $orders);
        } else {
            // TODO Log error
            abort(404, __('No suitable view blade found.'));
        }
    }

    public function edit(Request $r) {
        $order = \App\Order::findOrFail($r->id);
        $partners = \App\Partner::all();

        if (\View::exists('orders.edit')) {
            return view('orders.edit')
                ->with('order', $order)
                ->with('partners', $partners);
        } else {
            // TODO Log error
            abort(404, __('No suitable view blade found.'));
        }
    }

    public function update(Request $r, $id) {
        $data = $r->validate([
            'client_email' => 'required',
            'partner_id' => 'required',
            'status' => 'required',
            'products' => ''
        ]);

        $order = \App\Order::findOrFail($id);
        $order->update($data);

        foreach ($data['products'] as $k => $v) {
            $order->products()->updateExistingPivot(
                $k,
                [
                    'quantity' => $v
                ]
            );
        }

        return back()->with('update_success', __('Order update successful.'));
    }
}