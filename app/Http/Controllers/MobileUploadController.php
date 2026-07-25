<?php

namespace App\Http\Controllers;

use App\Models\MobileUpload;
use App\Models\InvoiceItem;
use App\Services\FileService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class MobileUploadController extends Controller
{
    protected $fileService;

    public function __construct(FileService $fileService)
    {
        $this->fileService = $fileService;
    }

    public function init(Request $request)
    {
        $token = Str::random(32);
        
        $invoiceItemId = $request->input('invoice_item_id');
        if (!is_numeric($invoiceItemId)) {
            $invoiceItemId = null;
        }

        $mobileUpload = MobileUpload::create([
            'token' => $token,
            'invoice_item_id' => $invoiceItemId,
            'status' => 'pending',
        ]);

        return response()->json([
            'token' => $token,
            'url' => route('mobile.upload.show', ['token' => $token]),
        ]);
    }

    public function status($token)
    {
        $mobileUpload = MobileUpload::with('file')->where('token', $token)->firstOrFail();

        return response()->json([
            'status' => $mobileUpload->status,
            'file' => $mobileUpload->file ? [
                'id' => $mobileUpload->file->id,
                'url' => $mobileUpload->file->url,
                'name' => $mobileUpload->file->name,
            ] : null,
        ]);
    }

    public function mobileShow($token)
    {
        $mobileUpload = MobileUpload::where('token', $token)->firstOrFail();
        return view('mobile-upload', compact('mobileUpload'));
    }

    public function mobileStore(Request $request, $token)
    {
        $mobileUpload = MobileUpload::where('token', $token)->firstOrFail();

        if ($mobileUpload->status === 'completed') {
            return response()->json(['message' => 'Esta carga ya ha sido completada.'], 400);
        }

        $request->validate([
            'file' => 'required|image|max:10240', // max 10MB
        ]);

        try {
            $file = $this->fileService->create($request);

            if ($file instanceof \Illuminate\Http\JsonResponse) {
                return $file;
            }

            $mobileUpload->update([
                'file_id' => $file->id,
                'status' => 'completed',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Archivo subido correctamente.',
                'file' => [
                    'id' => $file->id,
                    'url' => $file->url,
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Mobile upload error: ' . $e->getMessage());
            return response()->json(['error' => 'Error al subir el archivo: ' . $e->getMessage()], 500);
        }
    }
}
