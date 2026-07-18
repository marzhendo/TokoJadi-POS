<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Validation\Rule;

class StoreTransaksiRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // Update v2: cash or kasbon
            'metode_bayar' => ['required', Rule::in(['cash', 'kasbon'])],
            
            'pelanggan_id' => [
                'nullable',
                Rule::requiredIf(fn() => $this->metode_bayar === 'kasbon'),
                'integer',
                Rule::exists('pelanggan', 'id')
            ],
            
            // Validasi keranjang items
            'items' => ['required', 'array', 'min:1'],
            'items.*.satuan_jual_id' => [
                'required',
                'integer',
                Rule::exists('produk_satuan_jual', 'id')->where('aktif', true),
            ],
            'items.*.jumlah' => ['required', 'numeric', 'min:0.001'],
        ];
    }
}
