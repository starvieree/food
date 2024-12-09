<?php

namespace App\Http\Controllers\Admin;

use DateTime;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReportController extends Controller
{
    public function AdminAllReports()
    {
        return view('admin.backend.report.all_report');
    }

    public function AdminSearchByDate(Request $request)
    {
        $date = new DateTime($request->date);
        $formatDate = $date->format('d F Y');

        $orderDate = Order::where('order_date', $formatDate)->latest()->get();
        return view('admin.backend.report.search_by_date', compact('orderDate', 'formatDate'));
    }

    public function AdminSearchByMonth(Request $request)
    {
        $month = $request->month;
        $years = $request->year_name;

        $orderMonth = Order::where('order_month', $month)->where('order_year', $years)->latest()->get();
        return view('admin.backend.report.search_by_month', compact('orderMonth', 'month', 'years'));
    }

    public function AdminSearchByYear(Request $request)
    {
        $years = $request->year;

        $orderYear = Order::where('order_year', $years)->latest()->get();
        return view('admin.backend.report.search_by_year', compact('orderYear', 'years'));
    }
}
