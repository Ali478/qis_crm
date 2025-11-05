<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

class CustomerController extends Controller
{
    public function index()
    {
        if (!session('logged_in')) {
            return redirect()->route('login');
        }

        $customers = DB::table('customers')
            ->leftJoin('shipments', 'customers.id', '=', 'shipments.customer_id')
            ->select('customers.*', DB::raw('COUNT(shipments.id) as shipment_count'))
            ->groupBy('customers.id')
            ->orderBy('customers.created_at', 'desc')
            ->get();

        return view('customers.index', compact('customers'));
    }

    public function create(Request $request)
    {
        if (!session('logged_in')) {
            return redirect()->route('login');
        }

        // Generate the next customer code for display
        $nextCustomerCode = $this->generateUniqueCustomerCode();

        // If AJAX request, return just the code
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'customer_code' => $nextCustomerCode
            ]);
        }

        return view('customers.create', compact('nextCustomerCode'));
    }

    public function store(Request $request)
    {
        if (!session('logged_in')) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You must be logged in to perform this action.'
                ], 401);
            }
            return redirect()->route('login');
        }

        // Basic validation
        $rules = [
            'email' => 'required|email',
            'phone' => 'required',
            'city' => 'required',
            'country' => 'required',
        ];

        // Conditional validation based on customer type
        if ($request->customer_type === 'company') {
            $rules['company_name'] = 'required';
        } else {
            $rules['fullname'] = 'required';
        }

        $validator = \Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }
            return redirect()
                ->route('customers.create')
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Generate unique customer code
            $customerCode = $this->generateUniqueCustomerCode();
            
            DB::table('customers')->insert([
                'branch_id' => session('branch_id', 1),
                'customer_code' => $customerCode,
                'customer_type' => $request->customer_type ?? 'company',
                'fullname' => $request->fullname,
                'company_name' => $request->company_name,
                'contact_person' => $request->contact_person,
                'representative_name' => $request->representative_name,
                'representative_number' => $request->representative_number,
                'email' => $request->email,
                'phone' => $request->phone,
                'wechat' => $request->wechat,
                'whatsapp' => $request->whatsapp,
                'address' => $request->address,
                'city' => $request->city,
                'country' => $request->country,
                'payment_terms' => $request->payment_terms ?? 'cash',
                'credit_limit' => $request->credit_limit ?? 0,
                'currency' => 'USD',
                'is_active' => true,
                'created_by' => session('user_id', 1),
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Check if this is an AJAX request
            if ($request->ajax()) {
                $newCustomer = DB::table('customers')->where('customer_code', $customerCode)->first();
                return response()->json([
                    'success' => true,
                    'message' => 'Customer added successfully',
                    'customer' => $newCustomer
                ]);
            }

            return redirect()->route('customers.index')->with('success', 'Customer added successfully');
        } catch (\Illuminate\Database\QueryException $e) {
            // Check if it's a duplicate entry error for customer_code
            if ($e->getCode() == 23000 && str_contains($e->getMessage(), 'customer_code')) {
                // Retry with a new unique code (keep trying until successful or max attempts)
                $maxRetries = 10;
                $retryCount = 0;
                $success = false;
                
                while ($retryCount < $maxRetries && !$success) {
                    try {
                        $customerCode = $this->generateUniqueCustomerCode();
                        DB::table('customers')->insert([
                            'branch_id' => session('branch_id', 1),
                            'customer_code' => $customerCode,
                            'customer_type' => $request->customer_type ?? 'company',
                            'fullname' => $request->fullname,
                            'company_name' => $request->company_name,
                            'contact_person' => $request->contact_person,
                            'representative_name' => $request->representative_name,
                            'representative_number' => $request->representative_number,
                            'email' => $request->email,
                            'phone' => $request->phone,
                            'wechat' => $request->wechat,
                            'whatsapp' => $request->whatsapp,
                            'address' => $request->address,
                            'city' => $request->city,
                            'country' => $request->country,
                            'payment_terms' => $request->payment_terms ?? 'cash',
                            'credit_limit' => $request->credit_limit ?? 0,
                            'currency' => 'USD',
                            'is_active' => true,
                            'created_by' => session('user_id', 1),
                            'created_at' => now(),
                            'updated_at' => now()
                        ]);
                        $success = true;
                        
                        // Check if this is an AJAX request
                        if ($request->ajax()) {
                            $newCustomer = DB::table('customers')->where('customer_code', $customerCode)->first();
                            return response()->json([
                                'success' => true,
                                'message' => 'Customer added successfully',
                                'customer' => $newCustomer
                            ]);
                        }
                        
                        return redirect()->route('customers.index')->with('success', 'Customer added successfully with code: ' . $customerCode);
                    } catch (\Illuminate\Database\QueryException $retryException) {
                        $retryCount++;
                        // If still duplicate, wait a tiny bit and try again with new code
                        if ($retryCount < $maxRetries) {
                            usleep(100000); // 0.1 second delay
                        }
                    }
                }
                
                // If all retries failed
                if (!$success) {
                    if ($request->ajax()) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Unable to generate a unique customer code after multiple attempts. Please try again or contact support.'
                        ], 500);
                    }
                    return redirect()
                        ->route('customers.create')
                        ->withInput()
                        ->with('error', 'Unable to generate a unique customer code after multiple attempts. Please try again or contact support.');
                }
            }
            
            // For other database errors
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'An error occurred while saving the customer. Please try again.'
                ], 500);
            }
            return redirect()
                ->route('customers.create')
                ->withInput()
                ->withErrors(['database' => 'An error occurred while saving the customer. Please try again.'])
                ->with('error', 'Failed to create customer. Please check your input and try again.');
        } catch (\Exception $e) {
            // Catch any other exceptions
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'An unexpected error occurred. Please try again.'
                ], 500);
            }
            return redirect()
                ->route('customers.create')
                ->withInput()
                ->withErrors(['general' => 'An unexpected error occurred. Please try again.'])
                ->with('error', 'Failed to create customer. Please try again.');
        }
    }

    private function generateUniqueCustomerCode()
    {
        // Get the latest customer ID
        $lastCustomer = DB::table('customers')->orderBy('id', 'desc')->first();
        $nextId = $lastCustomer ? ($lastCustomer->id + 1) : 1;
        
        // Generate customer code based on latest ID + 1
        $customerCode = 'CUST' . str_pad($nextId, 3, '0', STR_PAD_LEFT);
        
        // Keep checking and incrementing until we find a unique code
        $maxAttempts = 1000; // Prevent infinite loop
        $attempt = 0;
        
        while (DB::table('customers')->where('customer_code', $customerCode)->exists() && $attempt < $maxAttempts) {
            $nextId++;
            $customerCode = 'CUST' . str_pad($nextId, 3, '0', STR_PAD_LEFT);
            $attempt++;
        }
        
        // Fallback if all attempts failed (should never happen)
        if ($attempt >= $maxAttempts) {
            // Use timestamp-based code as ultimate fallback
            $customerCode = 'CUST' . date('Ymd') . rand(100, 999);
        }
        
        return $customerCode;
    }

    public function show($id)
    {
        if (!session('logged_in')) {
            return redirect()->route('login');
        }

        $customer = DB::table('customers')->where('id', $id)->first();

        if (!$customer) {
            return redirect()->route('customers.index')->with('error', 'Customer not found');
        }

        $shipments = DB::table('shipments')
            ->where('customer_id', $id)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        $invoices = DB::table('invoices')
            ->where('customer_id', $id)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('customers.show', compact('customer', 'shipments', 'invoices'));
    }

    public function edit($id)
    {
        if (!session('logged_in')) {
            return redirect()->route('login');
        }

        $customer = DB::table('customers')->where('id', $id)->first();

        if (!$customer) {
            return redirect()->route('customers.index')->with('error', 'Customer not found');
        }

        return view('customers.edit', compact('customer'));
    }

    public function update(Request $request, $id)
    {
        if (!session('logged_in')) {
            return redirect()->route('login');
        }

        $updateData = [
            'customer_type' => $request->customer_type ?? 'company',
            'fullname' => $request->fullname,
            'company_name' => $request->company_name,
            'contact_person' => $request->contact_person,
            'representative_name' => $request->representative_name,
            'representative_number' => $request->representative_number,
            'email' => $request->email,
            'phone' => $request->phone,
            'wechat' => $request->wechat,
            'whatsapp' => $request->whatsapp,
            'address' => $request->address,
            'city' => $request->city,
            'country' => $request->country,
            'payment_terms' => $request->payment_terms ?? 'cash',
            'credit_limit' => $request->credit_limit ?? 0,
            'is_active' => $request->is_active ?? true,
            'updated_at' => now()
        ];

        DB::table('customers')->where('id', $id)->update($updateData);

        return redirect()->route('customers.show', $id)->with('success', 'Customer updated successfully');
    }

    public function destroy($id)
    {
        if (!session('logged_in')) {
            return redirect()->route('login');
        }

        // Check if customer has shipments
        $hasShipments = DB::table('shipments')->where('customer_id', $id)->exists();

        if ($hasShipments) {
            return redirect()->route('customers.index')->with('error', 'Cannot delete customer with existing shipments');
        }

        DB::table('customers')->where('id', $id)->delete();

        return redirect()->route('customers.index')->with('success', 'Customer deleted successfully');
    }
}