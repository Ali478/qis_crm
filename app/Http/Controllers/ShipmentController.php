<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ShipmentController extends Controller
{
    public function index(Request $request)
    {
        if (!session('logged_in')) {
            return redirect()->route('login');
        }

        // Start building the query
        $query = DB::table('shipments')
            ->join('customers', 'shipments.customer_id', '=', 'customers.id')
            ->select('shipments.*', 'customers.company_name', 'customers.customer_code');

        // Apply filters
        if ($request->filled('status')) {
            $query->where('shipments.status', $request->status);
        }

        if ($request->filled('transport_mode')) {
            $query->where('shipments.transport_mode', $request->transport_mode);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('shipments.booking_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('shipments.booking_date', '<=', $request->date_to);
        }

        $shipments = $query->orderBy('shipments.created_at', 'desc')->get();

        // Calculate stats (use filtered query if filters are applied)
        $statsQuery = DB::table('shipments');
        
        if ($request->filled('status')) {
            $statsQuery->where('status', $request->status);
        }
        if ($request->filled('transport_mode')) {
            $statsQuery->where('transport_mode', $request->transport_mode);
        }
        if ($request->filled('date_from')) {
            $statsQuery->whereDate('booking_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $statsQuery->whereDate('booking_date', '<=', $request->date_to);
        }

        $stats = [
            'total' => $statsQuery->count(),
            'in_transit' => (clone $statsQuery)->where('status', 'in_transit')->count(),
            'delivered' => (clone $statsQuery)->where('status', 'delivered')->count(),
            'pending' => (clone $statsQuery)->whereIn('status', ['draft', 'pending', 'booked'])->count(),
        ];

        // Get filter values for pre-filling the form
        $filters = [
            'status' => $request->status ?? '',
            'transport_mode' => $request->transport_mode ?? '',
            'date_from' => $request->date_from ?? '',
            'date_to' => $request->date_to ?? '',
        ];

        // Get dynamic options for transport mode if available
        $dynamicOptions = $this->getDynamicOptions();

        return view('shipments.index', compact('shipments', 'stats', 'filters', 'dynamicOptions'));
    }

    public function show($id)
    {
        if (!session('logged_in')) {
            return redirect()->route('login');
        }

        $shipment = DB::table('shipments')
            ->join('customers', 'shipments.customer_id', '=', 'customers.id')
            ->select('shipments.*', 'customers.company_name', 'customers.customer_code', 'customers.email as customer_email', 'customers.phone as customer_phone')
            ->where('shipments.id', $id)
            ->first();

        if (!$shipment) {
            return redirect()->route('shipments.index')->with('error', 'Shipment not found');
        }

        $tracking = DB::table('shipment_tracking')
            ->where('shipment_id', $id)
            ->orderBy('timestamp', 'asc') // Oldest first for timeline display
            ->get();

        $documents = DB::table('shipment_documents')
            ->where('shipment_id', $id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('shipments.show', compact('shipment', 'tracking', 'documents'));
    }

    public function create()
    {
        if (!session('logged_in')) {
            return redirect()->route('login');
        }

        $customers = DB::table('customers')->where('is_active', true)->get();
        $branches = DB::table('branches')->where('is_active', true)->get();

        // Get dynamic options
        $dynamicOptions = $this->getDynamicOptions();

        return view('shipments.create', compact('customers', 'branches', 'dynamicOptions'));
    }

    private function getDynamicOptions()
    {
        $types = ['service_type', 'transport_mode', 'shipment_type', 'weight_unit', 'volume_unit'];
        $options = [];

        foreach ($types as $type) {
            $options[$type] = DB::table('dynamic_options')
                ->where('option_type', $type)
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->orderBy('label')
                ->get();
        }

        return $options;
    }

    public function store(Request $request)
    {
        if (!session('logged_in')) {
            return redirect()->route('login');
        }

        // Generate shipment number
        $lastShipment = DB::table('shipments')->orderBy('id', 'desc')->first();
        $nextNumber = $lastShipment ? ((int) substr($lastShipment->shipment_number, -6) + 1) : 1;
        $shipmentNumber = 'SH-' . date('Y') . '-' . str_pad($nextNumber, 6, '0', STR_PAD_LEFT);

        $shipmentId = DB::table('shipments')->insertGetId([
            'branch_id' => session('branch_id', 1),
            'shipment_number' => $shipmentNumber,
            'customer_id' => $request->customer_id,
            'origin_country' => $request->origin_country,
            'origin_city' => $request->origin_city,
            'destination_country' => $request->destination_country,
            'destination_city' => $request->destination_city,
            'service_type' => $request->service_type,
            'transport_mode' => $request->transport_mode,
            'shipment_type' => $request->shipment_type,
            'status' => 'booked',
            'booking_date' => now(),
            'total_weight' => $request->total_weight,
            'total_volume' => $request->total_volume,
            'freight_charge' => $request->freight_charge,
            'total_cost' => $request->total_cost,
            'created_by' => session('user_id', 1),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Add initial tracking
        DB::table('shipment_tracking')->insert([
            'shipment_id' => $shipmentId,
            'status' => 'booked',
            'location' => $request->origin_city . ', ' . $request->origin_country,
            'description' => 'Shipment booked and confirmed',
            'timestamp' => now(),
            'created_by' => session('user_id', 1),
            'created_at' => now()
        ]);

        return redirect()->route('shipments.index')->with('success', 'Shipment created successfully: ' . $shipmentNumber);
    }

    public function edit($id)
    {
        if (!session('logged_in')) {
            return redirect()->route('login');
        }

        $shipment = DB::table('shipments')->where('id', $id)->first();

        if (!$shipment) {
            return redirect()->route('shipments.index')->with('error', 'Shipment not found');
        }

        $customers = DB::table('customers')->where('is_active', true)->get();
        $branches = DB::table('branches')->where('is_active', true)->get();

        // Get dynamic options
        $dynamicOptions = $this->getDynamicOptions();

        return view('shipments.edit', compact('shipment', 'customers', 'branches', 'dynamicOptions'));
    }

    public function update(Request $request, $id)
    {
        if (!session('logged_in')) {
            return redirect()->route('login');
        }

        $updateData = [
            'customer_id' => $request->customer_id,
            'origin_country' => $request->origin_country,
            'origin_city' => $request->origin_city,
            'destination_country' => $request->destination_country,
            'destination_city' => $request->destination_city,
            'service_type' => $request->service_type,
            'transport_mode' => $request->transport_mode,
            'shipment_type' => $request->shipment_type,
            'status' => $request->status,
            'total_weight' => $request->total_weight,
            'total_volume' => $request->total_volume,
            'freight_charge' => $request->freight_charge,
            'total_cost' => $request->total_cost,
            'estimated_departure' => $request->estimated_departure,
            'estimated_arrival' => $request->estimated_arrival,
            'actual_delivery_date' => $request->actual_delivery_date,
            'updated_at' => now()
        ];

        DB::table('shipments')->where('id', $id)->update($updateData);

        // Add tracking entry if status changed
        $oldShipment = DB::table('shipments')->where('id', $id)->first();
        if ($request->status !== $oldShipment->status) {
            DB::table('shipment_tracking')->insert([
                'shipment_id' => $id,
                'status' => $request->status,
                'location' => $request->destination_city . ', ' . $request->destination_country,
                'description' => 'Status updated to: ' . ucfirst(str_replace('_', ' ', $request->status)),
                'timestamp' => now(),
                'created_by' => session('user_id', 1),
                'created_at' => now()
            ]);
        }

        return redirect()->route('shipments.show', $id)->with('success', 'Shipment updated successfully');
    }

    public function destroy($id)
    {
        if (!session('logged_in')) {
            return redirect()->route('login');
        }

        DB::table('shipments')->where('id', $id)->delete();
        DB::table('shipment_tracking')->where('shipment_id', $id)->delete();

        return redirect()->route('shipments.index')->with('success', 'Shipment deleted successfully');
    }
}