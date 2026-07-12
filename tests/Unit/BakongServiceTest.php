<?php

namespace Tests\Unit;

use App\Services\BakongService;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class BakongServiceTest extends TestCase
{
    public function test_generate_qr_falls_back_to_local_payload_when_bakong_api_fails(): void
    {
        Http::fake([
            'https://api-bakong.nbc.gov.kh/v1/qr/generate' => Http::response([
                'responseCode' => 1,
                'responseMessage' => 'Internal Server Error',
                'errorCode' => 15,
                'data' => null,
            ], 500),
        ]);

        $service = new BakongService();
        $result = $service->generateQR('1001', 100000, 'KHR', 'Test order');

        $this->assertTrue($result['success']);
        $this->assertTrue($result['fallback'] ?? false);
        $this->assertNotEmpty($result['qr_data']);
        $this->assertStringContainsString('Payment for order #1001', $result['qr_data']);
    }
}
