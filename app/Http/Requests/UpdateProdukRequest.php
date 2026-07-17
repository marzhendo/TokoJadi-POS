<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProdukRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama'                         => ['required', 'string', 'max:255'],
            'kategori_id'                  => ['required', 'exists:kategori,id'],
            'satuan_dasar_id'              => ['required', 'exists:satuan,id'],
            'stok_minimum'                 => ['required', 'numeric', 'min:0'],
            'harga_modal_per_satuan_dasar' => ['required', 'numeric', 'min:0'],
            // Minimal 1 satuan jual, tidak boleh duplikat satuan_id
            'satuan_jual'                  => ['required', 'array', 'min:1'],
            'satuan_jual.*.satuan_id'      => ['required', 'exists:satuan,id', 'distinct'],
            'satuan_jual.*.jumlah_dalam_satuan_dasar' => ['required', 'numeric', 'min:0.001'],
            'satuan_jual.*.harga_jual'     => ['required', 'numeric', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'nama.required'           => 'Nama produk wajib diisi.',
            'kategori_id.required'    => 'Pilih kategori produk.',
            'satuan_dasar_id.required' => 'Pilih satuan dasar.',
            'satuan_jual.required'    => 'Minimal satu satuan jual wajib ditambahkan.',
            'satuan_jual.min'         => 'Minimal satu satuan jual wajib ditambahkan.',
            'satuan_jual.*.satuan_id.distinct' => 'Terdapat satuan jual yang duplikat.',
        ];
    }
}
