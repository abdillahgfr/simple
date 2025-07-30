<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AsetController extends Controller
{

    public function index(Request $request)
    {
        $user = session('user');
        if (!$user) {
            return redirect()->route('login')->withErrors(['login_error' => 'Please log in first.']);
        }

        // Pagination parameters
        $perPage = 1000;
        $page = $request->input('page', 1);
        $offset = ($page - 1) * $perPage;

        // Add LIMIT/OFFSET for SQL Server (use OFFSET-FETCH)
        $query = "
            SELECT
                [a].guid_aset as GUID_ASET,
                [a].KOLOKSKPD,
                [b3].NALOK as NALOKSKPD,
                [a].kolok as KOLOK,
                [b].NALOK,
                [a].KOLOKUPB as KOLOKSEKOLAH,
                [b2].nalok as NALOKSEKOLAH,
                [a].[kobar_108] as KOBAR,
                [c].[nabar] as NABAR,
                [a].[noreg_108] as NOREG,
                [a].[ukuran] as UKURAN,
                [a].[satuan] as SATUAN,
                [a].[kondisi] as KONDISI,
                [a].[alamat] as ALAMAT,
                [a].[nojalan] as NO_JALAN,
                [a].[rt] as RT,
                [a].[rw] as RW,
                [a].[KDLURAH] as KD_LURAH,
                [lurah].[nm_kel] as KELURAHAN,
                [camat].[kd_kec] as KD_CAMAT,
                [camat].[nm_kec] as KECAMATAN,
                [rayon].[kd_rayon] as KD_RAYON,
                [rayon].[nm_rayon] as RAYON,
                [prop].[kd_prop] as KD_PROV,
                [prop].[nm_prop] as PROVINSI,
                [f].[nm_asaloleh1] as ASAL_OLEH,
                [a].[harga] as HARGA_PEROLEHAN,
                [a].[tgloleh] as TGL_PEROLEHAN,
                [a].[nodok] as NO_DOKUMEN,
                [a].[tgldok] as TGL_DOKUMEN,
                [a].[lat] as LATITUDE,
                [a].[lon] as LONGITUDE,
                [a].[penggunaan] as PENGGUNAAN,
                [a].[bahan] as BAHAN,
                [a].[merk] as MERK,
                [a].[tipe] as TIPE,
                [a].[ketmasalah] as KETERANGAN,
                COALESCE ( a.harga, 0 ) + COALESCE ( a.jukor_nilai, 0 ) + COALESCE ( a.jukor_kapitalisasi, 0 ) + COALESCE ( a.jukor_niladd, 0 ) + COALESCE ( a.nilai_term, 0 ) + COALESCE ( j.SUM_NILRENOV , 0 ) AS KIB_NIL
            FROM
                [siera2024].[dbo].[REKON5_B2024] AS [a] WITH ( NOLOCK )
                LEFT JOIN [bpadmaster].[dbo].[master_profile] AS [b] ON [a].[kolok] = [b].[id_kolok]
                LEFT JOIN [bpadmaster].[dbo].[master_profile] AS [b3] ON [a].[KOLOKSKPD] = [b3].[id_kolok]
                LEFT JOIN [bpadmaster].[dbo].[master_profile] AS [b2] ON [a].[KOLOKUPB] = [b2].[id_kolok]
                LEFT JOIN [bpadkobar].[dbo].[data_nabar] AS [c] ON [a].[kobar_108] = [c].[KOBAR]
                LEFT JOIN [bpadmaster].[dbo].[glo_asaloleh1] AS [f] ON [a].[asaloleh] = [f].[KD_ASALOLEH1]
                LEFT JOIN [bpadmaster].[dbo].[glo_wil_kelurahan] AS [lurah] ON [a].[KDLURAH] = [lurah].[kd_bpad]
                LEFT JOIN [bpadmaster].[dbo].[glo_wil_kecamatan] AS [camat] ON lurah.kd_prop = camat.kd_prop AND lurah.kd_rayon = camat.kd_rayon AND lurah.kd_kec = camat.kd_kec
                LEFT JOIN [bpadmaster].[dbo].[glo_wil_rayon] AS [rayon] ON rayon.kd_prop = lurah.kd_prop AND rayon.kd_rayon = lurah.kd_rayon
                LEFT JOIN [bpadmaster].[dbo].[glo_wil_provinsi] AS [prop] ON lurah.kd_prop = prop.kd_prop
                LEFT JOIN ( SELECT id_kolok, kd_wil FROM [bpadmaster].dbo.master_profile_detail WHERE tahun = 2024 ) AS h ON [a].[kolok] = [h].[id_kolok]
                LEFT JOIN (
                    SELECT
                        k.kolok,
                        k.kobar_108,
                        k.noreg_108,
                        SUM ( nilrenov ) SUM_NILRENOV 
                    FROM
                        siera2024.dbo.REKON5_RENOVASI2024 k 
                    GROUP BY
                        k.kolok,
                        k.kobar_108,
                        k.noreg_108 
                ) AS j ON [a].[kolok] = [j].[kolok] 
                AND [j].[kobar_108] = [a].[kobar_108] 
                AND [j].[noreg_108] = [a].[noreg_108]
                LEFT JOIN [bpadas].[dbo].[aset_qfatbbar] AS [r] ON [a].[kobar] = [r].[kobar] 
            WHERE
                [a].[sts] = 1
                AND ( COALESCE ( a.jukor_form, '' ) = '' ) 
                AND (
                    NOT (
                        (
                            COALESCE ( a.harga, 0 ) + COALESCE ( a.jukor_nilai, 0 ) + COALESCE ( a.jukor_kapitalisasi, 0 ) + COALESCE ( a.jukor_niladd, 0 ) + COALESCE ( a.nilai_term, 0 ) + COALESCE ( j.SUM_NILRENOV, 0 ) 
                        ) = 0 
                        AND a.nnskoreksi IN ( 
                            'Reklasifikasi Belanja Modal Tahun Anggaran 2024 ke Rekening Penampungan',
                            'Koreksi Belanja Modal menjadi Uang Muka',
                            'Reklasifikasi Ke Beban Dibayar Dimuka',
                            'Pembayaran Utang Belanja Modal yang Aset Telah Diakui',
                            'Pembayaran Utang Belanja Modal - BLUD yang Aset Telah Diakui',
                            'Koreksi Belanja Modal Tahun 2024 (Temuan, Klaim Bank Garansi, dan Koreksi Lainnya)',
                            'Koreksi Belanja Modal sebelum Tahun 2024 (Temuan)',
                            'Koreksi Lebih Catat Nilai',
                            'Reklasifikasi Belanja Modal Tahun 2024 ke Kas yang Dibatasi Penggunaannya' 
                        )
                    ) 
                )
            ORDER BY [a].guid_aset
            OFFSET $offset ROWS FETCH NEXT $perPage ROWS ONLY
        ";

        $results = DB::connection('sqlsrv')->select($query);

        // Optionally, get total count for pagination
        $countQuery = "
            SELECT COUNT(*) as total
            FROM [siera2024].[dbo].[REKON5_B2024] AS [a]
            WHERE [a].[sts] = 1
                AND ( COALESCE ( a.jukor_form, '' ) = '' ) 
                AND (
                    NOT (
                        (
                            COALESCE ( a.harga, 0 ) + COALESCE ( a.jukor_nilai, 0 ) + COALESCE ( a.jukor_kapitalisasi, 0 ) + COALESCE ( a.jukor_niladd, 0 ) + COALESCE ( a.nilai_term, 0 ) 
                        ) = 0 
                        AND a.nnskoreksi IN ( 
                            'Reklasifikasi Belanja Modal Tahun Anggaran 2024 ke Rekening Penampungan',
                            'Koreksi Belanja Modal menjadi Uang Muka',
                            'Reklasifikasi Ke Beban Dibayar Dimuka',
                            'Pembayaran Utang Belanja Modal yang Aset Telah Diakui',
                            'Pembayaran Utang Belanja Modal - BLUD yang Aset Telah Diakui',
                            'Koreksi Belanja Modal Tahun 2024 (Temuan, Klaim Bank Garansi, dan Koreksi Lainnya)',
                            'Koreksi Belanja Modal sebelum Tahun 2024 (Temuan)',
                            'Koreksi Lebih Catat Nilai',
                            'Reklasifikasi Belanja Modal Tahun 2024 ke Kas yang Dibatasi Penggunaannya' 
                        )
                    ) 
                )
        ";
        $total = DB::connection('sqlsrv')->select($countQuery)[0]->total ?? 0;

        return view('Frontend.IdentifikasiIdle', [
            'results' => $results,
            'total' => $total,
            'perPage' => $perPage,
            'page' => $page
        ]);
    }

}