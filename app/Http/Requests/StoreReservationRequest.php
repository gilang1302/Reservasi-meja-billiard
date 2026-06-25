<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReservationRequest extends FormRequest
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
            'table_id' => 'required|exists:tables,id',
            'start_time' => 'required|date|after_or_equal:today',
            'end_time' => 'required|date|after:start_time',
            'payment_method' => 'required|in:QRIS,Transfer Bank,Cash',
            'bank_name' => 'required_if:payment_method,Transfer Bank|nullable|string',
        ];
    }

    /**
     * Custom messages for validation.
     */
    public function messages(): array
    {
        return [
            'table_id.required' => 'Meja billiard wajib dipilih.',
            'table_id.exists' => 'Meja billiard yang dipilih tidak valid.',
            'start_time.required' => 'Waktu mulai reservasi wajib diisi.',
            'start_time.after_or_equal' => 'Waktu mulai tidak boleh tanggal lampau.',
            'end_time.required' => 'Waktu selesai reservasi wajib diisi.',
            'end_time.after' => 'Waktu selesai harus setelah waktu mulai.',
            'payment_method.required' => 'Metode pembayaran wajib dipilih.',
            'payment_method.in' => 'Metode pembayaran tidak valid.',
            'bank_name.required_if' => 'Nama Bank wajib diisi untuk Transfer Bank.',
        ];
    }
}
