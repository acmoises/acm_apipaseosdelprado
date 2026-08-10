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
    public function metrics()
    {
        $today = Carbon::today();
        
        $totalResidents = Resident::count();
        $totalPayments = Payment::sum('amount');
        $totalSpents = Spent::sum('amount');
        $totalRosters = Roster::sum('amount');

        $entriesToday = Entries::whereDate('created_at', $today)->count();
        $departuresToday = Departure::whereDate('created_at', $today)->count();

        $recentEntries = Entries::with('resident')->orderBy('id', 'desc')->take(5)->get();
        $recentDepartures = Departure::with('resident')->orderBy('id', 'desc')->take(5)->get();
        $recentBitacoras = Bitacora::orderBy('id', 'desc')->take(5)->get();

        return response()->json([
            'success' => true,
            'metrics' => [
                'total_residents' => $totalResidents,
                'total_payments' => (float)$totalPayments,
                'total_spents' => (float)$totalSpents,
                'total_rosters' => (float)$totalRosters,
                'entries_today' => $entriesToday,
                'departures_today' => $departuresToday,
            ],
            'recent_entries' => $recentEntries,
            'recent_departures' => $recentDepartures,
            'recent_bitacoras' => $recentBitacoras,
        ]);
    }
}
