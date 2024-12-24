<?php

namespace App\Http\Controllers;

use App\Models\Administrasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\PembayaranService;
use Yajra\DataTables\Facades\DataTables;

class AdministrasiController extends Controller
{
    protected $pembayaranService;

    public function __construct(PembayaranService $pembayaranService)
    {
        $this->pembayaranService = $pembayaranService;
    }

        public function index()
        {
            return view('administrasi.pembayaran.index');
        }

        public function data()
        {
            $administrasis = Administrasi::with(['pendaftaran.jurusan'])
                ->orderBy('created_at', 'desc')
                ->select('administrasis.*');

            return DataTables::of($administrasis)
                ->addIndexColumn()
                ->addColumn('total_bayar_formatted', function ($row) {
                    return 'Rp ' . number_format($row->total_bayar, 0, ',', '.');
                })
                ->addColumn('sisa_pembayaran_formatted', function ($row) {
                    return 'Rp ' . number_format($row->sisa_pembayaran, 0, ',', '.');
                })
                ->addColumn('status_badge', function ($row) {
                    $badgeClass = $row->status_pembayaran == 'Lunas' ? 'bg-success' : 'bg-warning';
                    return '<span class="badge text-white ' . $badgeClass . '">' . $row->status_pembayaran . '</span>';
                })
                ->addColumn('action', function ($row) {
                    $html = '<div class="btn-group">';
                    if ($row->status_pembayaran != 'Lunas') {
                        $html .= '<a href="' . route('administrasi.pembayaran.bayar', $row->id) . '"
                                    class="btn btn-primary btn-sm">
                                    <i class="fas fa-money-bill"></i> Bayar
                                </a>';
                    }
                    $html .= '<a href="' . route('administrasi.pembayaran.detail', $row->id) . '"
                                class="btn btn-info btn-sm">
                                <i class="fas fa-info-circle"></i> Detail
                            </a>
                        </div>';
                    return $html;
                })
                ->rawColumns(['status_badge', 'action'])
                ->make(true);
        }

        public function show(Administrasi $administrasi)
        {
            $administrasi->load(['pendaftaran.jurusan', 'riwayatPembayaran']);
            return view('administrasi.pembayaran.detail', compact('administrasi'));
        }


    public function create(Administrasi $administrasi)
    {
        return view('administrasi.pembayaran.bayar', compact('administrasi'));
    }

    public function store(Request $request, Administrasi $administrasi)
    {
        $request->validate([
            'jenis_pembayaran' => 'required|in:pendaftaran,ppdb,mpls,awal_tahun',
            'jumlah_bayar' => 'required|numeric|min:1',
            'metode_pembayaran' => 'required|in:tunai,transfer',
            'bukti_pembayaran' => 'required_if:metode_pembayaran,transfer|image|max:2048'
        ]);

        try {
            DB::beginTransaction();

            $pembayaran = $this->pembayaranService->prosesPembayaran(
                $administrasi,
                $request->all()
            );

            DB::commit();

            // Generate struk pembayaran
            return view('administrasi.pembayaran.struk', compact('pembayaran'));

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memproses pembayaran: ' . $e->getMessage());
        }
    }

}
