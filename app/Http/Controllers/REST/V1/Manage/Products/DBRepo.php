<?php

namespace App\Http\Controllers\REST\V1\Manage\Products;

use App\Http\Libraries\BaseDBRepo;
use App\Models\Product;
use App\Models\Unit;
use Exception;
use Illuminate\Support\Facades\DB;

// Semua model yang digunakan didefinisikan di sini untuk kejelasan
use App\Models\ProductVariant;
use App\Models\Resource;
use App\Models\ResourceAvailability;
use App\Models\Pricing;
use App\Models\ServiceStock;

class DBRepo extends BaseDBRepo
{
    /*
     * =================================================================================
     * METHOD UNTUK MENGAMBIL DATA (GET)
     * =================================================================================
     */

    /*
     * =================================================================================
     * METHOD UNTUK MENGAMBIL DATA (GET) - VERSI MULTI-OUTLET
     * =================================================================================
     */
    public function getData()
    {
        try {
            // // -- KUNCI PERUBAHAN --
            // // Dapatkan ID outlet tempat karyawan bekerja dari data otentikasi.
            // $loggedInOutletId = $this->auth['outlet_id'] ?? null;
            // if (!$loggedInOutletId) {
            //     throw new Exception("Authentication context is missing outlet_id.");
            // }

            // Mulai query dengan scoping berdasarkan produk yang di-assign ke outlet karyawan
            $query = Product::query()
                // ->whereHas('outlets', function ($q) use ($loggedInOutletId) {
                //     $q->where('outlets.id', $loggedInOutletId);
                // })
                ->with([
                    'variants.price.unit',
                    'resources.availability',
                    'serviceStock.unit',
                    'pricing.unit',
                    'category',
                    'business'
                ]);

            if (isset($this->payload['id'])) {
                $data = $query->find($this->payload['id']);
                return (object) ['status' => !is_null($data), 'data' => $data ? $data->toArray() : null];
            }

            // $this->applyFilters($query);

            $perPage = $this->payload['per_page'] ?? 15;
            $data = $query->paginate($perPage);

            return (object) ['status' => true, 'data' => $data->toArray()];
        } catch (Exception $e) {
            return (object) ['status' => false, 'message' => $e->getMessage()];
        }
    }

    private function applyFilters(&$query)
    {
        if (isset($this->payload['booking_mechanism'])) {
            $query->where('booking_mechanism', $this->payload['booking_mechanism']);
        }
        if (isset($this->payload['keyword'])) {
            $keyword = $this->payload['keyword'];
            $query->where(function ($subQuery) use ($keyword) {
                $subQuery->where('name', 'LIKE', "%{$keyword}%")
                    ->orWhereHas('category', function ($q) use ($keyword) {
                        $q->where('name', 'LIKE', "%{$keyword}%");
                    });
            });
        }
    }

    /*
     * =================================================================================
     * METHOD UNTUK MENAMBAH DATA (INSERT) - VERSI MULTI-OUTLET
     * =================================================================================
     */
    public function insertData()
    {
        try {
            $result = DB::transaction(function () {
                // Langkah 1: Buat Produk utama, sekarang dengan business_id
                $product = Product::create([
                    'business_id' => $this->payload['business_id'],
                    'category_id' => $this->payload['category_id'],
                    'name' => $this->payload['name'],
                    'description' => $this->payload['description'] ?? null,
                    'product_type' => ($this->payload['booking_mechanism'] === 'INVENTORY_STOCK') ? 'GOODS' : 'SERVICE',
                    'booking_mechanism' => $this->payload['booking_mechanism'],
                ]);

                // Langkah 2: Jalankan alur spesifik berdasarkan mekanisme
                // (Tidak ada perubahan di dalam method-method ini)
                switch ($this->payload['booking_mechanism']) {
                    case 'INVENTORY_STOCK':
                        $this->createInventoryStockData($product);
                        break;
                    case 'TIME_SLOT':
                    case 'TIME_SLOT_CAPACITY':
                        $this->createTimeSlotData($product);
                        break;
                    case 'CONSUMABLE_STOCK':
                        $this->createConsumableStockData($product);
                        break;
                }

                // -- KUNCI PERUBAHAN --
                // Langkah 3: Assign produk yang baru dibuat ke outlet-outlet yang dipilih
                if (!empty($this->payload['outlet_ids'])) {
                    $product->outlets()->sync($this->payload['outlet_ids']);
                }

                return (object) ['status' => true, 'data' => (object) ['product_id' => $product->product_id]];
            });
            return $result;
        } catch (Exception $e) {
            return (object) ['status' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Method pendukung untuk membuat data produk Barang (INVENTORY_STOCK).
     */
    private function createInventoryStockData(Product $product): void
    {
        $unitPcs = Unit::where('name', 'Pcs')->firstOrFail();
        foreach ($this->payload['variants'] as $variantData) {
            $variant = $product->variants()->create([
                'name' => $variantData['name'],
                'stock_quantity' => $variantData['stock_quantity'],
                'sku' => $variantData['sku'] ?? null,
            ]);
            $variant->price()->create([
                'product_id' => $product->product_id,
                'unit_id' => $unitPcs->unit_id,
                'price' => $variantData['price'],
            ]);
        }
    }

    /**
     * Method pendukung untuk membuat data produk Jasa berbasis Waktu (TIME_SLOT & TIME_SLOT_CAPACITY).
     */
    private function createTimeSlotData(Product $product): void
    {
        foreach ($this->payload['resources'] as $resourceData) {
            $resource = $product->resources()->create([
                'name' => $resourceData['name'],
                'capacity' => $resourceData['capacity'] ?? null,
            ]);
            foreach ($this->payload['availability'] as $slot) {
                $resource->availability()->create($slot);
            }
        }
        foreach ($this->payload['prices'] as $priceData) {
            $product->pricing()->create($priceData);
        }
    }

    /**
     * Method pendukung untuk membuat data produk Jasa berbasis Stok (CONSUMABLE_STOCK).
     */
    private function createConsumableStockData(Product $product): void
    {
        foreach ($this->payload['service_stock'] as $stockData) {
            $product->serviceStock()->create($stockData);
        }
        foreach ($this->payload['prices'] as $priceData) {
            $product->pricing()->create($priceData);
        }
    }

    /*
     * =================================================================================
     * METHOD STATIC/TOOLS (Digunakan oleh Controller lain)
     * =================================================================================
     */

    /**
     * Method pendukung (static) untuk memeriksa keberadaan unit ID.
     * Digunakan oleh `Insert.php` di `nextValidation()`.
     */
    public static function checkUnitsExist(array $unitIds): bool
    {
        if (empty($unitIds)) return false;
        // Gunakan array_unique untuk efisiensi jika ada ID duplikat di payload
        $uniqueUnitIds = array_unique($unitIds);
        $count = Unit::whereIn('unit_id', $uniqueUnitIds)->count();
        return $count === count($uniqueUnitIds);
    }
}
