<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProdukRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // v1: single owner, semua boleh
    }

    public function rules(): array
    {
        return [
            'nama'                         => ['required', 'string', 'max:255'],
            'kategori_id'                  => ['required', 'exists:kategori,id'],
            'satuan_dasar_id'              => ['required', 'exists:satuan,id'],
            // AGENTS.md: input angka di kasir/produk WAJIB divalidasi
            'stok_saat_ini'                => ['required', 'numeric', 'min:0'],
            'stok_minimum'                 => ['required', 'numeric', 'min:0'],
            'harga_modal_per_satuan_dasar' => ['required', 'numeric', 'min:0'],
            // satuan_jual opsional saat create (bisa ditambah via edit)
            'satuan_jual'                  => ['nullable', 'array'],
            'satuan_jual.*.satuan_id'                 => ['required_with:satuan_jual', 'exists:satuan,id'],
            'satuan_jual.*.jumlah_dalam_satuan_dasar' => ['required_with:satuan_jual', 'numeric', 'min:0.001'],
            'satuan_jual.*.harga_jual'                => ['required_with:satuan_jual', 'numeric', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'nama.required'           => 'Nama produk wajib diisi.',
            'kategori_id.required'    => 'Pilih kategori produk.',
            'satuan_dasar_id.required' => 'Pilih satuan dasar.',
            'stok_saat_ini.numeric'   => 'Stok harus berupa angka.',
            'harga_modal_per_satuan_dasar.numeric' => 'Harga modal harus berupa angka.',
        ];
    }
}
