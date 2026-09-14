<?php

namespace App\Http\Controllers;

use App\Models\PrintJob;
use App\Models\PrintJobCode;
use App\Models\LabelSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        
        // Retrieve default or active setting for the user
        $setting = LabelSetting::where('user_id', $user->id)
            ->where('is_default', true)
            ->first();

        if (!$setting) {
            $setting = LabelSetting::create([
                'user_id' => $user->id,
                'setting_name' => 'XP-356B (20x30մմ)',
                'width_mm' => 20.0,
                'height_mm' => 30.0,
                'margin_mm' => 1.0,
                'orientation' => 'portrait',
                'product_font_size' => 9,
                'product_font_bold' => true,
                'product_pos_x' => 1.5,
                'product_pos_y' => 2.0,
                'last5_font_size' => 11,
                'last5_font_bold' => true,
                'last5_pos_x' => 1.5,
                'last5_pos_y' => 7.0,
                'datamatrix_size' => 15.0,
                'datamatrix_pos_x' => 2.5,
                'datamatrix_pos_y' => 12.0,
                'is_default' => true,
            ]);
        }

        $recentJobs = PrintJob::where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        $totalPrintedCodes = PrintJob::where('user_id', $user->id)->sum('printed_count');
        $totalBatchesCount = PrintJob::where('user_id', $user->id)->count();

        return view('dashboard.index', compact('setting', 'recentJobs', 'totalPrintedCodes', 'totalBatchesCount'));
    }

    /**
     * Preview the first 5 rows of an uploaded CSV file (AJAX request).
     */
    public function previewCsv(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|extensions:csv,txt|max:10240',
        ]);

        $file = $request->file('csv_file');
        $handle = fopen($file->getRealPath(), 'r');
        
        $previewRows = [];
        $totalRowsCount = 0;
        
        if ($handle !== false) {
            while (($row = fgetcsv($handle, 1000, ",")) !== false) {
                // Ignore empty rows
                if (empty($row) || (count($row) === 1 && trim($row[0]) === '')) {
                    continue;
                }

                $code = trim($row[0]);
                $totalRowsCount++;

                if (count($previewRows) < 5) {
                    $last5 = mb_strlen($code) >= 5 ? mb_substr($code, -5) : $code;
                    $previewRows[] = [
                        'row_num' => $totalRowsCount,
                        'code' => $code,
                        'last_5' => $last5,
                    ];
                }
            }
            fclose($handle);
        }

        return response()->json([
            'success' => true,
            'filename' => $file->getClientOriginalName(),
            'total_count' => $totalRowsCount,
            'preview_rows' => $previewRows,
        ]);
    }

    /**
     * Store full CSV import and batch print job.
     */
    public function storeBatch(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|extensions:csv,txt|max:20480',
            'product_name' => 'required|string|max:255',
        ]);

        $user = Auth::user();
        $file = $request->file('csv_file');
        $productName = trim($request->input('product_name'));
        $originalFilename = $file->getClientOriginalName();

        $handle = fopen($file->getRealPath(), 'r');
        $codes = [];

        if ($handle !== false) {
            while (($row = fgetcsv($handle, 4096, ",")) !== false) {
                if (!empty($row) && isset($row[0]) && trim($row[0]) !== '') {
                    $code = trim($row[0]);
                    $last5 = mb_strlen($code) >= 5 ? mb_substr($code, -5) : $code;
                    $codes[] = [
                        'code' => $code,
                        'last_5_chars' => $last5,
                    ];
                }
            }
            fclose($handle);
        }

        if (empty($codes)) {
            return response()->json([
                'success' => false,
                'message' => 'CSV ֆայլը դատարկ է կամ չի պարունակում վավեր կոդեր:',
            ], 422);
        }

        // Limit maximum codes to prevent server crash (e.g. 10,000 codes max per batch)
        if (count($codes) > 10000) {
            return response()->json([
                'success' => false,
                'message' => 'Կոդերի քանակը գերազանցում է առավելագույն 10,000 սահմանափակումը:',
            ], 422);
        }

        $printJob = DB::transaction(function () use ($user, $productName, $originalFilename, $codes) {
            $job = PrintJob::create([
                'user_id' => $user->id,
                'product_name' => $productName,
                'file_name' => $originalFilename,
                'total_codes' => count($codes),
                'printed_count' => count($codes),
                'status' => 'completed',
            ]);

            $now = now();
            $records = [];
            foreach ($codes as $c) {
                $records[] = [
                    'print_job_id' => $job->id,
                    'code' => $c['code'],
                    'last_5_chars' => $c['last_5_chars'],
                    'printed_at' => $now,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }

            // Batch insert in chunks of 500 for high performance
            foreach (array_chunk($records, 500) as $chunk) {
                PrintJobCode::insert($chunk);
            }

            return $job;
        });

        return response()->json([
            'success' => true,
            'message' => 'Ֆայլը ներբեռնվեց և տպագրության խմբաքանակը պատրաստ է:',
            'redirect_url' => route('dashboard.print', $printJob->id),
        ]);
    }

    /**
     * Render the thermal label sheet print view for a specific PrintJob.
     */
    public function printJob(PrintJob $printJob)
    {
        if ($printJob->user_id !== Auth::id()) {
            abort(403);
        }

        $setting = LabelSetting::where('user_id', Auth::id())
            ->where('is_default', true)
            ->first();

        $codes = $printJob->codes()->get();

        return view('print.label_sheet', compact('printJob', 'codes', 'setting'));
    }
}
