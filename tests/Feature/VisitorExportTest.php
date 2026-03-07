<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Visit;

class VisitorExportTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['role' => 'admin']);
    }

    public function test_visitor_pdf_export_with_various_parameters()
    {
        $response = $this->actingAs($this->admin)
            ->get(route('cetak.kunjungan.pdf', [
                'hari' => 'today',
                'bulan' => '03',
                'tahun' => '2026',
                'kelas' => 'XII RPL 1'
            ]));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/pdf');
    }

    public function test_visitor_excel_export_with_various_parameters()
    {
        $response = $this->actingAs($this->admin)
            ->get(route('cetak.kunjungan.excel', [
                'hari' => '07',
                'bulan' => '03',
                'tahun' => '2026',
                'kelas' => 'XII RPL 1'
            ]));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    }
}
