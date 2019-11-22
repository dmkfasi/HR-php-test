<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index() {
        // Might want to use chunk() instead...
        $orders = \App\Order::all();

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

        try {
          $order = \App\Order::findOrFail($id);
          $order->update([]);

          // As the task describes, send out
          // an email notification for extra credits
          if ((int)$order->status == $order::STATE_COMPLETED) {
            // TODO mail enqueue
          }

          // Updates product quantity within the pivot table
          // Although out of score of the task,
          // but since I have implement it anyways...
          foreach ($data['products'] as $k => $v) {
              $order->products()->updateExistingPivot(
                  $k,
                  [
                      'quantity' => $v
                  ]
              );
          }

          return back()->with('update_success', __('Order update successful.'));
        } catch (Illuminate\Database\QueryException $e) {
            // TODO Log error
            return back()->with('update_failure', __('Unable to update Order.'));
        }
    }
}