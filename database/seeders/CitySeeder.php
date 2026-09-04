<?php

namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    public function run(): void
    {
        $cities = [
            // D.I. Yogyakarta & Jawa Tengah
            ['name' => 'Kota Yogyakarta', 'province' => 'D.I. Yogyakarta', 'latitude' => -7.7956, 'longitude' => 110.3695],
            ['name' => 'Kab. Sleman', 'province' => 'D.I. Yogyakarta', 'latitude' => -7.7122, 'longitude' => 110.3552],
            ['name' => 'Kab. Bantul', 'province' => 'D.I. Yogyakarta', 'latitude' => -7.8872, 'longitude' => 110.3289],
            ['name' => 'Kab. Gunungkidul', 'province' => 'D.I. Yogyakarta', 'latitude' => -7.9609, 'longitude' => 110.6049],
            ['name' => 'Kab. Kulon Progo', 'province' => 'D.I. Yogyakarta', 'latitude' => -7.8183, 'longitude' => 110.1578],
            ['name' => 'Kota Semarang', 'province' => 'Jawa Tengah', 'latitude' => -6.9667, 'longitude' => 110.4167],
            ['name' => 'Kota Surakarta (Solo)', 'province' => 'Jawa Tengah', 'latitude' => -7.5755, 'longitude' => 110.8243],
            ['name' => 'Kab. Magelang', 'province' => 'Jawa Tengah', 'latitude' => -7.4706, 'longitude' => 110.2178],
            ['name' => 'Kota Magelang', 'province' => 'Jawa Tengah', 'latitude' => -7.4797, 'longitude' => 110.2177],
            ['name' => 'Kab. Klaten', 'province' => 'Jawa Tengah', 'latitude' => -7.7058, 'longitude' => 110.6022],

            // DKI Jakarta & Jabodetabek
            ['name' => 'Jakarta Selatan', 'province' => 'DKI Jakarta', 'latitude' => -6.2615, 'longitude' => 106.8106],
            ['name' => 'Jakarta Pusat', 'province' => 'DKI Jakarta', 'latitude' => -6.1805, 'longitude' => 106.8284],
            ['name' => 'Jakarta Barat', 'province' => 'DKI Jakarta', 'latitude' => -6.1683, 'longitude' => 106.7589],
            ['name' => 'Jakarta Timur', 'province' => 'DKI Jakarta', 'latitude' => -6.2250, 'longitude' => 106.9004],
            ['name' => 'Jakarta Utara', 'province' => 'DKI Jakarta', 'latitude' => -6.1384, 'longitude' => 106.8640],
            ['name' => 'Kota Bogor', 'province' => 'Jawa Barat', 'latitude' => -6.5971, 'longitude' => 106.8060],
            ['name' => 'Kota Depok', 'province' => 'Jawa Barat', 'latitude' => -6.4025, 'longitude' => 106.7942],
            ['name' => 'Kota Tangerang', 'province' => 'Banten', 'latitude' => -6.1783, 'longitude' => 106.6300],
            ['name' => 'Kota Tangerang Selatan', 'province' => 'Banten', 'latitude' => -6.2886, 'longitude' => 106.7179],
            ['name' => 'Kota Bekasi', 'province' => 'Jawa Barat', 'latitude' => -6.2383, 'longitude' => 106.9756],

            // Jawa Barat & Banten
            ['name' => 'Kota Bandung', 'province' => 'Jawa Barat', 'latitude' => -6.9175, 'longitude' => 107.6191],
            ['name' => 'Kota Cimahi', 'province' => 'Jawa Barat', 'latitude' => -6.8722, 'longitude' => 107.5422],
            ['name' => 'Kota Cirebon', 'province' => 'Jawa Barat', 'latitude' => -6.7320, 'longitude' => 108.5523],
            ['name' => 'Kota Sukabumi', 'province' => 'Jawa Barat', 'latitude' => -6.9277, 'longitude' => 106.9300],

            // Jawa Timur & Bali
            ['name' => 'Kota Surabaya', 'province' => 'Jawa Timur', 'latitude' => -7.2575, 'longitude' => 112.7521],
            ['name' => 'Kota Malang', 'province' => 'Jawa Timur', 'latitude' => -7.9666, 'longitude' => 112.6326],
            ['name' => 'Kota Kediri', 'province' => 'Jawa Timur', 'latitude' => -7.8480, 'longitude' => 112.0178],
            ['name' => 'Kota Denpasar', 'province' => 'Bali', 'latitude' => -8.6705, 'longitude' => 115.2126],
            ['name' => 'Kab. Badung (Kuta)', 'province' => 'Bali', 'latitude' => -8.6482, 'longitude' => 115.1724],

            // Sumatera, Kalimantan, Sulawesi
            ['name' => 'Kota Medan', 'province' => 'Sumatera Utara', 'latitude' => 3.5952, 'longitude' => 98.6722],
            ['name' => 'Kota Palembang', 'province' => 'Sumatera Selatan', 'latitude' => -2.9761, 'longitude' => 104.7754],
            ['name' => 'Kota Padang', 'province' => 'Sumatera Barat', 'latitude' => -0.9471, 'longitude' => 100.4172],
            ['name' => 'Kota Pekanbaru', 'province' => 'Riau', 'latitude' => 0.5071, 'longitude' => 101.4478],
            ['name' => 'Kota Bandar Lampung', 'province' => 'Lampung', 'latitude' => -5.4500, 'longitude' => 105.2667],
            ['name' => 'Kota Batam', 'province' => 'Kepulauan Riau', 'latitude' => 1.1301, 'longitude' => 104.0529],
            ['name' => 'Kota Balikpapan', 'province' => 'Kalimantan Timur', 'latitude' => -1.2379, 'longitude' => 116.8529],
            ['name' => 'Kota Samarinda', 'province' => 'Kalimantan Timur', 'latitude' => -0.5022, 'longitude' => 117.1536],
            ['name' => 'Kota Banjarmasin', 'province' => 'Kalimantan Selatan', 'latitude' => -3.3194, 'longitude' => 114.5908],
            ['name' => 'Kota Makassar', 'province' => 'Sulawesi Selatan', 'latitude' => -5.1477, 'longitude' => 119.4327],
            ['name' => 'Kota Manado', 'province' => 'Sulawesi Utara', 'latitude' => 1.4748, 'longitude' => 124.8428],
        ];

        foreach ($cities as $city) {
            City::updateOrCreate(['name' => $city['name']], $city);
        }
    }
}
