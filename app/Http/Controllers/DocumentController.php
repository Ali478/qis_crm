<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    public function upload(Request $request, $shipmentId)
    {
        if (!session('logged_in')) {
            return redirect()->route('login');
        }

        $request->validate([
            'document_type' => 'required|string',
            'document_file' => 'required|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:10240', // 10MB max
            'description' => 'nullable|string'
        ]);

        // Get the uploaded file
        $file = $request->file('document_file');

        // Generate unique filename
        $filename = time() . '_' . $file->getClientOriginalName();

        // Store file in public/documents/shipments/{shipment_id}/
        $path = $file->storeAs("documents/shipments/{$shipmentId}", $filename, 'public');

        // Save to database
        DB::table('shipment_documents')->insert([
            'shipment_id' => $shipmentId,
            'document_type' => $request->document_type,
            'document_name' => $file->getClientOriginalName(),
            'file_path' => $path,
            'file_type' => $file->getClientOriginalExtension(),
            'file_size' => $file->getSize(),
            'description' => $request->description,
            'status' => 'uploaded',
            'uploaded_by' => session('user_id', 1),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return redirect()->route('shipments.show', $shipmentId)->with('success', 'Document uploaded successfully');
    }

    public function download($id)
    {
        if (!session('logged_in')) {
            return redirect()->route('login');
        }

        $document = DB::table('shipment_documents')->where('id', $id)->first();

        if (!$document) {
            return redirect()->back()->with('error', 'Document not found');
        }

        $filePath = storage_path('app/public/' . $document->file_path);

        if (!file_exists($filePath)) {
            return redirect()->back()->with('error', 'File not found on server');
        }

        return response()->download($filePath, $document->document_name);
    }

    public function delete($id)
    {
        if (!session('logged_in')) {
            return redirect()->route('login');
        }

        $document = DB::table('shipment_documents')->where('id', $id)->first();

        if (!$document) {
            return redirect()->back()->with('error', 'Document not found');
        }

        // Delete file from storage
        if (Storage::disk('public')->exists($document->file_path)) {
            Storage::disk('public')->delete($document->file_path);
        }

        // Delete from database
        DB::table('shipment_documents')->where('id', $id)->delete();

        return redirect()->route('shipments.show', $document->shipment_id)->with('success', 'Document deleted successfully');
    }

    public function view($id)
    {
        if (!session('logged_in')) {
            return redirect()->route('login');
        }

        $document = DB::table('shipment_documents')->where('id', $id)->first();

        if (!$document) {
            return redirect()->back()->with('error', 'Document not found');
        }

        $filePath = storage_path('app/public/' . $document->file_path);

        if (!file_exists($filePath)) {
            return redirect()->back()->with('error', 'File not found on server');
        }

        return response()->file($filePath);
    }
}
