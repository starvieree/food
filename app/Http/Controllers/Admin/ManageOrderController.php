<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ManageOrderController extends Controller
{
    public function PendingOrder()
    {
        $allData = Order::where('status', 'Pending')->orderBy('id', 'desc')->get();
        return view('admin.backend.order.pending_order', compact('allData'));
    }

    public function ConfirmOrder()
    {
        $allData = Order::where('status', 'confirm')->orderBy('id', 'desc')->get();
        return view('admin.backend.order.confirm_order', compact('allData'));
    }

    public function ProcessingOrder()
    {
        $allData = Order::where('status', 'processing')->orderBy('id', 'desc')->get();
        return view('admin.backend.order.processing_order', compact('allData'));
    }

    public function DeliveredOrder()
    {
        $allData = Order::where('status', 'deliverd')->orderBy('id', 'desc')->get();
        return view('admin.backend.order.delivered_order', compact('allData'));
    }

    public function AdminOrderDetails($id)
    {
        $order = Order::with('user')->where('id', $id)->first();
        $orderItem = OrderItem::with('product')->where('order_id', $id)->orderBy('id', 'desc')->get();
        $totalPrice = 0;

        foreach ($orderItem as $item) {
            $totalPrice += $item->price * $item->qty;
        }

        return view('admin.backend.order.admin_order_details', compact('order', 'orderItem', 'totalPrice'));
    }

    public function PendingToConfirm($id)
    {
        Order::find($id)->update(['status' => 'confirm']);

        $notification = array(
            'message' => 'Order Confirm Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('confirm.order')->with($notification);
    }

    public function ConfirmToProcessing($id)
    {
        Order::find($id)->update(['status' => 'processing']);

        $notification = array(
            'message' => 'Order Processing Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('processing.order')->with($notification);
    }

    public function ProcessingToDiliverd($id)
    {
        Order::find($id)->update(['status' => 'deliverd']);

        $notification = array(
            'message' => 'Order Processing Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('delivered.order')->with($notification);
    }

    public function AllClientOrders()
    {
        $clientId = Auth::guard('client')->id();
        $orderItemGroupData = OrderItem::with(['product', 'order'])->where('client_id', $clientId)
            ->orderBy('order_id', 'desc')
            ->get()
            ->groupBy('order_id');

        return view('client.backend.order.all_orders', compact('orderItemGroupData'));
    }

    public function ClientOrderDetails($id)
    {
        $order = Order::with('user')->where('id', $id)->first();
        $orderItem = OrderItem::with('product')->where('order_id', $id)->orderBy('id', 'desc')->get();
        $totalPrice = 0;

        foreach ($orderItem as $item) {
            $totalPrice += $item->price * $item->qty;
        }

        return view('client.backend.order.client_order_details', compact('order', 'orderItem', 'totalPrice'));
    }

    public function UserOrderList()
    {
        $userId = Auth::user()->id;
        $allUserOrder = Order::where('user_id', $userId)->orderBy('id', 'desc')->get();

        return view('frontend.dashboard.order.order_list', compact('allUserOrder'));
    }

    public function UserOrderDetails($id)
    {
        $cid = Auth::guard('client')->id();
        $order = Order::with('user')->where('id', $id)->where('user_id', Auth::id())->first();
        $orderItem = OrderItem::with('product')->where('order_id', $id)->where('client_id', $cid)->orderBy('id', 'desc')->get();

        $totalPrice = 0;

        foreach ($orderItem as $item) {
            $totalPrice += $item->price * $item->qty;
        }

        return view('frontend.dashboard.order.order_details', compact('order', 'orderItem', 'totalPrice'));
    }

    public function UserInvoiceDownload($id)
    {
        $order = Order::with('user')->where('id', $id)->where('user_id', Auth::id())->first();
        $orderItem = OrderItem::with('product')->where('order_id', $id)->orderBy('id', 'desc')->get();
        $totalPrice = 0;

        foreach ($orderItem as $item) {
            $totalPrice += $item->price * $item->qty;
        }

        $pdf = Pdf::loadView('frontend.dashboard.order.invoice_download', compact('order', 'orderItem', 'totalPrice'))->setPaper('a4')->setOption([
            'tempDir' => public_path(),
            'chroot' => public_path(),
        ]);

        return $pdf->download('invoice.pdf');
    }
}
