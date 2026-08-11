<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Bitacora;
use App\Models\Departure;
use App\Models\Entries;
use App\Models\Payment;
use App\Models\Resident;
use App\Models\Roster;
use App\Models\Spent;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardApiController extends Controller
{
    public function metrics(Request $request)
    {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        $month = (int)$request->get('month', $currentMonth);
        $year = (int)$request->get('year', $currentYear);

        $today = Carbon::today();
        
        $totalResidents = Resident::count();

        // Filter metrics by selected month and year
        $totalPayments = Payment::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->sum('amount');

        $totalSpents = Spent::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->sum('amount');

        $totalRosters = Roster::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->sum('amount');

        $entriesMonth = Entries::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->count();

        $departuresMonth = Departure::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->count();

        $isCurrentMonthYear = ($month === $currentMonth && $year === $currentYear);
        $entriesToday = $isCurrentMonthYear ? Entries::whereDate('created_at', $today)->count() : $entriesMonth;
        $departuresToday = $isCurrentMonthYear ? Departure::whereDate('created_at', $today)->count() : $departuresMonth;

        $recentEntries = Entries::with('resident')
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->orderBy('id', 'desc')
            ->take(5)
            ->get();

        $recentDepartures = Departure::with('resident')
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->orderBy('id', 'desc')
            ->take(5)
            ->get();

        $recentBitacoras = Bitacora::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->orderBy('id', 'desc')
            ->take(5)
            ->get();

        $totalExpenses = (float)$totalSpents + (float)$totalRosters;
        $netBalance = (float)$totalPayments - $totalExpenses;

        return response()->json([
            'success' => true,
            'month' => $month,
            'year' => $year,
            'metrics' => [
                'total_residents' => $totalResidents,
                'total_payments' => (float)$totalPayments,
                'total_spents' => (float)$totalSpents,
                'total_rosters' => (float)$totalRosters,
                'total_expenses' => (float)$totalExpenses,
                'net_balance' => (float)$netBalance,
                'entries_today' => $entriesToday,
                'departures_today' => $departuresToday,
                'entries_month' => $entriesMonth,
                'departures_month' => $departuresMonth,
            ],
            'recent_entries' => $recentEntries,
            'recent_departures' => $recentDepartures,
            'recent_bitacoras' => $recentBitacoras,
        ]);
    }
}
