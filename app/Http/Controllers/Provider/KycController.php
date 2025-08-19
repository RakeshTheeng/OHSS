<?php

namespace App\Http\Controllers\Provider;

use App\Http\Controllers\Controller;
use App\Models\KycDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class KycController extends Controller
{
    /**
     * Display KYC documents for the provider
     */
    public function index()
    {
        $provider = auth()->user();

        $kycDocuments = KycDocument::where('user_id', $provider->id)
                                  ->orderBy('created_at', 'desc')
                                  ->get();

        return view('provider.kyc.index', compact('kycDocuments'));
    }

    /**
     * Show the form for uploading a new KYC document
     */
    public function create()
    {
        return view('provider.kyc.create');
    }

    /**
     * Store a new KYC document
     */
    public function store(Request $request)
    {
        $provider = auth()->user();

        $request->validate([
            'document_type' => 'required|in:citizenship,passport,driving_license,voter_id',
            'document_number' => 'required|string|max:50',
            'document_file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120', // 5MB max
        ]);

        // Handle file upload
        $filePath = $request->file('document_file')->store('kyc-documents', 'public');

        // Get file information
        $file = $request->file('document_file');

        KycDocument::create([
            'user_id' => $provider->id,
            'document_type' => $request->document_type,
            'document_number' => $request->document_number,
            'file_path' => $filePath,
            'original_name' => $file->getClientOriginalName(),
            'file_size' => $file->getSize(),
            'status' => 'pending',
        ]);

        return redirect()->route('provider.kyc.index')
                        ->with('success', 'KYC document uploaded successfully! It will be reviewed by our team.');
    }

    /**
     * Display the specified KYC document
     */
    public function show(KycDocument $kyc)
    {
        // Ensure the document belongs to the authenticated provider
        if ($kyc->user_id !== auth()->id()) {
            abort(403);
        }

        return view('provider.kyc.show', compact('kyc'));
    }

    /**
     * Remove the specified KYC document
     */
    public function destroy(KycDocument $kyc)
    {
        // Ensure the document belongs to the authenticated provider
        if ($kyc->user_id !== auth()->id()) {
            abort(403);
        }

        // Only allow deletion of pending or rejected documents
        if ($kyc->status === 'approved') {
            return back()->with('error', 'Cannot delete approved documents.');
        }

        // Delete the file
        if ($kyc->file_path) {
            Storage::disk('public')->delete($kyc->file_path);
        }

        $kyc->delete();

        return back()->with('success', 'KYC document deleted successfully.');
    }
}
