<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessEmails;
use App\Order;
use App\Partner;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * @return mixed
     */
    public function list()
    {
        $orders = Order::orderBy('created_at', 'desc')->paginate(20); //make pagination for our query
        foreach ($orders as $order) {
            $order->status = $order->switchStatus($order->status);
            $order->calculateTotal();
        }
        $pagesCount = (int) $orders->total() / $orders->count();
        return view('pages.list', compact('orders', 'pagesCount'));
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function edit($id)
    {
        $order = Order::find($id);
        $order->calculateTotal();
        $statuses = Order::getStatuses();
        $partners = Partner::pluck('name', 'id');
        return view('pages.edit', compact('order', 'statuses', 'partners'));
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, $id)
    {
        $order = Order::find($id);
        $data = $this->validate($request, [
            'client_email' => 'required',
            'status' => 'required',
            'partner' => 'required',
        ]);
        $data['id'] = $id;

        if ($order->updateOrder($data) && (int) $data['status'] === 20) {
            $this->notifyEmail($order);
        }
        return redirect('orders')->with('success', "ЗАказ успешно обновлен!!")->with('id', $id);
    }

    private function notifyEmail($order): void
    {
        ProcessEmails::dispatch($order);
    }
}
