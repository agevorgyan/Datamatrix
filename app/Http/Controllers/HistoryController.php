<?php

namespace App\Http\Controllers;

use App\Models\PrintJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\StreamedResponse;

class HistoryController extends Controller
{
    /**
     * Display paginated print history for current user with search & status filters.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = PrintJob::where('user_id', $user->id);

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('product_name', 'like', "%{$search}%")
                  ->orWhere('file_name', 'like', "%{$search}%");
            });
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $jobs = $query->latest()->paginate(15)->withQueryString();

        return view('history.index', compact('jobs'));
    }

    /**
     * Display details of a specific print job.
     */
    public function show(PrintJob $printJob)
    {
        if ($printJob->user_id !== Auth::id()) {
            abort(403);
        }

        $codes = $printJob->codes()->paginate(50);

        return view('history.show', compact('printJob', 'codes'));
    }

    /**
     * Delete a single print job.
     */
    public function destroy(PrintJob $printJob)
    {
        if ($printJob->user_id !== Auth::id()) {
            abort(403);
        }

        $printJob->delete();

        return redirect()->route('history.index')
            ->with('success', 'Տպագրության պատմության գրանցումը հեռացվեց:');
    }

    /**
     * Batch delete multiple print jobs at once.
     */
    public function destroyBatch(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:print_jobs,id',
        ]);

        $ids = $request->input('ids');
        $user = Auth::user();

        $count = PrintJob::where('user_id', $user->id)
            ->whereIn('id', $ids)
            ->delete();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Ընտրված {$count} գրանցումները հաջողությամբ ջնջվեցին:",
            ]);
        }

        return redirect()->route('history.index')
            ->with('success', "Ընտրված {$count} գրանցումները հաջողությամբ ջնջվեցին:");
    }

    /**
     * Export all print history logs of current user to a downloadable CSV file.
     */
    public function exportCsv(): StreamedResponse
    {
        $user = Auth::user();
        $filename = 'print_history_' . date('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($user) {
            $file = fopen('php://output', 'w');
            
            // Output UTF-8 BOM for Excel armenian text compatibility
            fputs($file, "\xEF\xBB\xBF");

            // Header row
            fputcsv($file, [
                'ID',
                'Ապրանքի անվանում',
                'Ֆայլի անվանում',
                'Կոդերի քանակ',
                'Կարգավիճակ',
                'Ամսաթիվ'
            ]);

            $jobs = PrintJob::where('user_id', $user->id)->latest()->get();

            foreach ($jobs as $job) {
                $statusText = match ($job->status) {
                    'completed' => 'Ավարտված',
                    'pending' => 'Սպասող',
                    'in_progress' => 'Ընթացքի մեջ',
                    default => $job->status,
                };

                fputcsv($file, [
                    $job->id,
                    $job->product_name,
                    $job->file_name,
                    $job->total_codes,
                    $statusText,
                    $job->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
