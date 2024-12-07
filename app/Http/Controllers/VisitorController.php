<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Visitor;
use Carbon\Carbon;

class VisitorController extends Controller
{
    public function logVisitor(Request $request)
    {
        $visitorId = $request->input('visitorId');
        $ipAddress = $request->ip();
        $userAgent = $request->header('User-Agent');
        $today = Carbon::today();

        // Check if the visitor has already been recorded today
        $existingVisitor = Visitor::where('visitor_id', $visitorId)
            ->where('ip_address', $ipAddress)
            ->whereDate('last_visited_at', $today)
            ->first();

        if ($existingVisitor) {
            return response()->json(['message' => 'Visitor already logged today']);
        }

        // Log the visitor
        Visitor::create([
            'visitor_id' => $visitorId,
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
            'last_visited_at' => $today,
        ]);

        return response()->json(['message' => 'Visitor logged successfully']);
    }

    public function getVisitors()
    {
        $visitors = Visitor::all();
        return response()->json($visitors);
    }
}