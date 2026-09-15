<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LabelSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'setting_name',
        'width_mm',
        'height_mm',
        'margin_mm',
        'margin_top_mm',
        'margin_bottom_mm',
        'margin_left_mm',
        'margin_right_mm',
        'label_gap_mm',
        'orientation',
        'print_scale',
        'print_dpi',
        'product_font_size',
        'product_font_bold',
        'product_pos_x',
        'product_pos_y',
        'last5_font_size',
        'last5_font_bold',
        'last5_pos_x',
        'last5_pos_y',
        'datamatrix_size',
        'datamatrix_pos_x',
        'datamatrix_pos_y',
        'is_default',
    ];

    protected $casts = [
        'width_mm' => 'float',
        'height_mm' => 'float',
        'margin_mm' => 'float',
        'margin_top_mm' => 'float',
        'margin_bottom_mm' => 'float',
        'margin_left_mm' => 'float',
        'margin_right_mm' => 'float',
        'label_gap_mm' => 'float',
        'print_scale' => 'integer',
        'print_dpi' => 'integer',
        'product_font_size' => 'integer',
        'product_font_bold' => 'boolean',
        'product_pos_x' => 'float',
        'product_pos_y' => 'float',
        'last5_font_size' => 'integer',
        'last5_font_bold' => 'boolean',
        'last5_pos_x' => 'float',
        'last5_pos_y' => 'float',
        'datamatrix_size' => 'float',
        'datamatrix_pos_x' => 'float',
        'datamatrix_pos_y' => 'float',
        'is_default' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
