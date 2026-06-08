<?php

namespace App\Http\Controllers;

use App\Exports\ServiceOrderReportExport;
use App\Models\Customer;
use App\Models\Mechanic;
use App\Models\ServiceOrder;
use App\Models\Sparepart;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $filters = $this->getFilterDates($request);
        $startDate = $filters['start_date'];
        $endDate = $filters['end_date'];
        $period = $filters['period'];

        // Card Stats
        $stats = $this->getStatsData($startDate, $endDate);

        // Chart Data
        $chartData = $this->getChartData($startDate, $endDate, $period);

        return view('dashboard.index', compact('stats', 'chartData', 'period', 'startDate', 'endDate'));
    }

    public function exportPdf(Request $request)
    {
        $filters = $this->getFilterDates($request);
        $startDate = $filters['start_date'];
        $endDate = $filters['end_date'];
        $period = $filters['period'];

        $stats = $this->getStatsData($startDate, $endDate);
        $serviceOrders = ServiceOrder::with(['customer', 'vehicle', 'mechanic'])
            ->whereBetween('service_date', [$startDate, $endDate])
            ->orderBy('service_date', 'asc')
            ->get();

        $pdf = Pdf::loadView('dashboard.pdf', compact('stats', 'serviceOrders', 'period', 'startDate', 'endDate'));
        return $pdf->stream('laporan-dashboard-' . $period . '.pdf');
    }

    public function exportExcel(Request $request)
    {
        $filters = $this->getFilterDates($request);
        $startDate = $filters['start_date'];
        $endDate = $filters['end_date'];
        $period = $filters['period'];

        return Excel::download(
            new ServiceOrderReportExport($startDate->toDateString(), $endDate->toDateString()),
            'laporan-dashboard-' . $period . '-' . now()->format('YmdHis') . '.xlsx'
        );
    }

    private function getFilterDates(Request $request)
    {
        $period = $request->input('period', 'month');
        $startDate = null;
        $endDate = null;

        switch ($period) {
            case 'today':
                $startDate = Carbon::today();
                $endDate = Carbon::today();
                break;
            case 'week':
                $startDate = Carbon::now()->startOfWeek();
                $endDate = Carbon::now()->endOfWeek();
                break;
            case 'month':
                $startDate = Carbon::now()->startOfMonth();
                $endDate = Carbon::now()->endOfMonth();
                break;
            case 'year':
                $startDate = Carbon::now()->startOfYear();
                $endDate = Carbon::now()->endOfYear();
                break;
            case 'custom':
                $startDate = Carbon::parse($request->input('start_date', Carbon::now()->startOfMonth()->toDateString()));
                $endDate = Carbon::parse($request->input('end_date', Carbon::now()->toDateString()));
                break;
            default:
                $startDate = Carbon::now()->startOfMonth();
                $endDate = Carbon::now()->endOfMonth();
                $period = 'month';
        }

        return [
            'start_date' => $startDate,
            'end_date' => $endDate,
            'period' => $period
        ];
    }

    // fungsi untuk mendapatkan data statistik utama seperti total pendapatan, jumlah order, jumlah order yang selesai, dan jumlah sparepart yang terjual berdasarkan periode waktu yang dipilih
    private function getStatsData($startDate, $endDate)
    {
        $revenue = ServiceOrder::whereBetween('service_date', [$startDate, $endDate])
            ->where('payment_status', 'paid')
            ->sum('grand_total');

        $totalOrders = ServiceOrder::whereBetween('service_date', [$startDate, $endDate])->count();

        $completedOrders = ServiceOrder::whereBetween('service_date', [$startDate, $endDate])
            ->whereIn('status', ['completed', 'paid', 'closed'])
            ->count();

        $partsSold = DB::table('service_order_details')
            ->join('service_orders', 'service_order_details.service_order_id', '=', 'service_orders.id')
            ->whereBetween('service_orders.service_date', [$startDate, $endDate])
            ->sum('service_order_details.quantity');

    //    fungsi untuk menghitung total customer, total mekanik aktif, dan sparepart dengan stok kurang dari 5
        $totalCustomers = Customer::count();
        $totalMechanics = Mechanic::where('is_active', 1)->count();
        $lowStockSpareparts = Sparepart::where('stock', '<', 5)->count();

        return [
            'revenue' => $revenue,
            'total_orders' => $totalOrders,
            'completed_orders' => $completedOrders,
            'parts_sold' => (int)$partsSold,
            'total_customers' => $totalCustomers,
            'total_mechanics' => $totalMechanics,
            'low_stock' => $lowStockSpareparts
        ];
    }

    // fungsi untuk mendapatkan data tren pendapatan dan jumlah order berdasarkan periode waktu yang dipilih
    private function getChartData($startDate, $endDate, $period)
    {
        
        if ($period === 'year' || ($period === 'custom' && $startDate->diffInDays($endDate) > 31)) {
            $trendRaw = DB::table('service_orders')
                ->select(DB::raw("DATE_FORMAT(service_date, '%Y-%m') as date_label"), DB::raw('SUM(grand_total) as revenue'), DB::raw('COUNT(*) as count'))
                ->whereBetween('service_date', [$startDate, $endDate])
                ->groupBy('date_label')
                ->orderBy('date_label', 'asc')
                ->get();

            $labels = [];
            $revenueSeries = [];
            $orderSeries = [];
            
            $current = $startDate->copy()->startOfMonth();
            while ($current <= $endDate) {
                $key = $current->format('Y-m');
                $labels[] = $current->format('M Y');
                $found = $trendRaw->firstWhere('date_label', $key);
                $revenueSeries[] = $found ? (float)$found->revenue : 0;
                $orderSeries[] = $found ? (int)$found->count : 0;
                $current->addMonth();
            }
        } else {
            $trendRaw = DB::table('service_orders')
                ->select(DB::raw('DATE(service_date) as date_label'), DB::raw('SUM(grand_total) as revenue'), DB::raw('COUNT(*) as count'))
                ->whereBetween('service_date', [$startDate, $endDate])
                ->groupBy('date_label')
                ->orderBy('date_label', 'asc')
                ->get();

            $labels = [];
            $revenueSeries = [];
            $orderSeries = [];

            $current = $startDate->copy();
            while ($current <= $endDate) {
                $key = $current->toDateString();
                $labels[] = $current->format('d M');
                $found = $trendRaw->firstWhere('date_label', $key);
                $revenueSeries[] = $found ? (float)$found->revenue : 0;
                $orderSeries[] = $found ? (int)$found->count : 0;
                $current->addDay();
            }
        }

// 2. Payment Method Distribution
        $paymentRaw = DB::table('service_orders')
            ->select('payment_method', DB::raw('count(*) as count'))
            ->whereBetween('service_date', [$startDate, $endDate])
            ->groupBy('payment_method')
            ->get();

        $paymentLabels = [];
        $paymentCounts = [];
        foreach ($paymentRaw as $item) {
            $paymentLabels[] = ucfirst($item->payment_method);
            $paymentCounts[] = (int)$item->count;
        }

        // fungsi untuk mendapatkan data mekanik dengan jumlah order terbanyak
        $mechanicRaw = DB::table('service_orders')
            ->join('mechanics', 'service_orders.mechanic_id', '=', 'mechanics.id')
            ->select('mechanics.name_mechanic as name', DB::raw('count(*) as count'))
            ->whereBetween('service_date', [$startDate, $endDate])
            ->groupBy('mechanics.id', 'mechanics.name_mechanic')
            ->orderBy('count', 'desc')
            ->get();

        $mechanicNames = [];
        $mechanicCounts = [];
        foreach ($mechanicRaw as $item) {
            $mechanicNames[] = $item->name;
            $mechanicCounts[] = (int)$item->count;
        }

        // 4. Top Services
        $servicesRaw = DB::table('service_order_services')
            ->join('service_orders', 'service_order_services.service_order_id', '=', 'service_orders.id')
            ->join('services', 'service_order_services.service_id', '=', 'services.id')
            ->select('services.complaint_name as name', DB::raw('sum(service_order_services.quantity) as qty'))
            ->whereBetween('service_orders.service_date', [$startDate, $endDate])
            ->groupBy('services.id', 'services.complaint_name')
            ->orderBy('qty', 'desc')
            ->limit(5)
            ->get();

        $topServiceNames = [];
        $topServiceQtys = [];
        foreach ($servicesRaw as $item) {
            $topServiceNames[] = $item->name;
            $topServiceQtys[] = (int)$item->qty;
        }

        return [
            'trend' => [
                'labels' => $labels,
                'revenues' => $revenueSeries,
                'orders' => $orderSeries,
            ],
            'payments' => [
                'labels' => $paymentLabels,
                'counts' => $paymentCounts,
            ],
            'mechanics' => [
                'names' => $mechanicNames,
                'counts' => $mechanicCounts,
            ],
            'services' => [
                'names' => $topServiceNames,
                'quantities' => $topServiceQtys,
            ],
        ];
    }
}
