<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ExportHome; // <-- Import your service
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ExportController extends Controller
{
    /**
     * Handle the request to trigger the homepage export.
     */
    public function trigger(Request $request, ExportHome $exportHome)
    {
        try {
            // Run your existing service
            $exportHome->run();

            return response()->json([
                'success' => true,
                'message' => 'Homepage export completed successfully.'
            ]);

        } catch (\Exception $e) {
            // Log the error
            Log::error('Homepage export failed: ' . $e->getMessage());

            // Return an error response
            return response()->json([
                'success' => false,
                'message' => 'Homepage export failed.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
