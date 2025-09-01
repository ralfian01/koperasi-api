<?php

namespace App\Http\Controllers\REST\V1\Manage\Business;

use App\Http\Libraries\BaseDBRepo;
use App\Models\Business;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DBRepo extends BaseDBRepo
{

    /*
     * =================================================================================
     * METHOD STATIC/TOOLS
     * =================================================================================
     */

    /**
     * Memeriksa apakah email unik saat proses update.
     * Mengabaikan record dengan ID yang sedang diedit.
     * @param string|null $email
     * @param int $ignoreId ID dari record yang akan diabaikan
     * @return bool
     */
    public static function isEmailUniqueOnUpdate(?string $email, int $ignoreId): bool
    {
        // Jika email tidak diberikan (nullable), anggap valid
        if (is_null($email)) {
            return true;
        }

        // Cari record lain yang memiliki email yang sama, KECUALI record yang sedang kita edit
        return !Business::where('email', $email)
            ->where('id', '!=', $ignoreId)
            ->exists();
    }

    public function getData()
    {
        try {
            $query = Business::query()->with('outlets'); // Eager load outlets

            if (isset($this->payload['id'])) {
                $data = $query->find($this->payload['id']);
                return (object) ['status' => !is_null($data), 'data' => $data ? $data->toArray() : null];
            }

            if (isset($this->payload['keyword'])) {
                $query->where('name', 'LIKE', "%{$this->payload['keyword']}%");
            }

            $perPage = $this->payload['per_page'] ?? 15;
            $data = $query->paginate($perPage);

            return (object) ['status' => true, 'data' => $data->toArray()];
        } catch (Exception $e) {
            return (object) ['status' => false, 'message' => $e->getMessage()];
        }
    }


    public function insertData()
    {
        try {
            $dbPayload = $this->payload;

            // --- PERUBAHAN KRUSIAL DI SINI ---

            // 1. Dapatkan file langsung dari helper request() Laravel
            // Ini menjamin kita mendapatkan objek Illuminate\Http\UploadedFile
            $logoFile = request()->file('logo');

            // 2. Proses Upload File (jika ada dan valid)
            if ($logoFile && $logoFile->isValid()) {

                // Buat nama file yang unik
                $fileName = 'logo_' . time() . '_' . uniqid() . '.' . $logoFile->getClientOriginalExtension();

                // Simpan file menggunakan method storeAs() yang sekarang sudah dikenali
                $path = $logoFile->storeAs('logos', $fileName, 'public');

                // 3. Tambahkan path file ke payload yang akan disimpan ke DB
                $dbPayload['logo'] = $path;
            }
            // ------------------------------------

            return DB::transaction(function () use ($dbPayload) {
                // 4. Buat record di database dengan payload yang sudah diperbarui
                $business = Business::create($dbPayload);

                if (!$business) {
                    throw new Exception("Failed to create a new business unit.");
                }

                return (object) [
                    'status' => true,
                    'data' => (object) ['id' => $business->id]
                ];
            });
        } catch (Exception $e) {
            return (object) [
                'status' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Fungsi utama untuk memperbarui data bisnis.
     * @return object
     */
    public function updateData()
    {
        try {
            $businessId = $this->payload['id'];
            $business = Business::findOrFail($businessId);

            $dbPayload = Arr::except($this->payload, ['id']);

            $logoFile = request()->file('logo');
            if ($logoFile && $logoFile->isValid()) {
                if ($business->logo) {
                    Storage::disk('public')->delete($business->logo);
                }
                $fileName = 'logo_' . time() . '_' . uniqid() . '.' . $logoFile->getClientOriginalExtension();
                $path = $logoFile->storeAs('logos', $fileName, 'public');
                $dbPayload['logo'] = $path;
            }

            return DB::transaction(function () use ($business, $dbPayload) {
                $business->update($dbPayload);
                return (object) ['status' => true];
            });
        } catch (Exception $e) {
            return (object) [
                'status' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Fungsi utama untuk menghapus data bisnis.
     * @return object
     */
    public function deleteData()
    {
        try {
            // 1. Ambil ID dari payload dan cari data bisnis
            $businessId = $this->payload['id'];
            $business = Business::findOrFail($businessId);

            // 2. Simpan path logo untuk dihapus nanti
            $logoPath = $business->logo;

            // 3. Hapus record dari database
            // onDelete('cascade') pada migrasi akan menangani penghapusan data terkait
            // seperti outlets, products, dll. secara otomatis.
            $business->delete();

            // 4. Hapus file logo dari storage JIKA record DB berhasil dihapus
            if ($logoPath) {
                Storage::disk('public')->delete($logoPath);
            }

            return (object) ['status' => true];
        } catch (Exception $e) {
            return (object) [
                'status' => false,
                'message' => $e->getMessage(),
            ];
        }
    }
}
