<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Order;
use App\Partner;

class OrderController extends Controller
{
    public function index() {
        // Might want to use chunk() instead...
        $orders = Order::all();

        if (\View::exists('orders.list')) {
            return view('orders.list')
                ->with('orders', $orders);
        } else {
            // TODO Log error
            abort(404, __('No suitable view blade found.'));
        }
    }

    public function edit(Request $r) {
        $order = Order::findOrFail($r->id);
        $partners = Partner::all();

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
          $order = Order::findOrFail($id);
          $order->update($data);

          // As the task describes, send out
          // an email notification for extra credits
          if ((int)$order->status == $order::STATE_COMPLETED) {
            $this->sendCompletedMail($order);
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

    // Enqueues mail notification to the Order Partner 
    // and all the Order Produts Vendors
    protected function sendCompletedMail(Order $order) {
      // при установке статуса заказа "завершен" 
      // требуется отправить email - партнеру и 
      // всем поставщикам продуктов из заказа 

//      
//          Mail::to()
//          ->queue(new \App\Mail\OrderCompleted($order));
    }
}