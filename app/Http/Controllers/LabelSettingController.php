<?php

namespace App\Http\Controllers;

use App\Models\LabelSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LabelSettingController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        $setting = LabelSetting::where('user_id', $user->id)
            ->where('is_default', true)
            ->first();

        if (!$setting) {
            $setting = LabelSetting::create([
                'user_id' => $user->id,
                'setting_name' => 'Ունիվերսալ (20x30մմ)',
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

        return view('settings.edit', compact('setting'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'setting_name' => 'nullable|string|max:255',
            'width_mm' => 'required|numeric|min:10|max:200',
            'height_mm' => 'required|numeric|min:10|max:200',
            'margin_mm' => 'required|numeric|min:0|max:20',
            'margin_top_mm' => 'nullable|numeric|min:0|max:50',
            'margin_bottom_mm' => 'nullable|numeric|min:0|max:50',
            'margin_left_mm' => 'nullable|numeric|min:0|max:50',
            'margin_right_mm' => 'nullable|numeric|min:0|max:50',
            'label_gap_mm' => 'nullable|numeric|min:0|max:20',
            'orientation' => 'required|in:portrait,landscape',
            'print_scale' => 'nullable|integer|min:50|max:200',
            'print_dpi' => 'nullable|integer|in:203,300,600',
            'product_font_size' => 'required|integer|min:4|max:40',
            'product_font_bold' => 'boolean',
            'product_pos_x' => 'required|numeric|min:0|max:200',
            'product_pos_y' => 'required|numeric|min:0|max:200',
            'last5_font_size' => 'required|integer|min:4|max:40',
            'last5_font_bold' => 'boolean',
            'last5_pos_x' => 'required|numeric|min:0|max:200',
            'last5_pos_y' => 'required|numeric|min:0|max:200',
            'datamatrix_size' => 'required|numeric|min:5|max:100',
            'datamatrix_pos_x' => 'required|numeric|min:0|max:200',
            'datamatrix_pos_y' => 'required|numeric|min:0|max:200',
        ]);

        $user = Auth::user();

        $setting = LabelSetting::updateOrCreate(
            ['user_id' => $user->id],
            [
                'setting_name' => $validated['setting_name'] ?? 'XP-356B (20x30մմ)',
                'width_mm' => $validated['width_mm'],
                'height_mm' => $validated['height_mm'],
                'margin_mm' => $validated['margin_mm'],
                'margin_top_mm' => $validated['margin_top_mm'] ?? 0.0,
                'margin_bottom_mm' => $validated['margin_bottom_mm'] ?? 0.0,
                'margin_left_mm' => $validated['margin_left_mm'] ?? 0.0,
                'margin_right_mm' => $validated['margin_right_mm'] ?? 0.0,
                'label_gap_mm' => $validated['label_gap_mm'] ?? 2.0,
                'orientation' => $validated['orientation'],
                'print_scale' => $validated['print_scale'] ?? 100,
                'print_dpi' => $validated['print_dpi'] ?? 203,
                'product_font_size' => $validated['product_font_size'],
                'product_font_bold' => $request->boolean('product_font_bold'),
                'product_pos_x' => $validated['product_pos_x'],
                'product_pos_y' => $validated['product_pos_y'],
                'last5_font_size' => $validated['last5_font_size'],
                'last5_font_bold' => $request->boolean('last5_font_bold'),
                'last5_pos_x' => $validated['last5_pos_x'],
                'last5_pos_y' => $validated['last5_pos_y'],
                'datamatrix_size' => $validated['datamatrix_size'],
                'datamatrix_pos_x' => $validated['datamatrix_pos_x'],
                'datamatrix_pos_y' => $validated['datamatrix_pos_y'],
                'is_default' => true,
            ]
        );

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Լեյբլի կարգավորումները հաջողությամբ պահպանվեցին:',
                'setting' => $setting,
            ]);
        }

        return back()->with('success', 'Լեյբլի կարգավորումները հաջողությամբ պահպանվեցին:');
    }
}
