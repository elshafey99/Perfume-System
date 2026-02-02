<?php

namespace App\Http\Controllers\Api\Product;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Helpers\ApiResponse;
use Illuminate\Http\JsonResponse;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class ProductPrintController extends Controller
{
    /**
     * Generate QR code for product
     */
    public function generateQRCode(int $id): JsonResponse
    {
        $product = Product::find($id);

        if (!$product) {
            return ApiResponse::error('المنتج غير موجود', 404);
        }

        // QR Code data
        $qrData = [
            'product_id' => $product->id,
            'code' => $product->code,
            'sku' => $product->sku,
            'barcode' => $product->barcode,
            'name' => $product->name,
            'selling_price' => (float) $product->selling_price,
        ];

        // Generate QR code as SVG (no imagick required)
        $qrCodeSvg = QrCode::format('svg')
            ->size(300)
            ->errorCorrection('H')
            ->generate(json_encode($qrData));

        return ApiResponse::success([
            'product' => [
                'id' => $product->id,
                'name' => $product->name,
                'code' => $product->code,
                'sku' => $product->sku,
                'barcode' => $product->barcode,
            ],
            'qr_code' => base64_encode($qrCodeSvg), // Base64 encoded SVG
            'qr_code_type' => 'svg',
        ], 'تم إنشاء QR Code بنجاح');
    }

    /**
     * Generate printable label for product
     */
    public function printLabel(int $id): JsonResponse
    {
        $product = Product::with(['category', 'productType'])->find($id);

        if (!$product) {
            return ApiResponse::error('المنتج غير موجود', 404);
        }

        // QR Code data
        $qrData = [
            'product_id' => $product->id,
            'code' => $product->code,
            'sku' => $product->sku,
            'barcode' => $product->barcode,
            'name' => $product->name,
            'selling_price' => (float) $product->selling_price,
        ];

        // Generate QR code as SVG
        $qrCodeSvg = QrCode::format('svg')
            ->size(200)
            ->errorCorrection('H')
            ->generate(json_encode($qrData));

        // Generate barcode (using Code128 format)
        $barcodeData = $product->barcode ?? $product->sku ?? $product->code;

        $labelData = [
            'product' => [
                'id' => $product->id,
                'name' => $product->name,
                'code' => $product->code,
                'sku' => $product->sku,
                'barcode' => $product->barcode,
                'selling_price' => (float) $product->selling_price,
                'category' => $product->category?->name,
                'product_type' => $product->productType?->name,
            ],
            'qr_code' => base64_encode($qrCodeSvg), // Base64 encoded SVG
            'qr_code_type' => 'svg',
            'barcode_data' => $barcodeData,
            'print_date' => now()->format('Y-m-d H:i:s'),
        ];

        return ApiResponse::success($labelData, 'تم إنشاء ملصق المنتج بنجاح');
    }

    /**
     * Get product QR code URL (for embedding in resources)
     */
    public function getQRCodeUrl(int $id): string
    {
        $product = Product::find($id);
        
        if (!$product) {
            return '';
        }

        $qrData = [
            'product_id' => $product->id,
            'code' => $product->code,
            'name' => $product->name,
        ];

        $qrCode = base64_encode(QrCode::format('png')
            ->size(150)
            ->generate(json_encode($qrData)));

        return 'data:image/png;base64,' . $qrCode;
    }
}
