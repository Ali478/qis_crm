<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    public function index()
    {
        if (!session('logged_in')) {
            return redirect()->route('login');
        }

        $invoices = DB::table('invoices')
            ->join('customers', 'invoices.customer_id', '=', 'customers.id')
            ->leftJoin('shipments', 'invoices.shipment_id', '=', 'shipments.id')
            ->select(
                'invoices.*',
                'customers.company_name',
                'customers.customer_code',
                'shipments.shipment_number'
            )
            ->orderBy('invoices.created_at', 'desc')
            ->get();

        $stats = [
            'total_invoices' => DB::table('invoices')->count(),
            'total_amount' => DB::table('invoices')->sum('total_amount'),
            'paid_amount' => DB::table('invoices')->sum('paid_amount'),
            'pending_amount' => DB::table('invoices')->sum('balance_amount'),
        ];

        return view('invoices.index', compact('invoices', 'stats'));
    }

    public function create()
    {
        if (!session('logged_in')) {
            return redirect()->route('login');
        }

        $customers = DB::table('customers')->where('is_active', true)->get();
        $shipments = DB::table('shipments')
            ->leftJoin('invoices', 'shipments.id', '=', 'invoices.shipment_id')
            ->whereNull('invoices.id')
            ->select('shipments.*')
            ->get();

        return view('invoices.create', compact('customers', 'shipments'));
    }

    public function store(Request $request)
    {
        if (!session('logged_in')) {
            return redirect()->route('login');
        }

        // Generate invoice number
        $lastInvoice = DB::table('invoices')->orderBy('id', 'desc')->first();
        $nextNumber = $lastInvoice ? ((int) substr($lastInvoice->invoice_number, -4) + 1) : 1;
        $invoiceNumber = 'INV-' . date('Y') . '-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

        $subtotal = $request->subtotal ?? 0;
        $taxAmount = $subtotal * ($request->tax_rate ?? 0) / 100;
        $totalAmount = $subtotal + $taxAmount - ($request->discount_amount ?? 0);

        $invoiceId = DB::table('invoices')->insertGetId([
            'branch_id' => session('branch_id', 1),
            'invoice_number' => $invoiceNumber,
            'customer_id' => $request->customer_id,
            'shipment_id' => $request->shipment_id,
            'invoice_date' => $request->invoice_date ?? now(),
            'due_date' => $request->due_date,
            'subtotal' => $subtotal,
            'tax_rate' => $request->tax_rate ?? 0,
            'tax_amount' => $taxAmount,
            'discount_amount' => $request->discount_amount ?? 0,
            'total_amount' => $totalAmount,
            'paid_amount' => 0,
            'balance_amount' => $totalAmount,
            'currency' => 'USD',
            'status' => 'draft',
            'notes' => $request->notes,
            'created_by' => session('user_id', 1),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return redirect()->route('invoices.index')->with('success', 'Invoice created successfully: ' . $invoiceNumber);
    }

    public function show($id)
    {
        if (!session('logged_in')) {
            return redirect()->route('login');
        }

        $invoice = DB::table('invoices')
            ->join('customers', 'invoices.customer_id', '=', 'customers.id')
            ->leftJoin('shipments', 'invoices.shipment_id', '=', 'shipments.id')
            ->select(
                'invoices.*',
                'customers.company_name',
                'customers.contact_person',
                'customers.address as customer_address',
                'customers.city as customer_city',
                'customers.country as customer_country',
                'customers.email as customer_email',
                'customers.phone as customer_phone',
                'shipments.shipment_number'
            )
            ->where('invoices.id', $id)
            ->first();

        if (!$invoice) {
            return redirect()->route('invoices.index')->with('error', 'Invoice not found');
        }

        $invoice_items = DB::table('invoice_items')->where('invoice_id', $id)->get();
        $payments = DB::table('payments')->where('invoice_id', $id)->orderBy('payment_date', 'desc')->get();

        return view('invoices.show', compact('invoice', 'invoice_items', 'payments'));
    }

    public function edit($id)
    {
        if (!session('logged_in')) {
            return redirect()->route('login');
        }

        $invoice = DB::table('invoices')->where('id', $id)->first();

        if (!$invoice) {
            return redirect()->route('invoices.index')->with('error', 'Invoice not found');
        }

        $customers = DB::table('customers')->where('is_active', true)->get();
        $shipments = DB::table('shipments')
            ->leftJoin('invoices', function($join) use ($id) {
                $join->on('shipments.id', '=', 'invoices.shipment_id')
                     ->where('invoices.id', '!=', $id);
            })
            ->whereNull('invoices.id')
            ->select('shipments.*')
            ->get();

        return view('invoices.edit', compact('invoice', 'customers', 'shipments'));
    }

    public function update(Request $request, $id)
    {
        if (!session('logged_in')) {
            return redirect()->route('login');
        }

        $subtotal = $request->subtotal ?? 0;
        $taxAmount = $subtotal * ($request->tax_rate ?? 0) / 100;
        $totalAmount = $subtotal + $taxAmount - ($request->discount_amount ?? 0);

        // Get current invoice to preserve paid_amount
        $currentInvoice = DB::table('invoices')->where('id', $id)->first();
        $paidAmount = $currentInvoice->paid_amount ?? 0;

        $updateData = [
            'customer_id' => $request->customer_id,
            'shipment_id' => $request->shipment_id,
            'invoice_date' => $request->invoice_date,
            'due_date' => $request->due_date,
            'subtotal' => $subtotal,
            'tax_rate' => $request->tax_rate ?? 0,
            'tax_amount' => $taxAmount,
            'discount_amount' => $request->discount_amount ?? 0,
            'total_amount' => $totalAmount,
            'balance_amount' => $totalAmount - $paidAmount,
            'status' => $request->status,
            'notes' => $request->notes,
            'updated_at' => now()
        ];

        DB::table('invoices')->where('id', $id)->update($updateData);

        return redirect()->route('invoices.show', $id)->with('success', 'Invoice updated successfully');
    }

    public function destroy($id)
    {
        if (!session('logged_in')) {
            return redirect()->route('login');
        }

        // Check if invoice has payments
        $hasPayments = DB::table('payments')->where('invoice_id', $id)->exists();

        if ($hasPayments) {
            return redirect()->route('invoices.index')->with('error', 'Cannot delete invoice with existing payments');
        }

        DB::table('invoices')->where('id', $id)->delete();
        DB::table('invoice_items')->where('invoice_id', $id)->delete();

        return redirect()->route('invoices.index')->with('success', 'Invoice deleted successfully');
    }
}