<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\IdentifikasiAset;
use App\Models\PermohonanAset;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver; 
use Barryvdh\DomPDF\Facade\Pdf;



class AsetController extends Controller
{

    public function backup(Request $request)
    {
        $user = session('user');
        if (!$user) {
            return redirect()->route('login')->withErrors(['login_error' => 'Please log in first.']);
        }

        $userKolok = $user->kolok;
        $perPage = 3000;
        $page = max(1, (int)$request->input('page', 1));
        $offset = ($page - 1) * $perPage;

        // Use parameter binding to avoid SQL injection and improve performance
        $bindings = [
            'userKolok' => $userKolok,
            'offset' => $offset,
            'perPage' => $perPage,
        ];

        // Only select necessary columns, avoid SELECT *
        $query = "
            SELECT
                a.guid_aset as GUID_ASET,
                a.KOLOKSKPD,
                b3.NALOK as NALOKSKPD,
                a.kolok as KOLOK,
                b.NALOK,
                a.KOLOKUPB as KOLOKSEKOLAH,
                b2.nalok as NALOKSEKOLAH,
                a.kobar_108 as KOBAR,
                c.nabar as NABAR,
                a.noreg_108 as NOREG,
                a.ukuran as UKURAN,
                a.satuan as SATUAN,
                a.kondisi as KONDISI,
                a.alamat as ALAMAT,
                a.nojalan as NO_JALAN,
                a.rt as RT,
                a.rw as RW,
                a.KDLURAH as KD_LURAH,
                lurah.nm_kel as KELURAHAN,
                camat.kd_kec as KD_CAMAT,
                camat.nm_kec as KECAMATAN,
                rayon.kd_rayon as KD_RAYON,
                rayon.nm_rayon as RAYON,
                prop.kd_prop as KD_PROV,
                prop.nm_prop as PROVINSI,
                f.nm_asaloleh1 as ASAL_OLEH,
                a.harga as HARGA_PEROLEHAN,
                a.tgloleh as TGL_PEROLEHAN,
                a.nodok as NO_DOKUMEN,
                a.tgldok as TGL_DOKUMEN,
                a.lat as LATITUDE,
                a.lon as LONGITUDE,
                a.penggunaan as PENGGUNAAN,
                a.bahan as BAHAN,
                a.merk as MERK,
                a.tipe as TIPE,
                a.ketmasalah as KETERANGAN,
                COALESCE(a.harga, 0) + COALESCE(a.jukor_nilai, 0) + COALESCE(a.jukor_kapitalisasi, 0) + COALESCE(a.jukor_niladd, 0) + COALESCE(a.nilai_term, 0) + COALESCE(j.SUM_NILRENOV, 0) AS KIB_NIL
            FROM
                siera2024.dbo.REKON5_B2024 AS a WITH (NOLOCK)
                LEFT JOIN bpadmaster.dbo.master_profile AS b ON a.kolok = b.id_kolok
                LEFT JOIN bpadmaster.dbo.master_profile AS b3 ON a.KOLOKSKPD = b3.id_kolok
                LEFT JOIN bpadmaster.dbo.master_profile AS b2 ON a.KOLOKUPB = b2.id_kolok
                LEFT JOIN bpadkobar.dbo.data_nabar AS c ON a.kobar_108 = c.KOBAR
                LEFT JOIN bpadmaster.dbo.glo_asaloleh1 AS f ON a.asaloleh = f.KD_ASALOLEH1
                LEFT JOIN bpadmaster.dbo.glo_wil_kelurahan AS lurah ON a.KDLURAH = lurah.kd_bpad
                LEFT JOIN bpadmaster.dbo.glo_wil_kecamatan AS camat ON lurah.kd_prop = camat.kd_prop AND lurah.kd_rayon = camat.kd_rayon AND lurah.kd_kec = camat.kd_kec
                LEFT JOIN bpadmaster.dbo.glo_wil_rayon AS rayon ON rayon.kd_prop = lurah.kd_prop AND rayon.kd_rayon = lurah.kd_rayon
                LEFT JOIN bpadmaster.dbo.glo_wil_provinsi AS prop ON lurah.kd_prop = prop.kd_prop
                LEFT JOIN (
                    SELECT id_kolok, kd_wil FROM bpadmaster.dbo.master_profile_detail WHERE tahun = 2024
                ) AS h ON a.kolok = h.id_kolok
                LEFT JOIN (
                    SELECT
                        k.kolok,
                        k.kobar_108,
                        k.noreg_108,
                        SUM(nilrenov) AS SUM_NILRENOV
                    FROM
                        siera2024.dbo.REKON5_RENOVASI2024 k
                    GROUP BY
                        k.kolok,
                        k.kobar_108,
                        k.noreg_108
                ) AS j ON a.kolok = j.kolok AND j.kobar_108 = a.kobar_108 AND j.noreg_108 = a.noreg_108
                LEFT JOIN bpadas.dbo.aset_qfatbbar AS r ON a.kobar = r.kobar
            WHERE
                a.sts = 1
                AND a.kolok = :userKolok
                AND (COALESCE(a.jukor_form, '') = '')
                AND (
                    NOT (
                        (
                            COALESCE(a.harga, 0) + COALESCE(a.jukor_nilai, 0) + COALESCE(a.jukor_kapitalisasi, 0) + COALESCE(a.jukor_niladd, 0) + COALESCE(a.nilai_term, 0) + COALESCE(j.SUM_NILRENOV, 0)
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
                AND a.kobar_108 NOT IN (
                    '132020101001', '132020101002', '132020101003', '132020101004', '132020101005', '132020102001', '132020102002', '132020102003', '132020102004', '132020103001', '132020103002', '132020103003', '132020103004', '132020103005', '132020103006', '132020104001', '132020104002', '132020104003', '132020104004', '132020104005', '132020105001', '132020105002', '132020105003', '132020105004', '132020106001', '132020106002', '132020106003', '132020106004', '132020106005', '132020106006', '132020106007', '132020106008', '132020106009', '132020106010', '132020106011', '132020106012', '132020106013', '132020106014', '132020106015', '132020106016', '132020106017', '132020106018', '132020106019', '132020106020', '132020106021', '132020106022', '132020106023', '132020106024', '132020106025', '132020106026', '132020106027', '132020106028', '132020106029', '132020106030', '132020106031', '132020106032', '132020106033', '132020106034', '132020106035', '132020106036', '132020106037', '132020106038', '132020106039', '132020106040', '132020106041', '132020106042', '132020106043', '132020106044', '132020106045', '132020106046', '132020106047', '132020106048', '132020106049', '132020106050', '132020106051', '132020106052', '132020106053', '132020106054', '132020106055', '132020106056', '132020106057', '132020106058', '132020106059', '132020106060', '132020106061', '132020106062', '132020106063', '132020106064', '132020106065', '132020106066', '132020106067', '132020106068', '132020106069', '132020106070', '132020106071', '132020106072', '132020106073', '132020106074', '132020106075', '132020106076', '132020106077', '132020106078', '132020106079', '132020106080', '132020106081', '132020106082', '132020106083', '132020106084', '132020106085', '132020106086', '132020106087', '132020106088', '132020106089', '132020106090', '132020106091', '132020106092', '132020106093', '132020106094', '132020106095', '132020106096', '132020106097', '132020106098', '132020106099', '132020106100', '132020106101', '132020106102', '132020106103', '132020106104', '132020106105', '132020106106', '132020106107', '132020106108', '132020106109', '132010110002', '132010110003', '132010105003', '132010307001', '132010307005'
                )
            ORDER BY a.guid_aset
            OFFSET :offset ROWS FETCH NEXT :perPage ROWS ONLY
        ";

        $results = DB::connection('sqlsrv')->select($query, $bindings);

        // Count query, also use parameter binding
        $countQuery = "
            SELECT COUNT(*) as total
            FROM siera2024.dbo.REKON5_B2024 AS a
            WHERE a.sts = 1
                AND a.kolok = :userKolok
                AND (COALESCE(a.jukor_form, '') = '')
                AND (
                    NOT (
                        (
                            COALESCE(a.harga, 0) + COALESCE(a.jukor_nilai, 0) + COALESCE(a.jukor_kapitalisasi, 0) + COALESCE(a.jukor_niladd, 0) + COALESCE(a.nilai_term, 0)
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
                AND a.kobar_108 NOT IN (
                    '132020101001', '132020101002', '132020101003', '132020101004', '132020101005', '132020102001', '132020102002', '132020102003', '132020102004', '132020103001', '132020103002', '132020103003', '132020103004', '132020103005', '132020103006', '132020104001', '132020104002', '132020104003', '132020104004', '132020104005', '132020105001', '132020105002', '132020105003', '132020105004', '132020106001', '132020106002', '132020106003', '132020106004', '132020106005', '132020106006', '132020106007', '132020106008', '132020106009', '132020106010', '132020106011', '132020106012', '132020106013', '132020106014', '132020106015', '132020106016', '132020106017', '132020106018', '132020106019', '132020106020', '132020106021', '132020106022', '132020106023', '132020106024', '132020106025', '132020106026', '132020106027', '132020106028', '132020106029', '132020106030', '132020106031', '132020106032', '132020106033', '132020106034', '132020106035', '132020106036', '132020106037', '132020106038', '132020106039', '132020106040', '132020106041', '132020106042', '132020106043', '132020106044', '132020106045', '132020106046', '132020106047', '132020106048', '132020106049', '132020106050', '132020106051', '132020106052', '132020106053', '132020106054', '132020106055', '132020106056', '132020106057', '132020106058', '132020106059', '132020106060', '132020106061', '132020106062', '132020106063', '132020106064', '132020106065', '132020106066', '132020106067', '132020106068', '132020106069', '132020106070', '132020106071', '132020106072', '132020106073', '132020106074', '132020106075', '132020106076', '132020106077', '132020106078', '132020106079', '132020106080', '132020106081', '132020106082', '132020106083', '132020106084', '132020106085', '132020106086', '132020106087', '132020106088', '132020106089', '132020106090', '132020106091', '132020106092', '132020106093', '132020106094', '132020106095', '132020106096', '132020106097', '132020106098', '132020106099', '132020106100', '132020106101', '132020106102', '132020106103', '132020106104', '132020106105', '132020106106', '132020106107', '132020106108', '132020106109', '132010110002', '132010110003', '132010105003', '132010307001', '132010307005'
                )
        ";
        $total = DB::connection('sqlsrv')->select($countQuery, ['userKolok' => $userKolok])[0]->total ?? 0;

        return view('Frontend.IdentifikasiIdle', [
            'results' => $results,
            'total' => $total,
            'perPage' => $perPage,
            'page' => $page,
        ]);
    }

    public function index(Request $request)
    {
        $user = session('user');
        if (!$user) {
            return redirect()->route('login')->withErrors(['login_error' => 'Please log in first.']);
        }

        // Mulai query dengan kolok user
        $query = IdentifikasiAset::query()
            ->where('kolok', $user->kolok); // filter sesuai kolok user

        // Ambil hasil (pakai pagination kalau perlu)
        $asets = $query->limit(100)->get();

        $tahunList = IdentifikasiAset::whereNotNull('tgloleh')
        ->selectRaw('YEAR(tgloleh) as tahun') // pastikan alias 'tahun' dipakai
        ->groupByRaw('YEAR(tgloleh)')
        ->orderByRaw('YEAR(tgloleh) DESC')
        ->pluck('tahun');

        return view('Frontend.IdentifikasiIdle', compact('asets', 'tahunList'));
    }

    public function form(Request $request)
    {
        $user = session('user');
        if (!$user) {
            return redirect()->route('login')->withErrors(['login_error' => 'Please log in first.']);
        }

        // Ambil guid_aset dari query, tidak peduli huruf besar/kecil
        $guidAset = $request->query('GUID_ASET') ?? $request->query('guid_aset');
        $aset = null;

        if ($guidAset) {
            $aset = DB::connection('sqlsrv')->table('REKON5_B2024 as a')
                ->leftJoin('bpadmaster.dbo.master_profile as b', 'a.kolok', '=', 'b.id_kolok')
                ->leftJoin('bpadmaster.dbo.master_profile as b3', 'a.KOLOKSKPD', '=', 'b3.id_kolok')
                ->leftJoin('bpadmaster.dbo.master_profile as b2', 'a.KOLOKUPB', '=', 'b2.id_kolok')
                ->leftJoin('bpadkobar.dbo.data_nabar as c', 'a.kobar_108', '=', 'c.KOBAR')
                ->leftJoin('bpadmaster.dbo.glo_asaloleh1 as f', 'a.asaloleh', '=', 'f.KD_ASALOLEH1')
                ->leftJoin('bpadmaster.dbo.glo_wil_kelurahan as lurah', 'a.KDLURAH', '=', 'lurah.kd_bpad')
                ->leftJoin('bpadmaster.dbo.glo_wil_kecamatan as camat', function($join) {
                    $join->on('lurah.kd_prop', '=', 'camat.kd_prop')
                        ->on('lurah.kd_rayon', '=', 'camat.kd_rayon')
                        ->on('lurah.kd_kec', '=', 'camat.kd_kec');
                })
                ->leftJoin('bpadmaster.dbo.glo_wil_rayon as rayon', function($join) {
                    $join->on('rayon.kd_prop', '=', 'lurah.kd_prop')
                        ->on('rayon.kd_rayon', '=', 'lurah.kd_rayon');
                })
                ->leftJoin('bpadmaster.dbo.glo_wil_provinsi as prop', 'lurah.kd_prop', '=', 'prop.kd_prop')
                ->select(
                    'a.*',
                    'b.NALOK',
                    'b3.NALOK as NALOKSKPD',
                    'b2.nalok as NALOKSEKOLAH',
                    'c.nabar as NABAR',
                    'f.nm_asaloleh1 as ASAL_OLEH',
                    'tgloleh as TGL_PEROLEHAN',
                    'harga as HARGA_PEROLEHAN',
                    'lurah.nm_kel as KELURAHAN',
                    'camat.nm_kec as KECAMATAN',
                    'rayon.nm_rayon as RAYON',
                    'prop.nm_prop as PROVINSI'
                )
                ->whereRaw('LOWER(a.guid_aset) = LOWER(?)', [$guidAset])
                ->first();
        }

        return view('Frontend.FormAset', [
            'guid_aset' => $guidAset,
            'aset' => $aset
        ]);
    }

    public function tambah(Request $request)
    {
        $user = session('user');
        if (!$user) {
            return redirect()->route('login')->withErrors(['login_error' => 'Please log in first.']);
        }

        $userKolok = $user->kolok;
        $perPage   = 3000;
        $page      = max(1, (int) $request->input('page', 1));
        $offset    = ($page - 1) * $perPage;

        $bindings = [
            'userKolok' => $userKolok,
            'offset'    => $offset,
            'perPage'   => $perPage,
        ];

        // Query utama
        $query = "
            SELECT
                a.guid_aset as GUID_ASET,
                a.KOLOKSKPD,
                b3.NALOK as NALOKSKPD,
                a.kolok as KOLOK,
                b.NALOK,
                a.KOLOKUPB as KOLOKSEKOLAH,
                b2.nalok as NALOKSEKOLAH,
                a.kobar_108 as KOBAR,
                c.nabar as NABAR,
                a.noreg_108 as NOREG,
                a.ukuran as UKURAN,
                a.satuan as SATUAN,
                a.kondisi as KONDISI,
                a.alamat as ALAMAT,
                a.nojalan as NO_JALAN,
                a.rt as RT,
                a.rw as RW,
                a.KDLURAH as KD_LURAH,
                lurah.nm_kel as KELURAHAN,
                camat.kd_kec as KD_CAMAT,
                camat.nm_kec as KECAMATAN,
                rayon.kd_rayon as KD_RAYON,
                rayon.nm_rayon as RAYON,
                prop.kd_prop as KD_PROV,
                prop.nm_prop as PROVINSI,
                f.nm_asaloleh1 as ASAL_OLEH,
                a.harga as HARGA_PEROLEHAN,
                a.tgloleh as TGL_PEROLEHAN,
                a.nodok as NO_DOKUMEN,
                a.tgldok as TGL_DOKUMEN,
                a.lat as LATITUDE,
                a.lon as LONGITUDE,
                a.penggunaan as PENGGUNAAN,
                b4.NM_BAHAN as BAHAN,
                a.merk as MERK,
                a.tipe as TIPE,
                a.ketmasalah as KETERANGAN,
                COALESCE(a.harga, 0) + COALESCE(a.jukor_nilai, 0) + COALESCE(a.jukor_kapitalisasi, 0) 
                + COALESCE(a.jukor_niladd, 0) + COALESCE(a.nilai_term, 0) + COALESCE(j.SUM_NILRENOV, 0) AS KIB_NIL
            FROM
                siera2024.dbo.REKON5_B2024 AS a WITH (NOLOCK)
                LEFT JOIN bpadmaster.dbo.master_profile AS b ON a.kolok = b.id_kolok
                LEFT JOIN bpadmaster.dbo.master_profile AS b3 ON a.KOLOKSKPD = b3.id_kolok
                LEFT JOIN bpadmaster.dbo.master_profile AS b2 ON a.KOLOKUPB = b2.id_kolok
                LEFT JOIN bpadmaster.dbo.ASET_QBAHAN AS b4 ON a.bahan = b4.KD_BAHAN
                LEFT JOIN bpadkobar.dbo.data_nabar AS c ON a.kobar_108 = c.KOBAR
                LEFT JOIN bpadmaster.dbo.glo_asaloleh1 AS f ON a.asaloleh = f.KD_ASALOLEH1
                LEFT JOIN bpadmaster.dbo.glo_wil_kelurahan AS lurah ON a.KDLURAH = lurah.kd_bpad
                LEFT JOIN bpadmaster.dbo.glo_wil_kecamatan AS camat 
                    ON lurah.kd_prop = camat.kd_prop 
                    AND lurah.kd_rayon = camat.kd_rayon 
                    AND lurah.kd_kec = camat.kd_kec
                LEFT JOIN bpadmaster.dbo.glo_wil_rayon AS rayon 
                    ON rayon.kd_prop = lurah.kd_prop 
                    AND rayon.kd_rayon = lurah.kd_rayon
                LEFT JOIN bpadmaster.dbo.glo_wil_provinsi AS prop ON lurah.kd_prop = prop.kd_prop
                LEFT JOIN (
                    SELECT
                        k.kolok,
                        k.kobar_108,
                        k.noreg_108,
                        SUM(nilrenov) AS SUM_NILRENOV
                    FROM
                        siera2024.dbo.REKON5_RENOVASI2024 k
                    GROUP BY
                        k.kolok,
                        k.kobar_108,
                        k.noreg_108
                ) AS j 
                    ON a.kolok = j.kolok 
                    AND j.kobar_108 = a.kobar_108 
                    AND j.noreg_108 = a.noreg_108
            WHERE
                a.sts = 1
                AND a.kolok = :userKolok
                AND (COALESCE(a.jukor_form, '') = '')
                AND NOT EXISTS (
                    SELECT 1 
                    FROM bpad_simple_web.dbo.identifikasi_aset ia
                    WHERE ia.guid_aset = a.guid_aset
                )
                AND (
                    NOT (
                        (
                            COALESCE(a.harga, 0) + COALESCE(a.jukor_nilai, 0) + COALESCE(a.jukor_kapitalisasi, 0) 
                            + COALESCE(a.jukor_niladd, 0) + COALESCE(a.nilai_term, 0) + COALESCE(j.SUM_NILRENOV, 0)
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
                AND a.kobar_108 NOT IN (
                    '132020101001','132020101002','132020101003','132020101004','132020101005',
                    '132020102001','132020102002','132020102003','132020102004',
                    '132020103001','132020103002','132020103003','132020103004','132020103005','132020103006',
                    '132020104001','132020104002','132020104003','132020104004','132020104005',
                    '132020105001','132020105002','132020105003','132020105004',
                    '132020106001','132020106002','132020106003','132020106004','132020106005',
                    '132020106006','132020106007','132020106008','132020106009','132020106010',
                    '132020106011','132020106012','132020106013','132020106014','132020106015',
                    '132020106016','132020106017','132020106018','132020106019','132020106020',
                    '132020106021','132020106022','132020106023','132020106024','132020106025',
                    '132020106026','132020106027','132020106028','132020106029','132020106030',
                    '132020106031','132020106032','132020106033','132020106034','132020106035',
                    '132020106036','132020106037','132020106038','132020106039','132020106040',
                    '132020106041','132020106042','132020106043','132020106044','132020106045',
                    '132020106046','132020106047','132020106048','132020106049','132020106050',
                    '132020106051','132020106052','132020106053','132020106054','132020106055',
                    '132020106056','132020106057','132020106058','132020106059','132020106060',
                    '132020106061','132020106062','132020106063','132020106064','132020106065',
                    '132020106066','132020106067','132020106068','132020106069','132020106070',
                    '132020106071','132020106072','132020106073','132020106074','132020106075',
                    '132020106076','132020106077','132020106078','132020106079','132020106080',
                    '132020106081','132020106082','132020106083','132020106084','132020106085',
                    '132020106086','132020106087','132020106088','132020106089','132020106090',
                    '132020106091','132020106092','132020106093','132020106094','132020106095',
                    '132020106096','132020106097','132020106098','132020106099','132020106100',
                    '132020106101','132020106102','132020106103','132020106104','132020106105',
                    '132020106106','132020106107','132020106108','132020106109',
                    '132010110002','132010110003','132010105003','132010307001','132010307005'
                )
            ORDER BY a.guid_aset
            OFFSET :offset ROWS FETCH NEXT :perPage ROWS ONLY
        ";

        $results = DB::connection('sqlsrv')->select($query, $bindings);

        // Ambil detail aset yang dipilih
        $selectedAset = null;
        if ($request->filled('guid_aset')) {
            $selectedAset = collect($results)->firstWhere('GUID_ASET', $request->guid_aset);
        }

        // Count query
        $countQuery = "
            SELECT COUNT(*) as total
            FROM siera2024.dbo.REKON5_B2024 AS a
            LEFT JOIN bpadkobar.dbo.data_nabar AS c ON a.kobar_108 = c.KOBAR
            WHERE a.sts = 1
                AND a.kolok = :userKolok
                AND (COALESCE(a.jukor_form, '') = '')
                AND NOT EXISTS (
                    SELECT 1 
                    FROM bpad_simple_web.dbo.identifikasi_aset ia
                    WHERE ia.guid_aset = a.guid_aset
                )
                AND (
                    NOT (
                        (
                            COALESCE(a.harga, 0) + COALESCE(a.jukor_nilai, 0) 
                            + COALESCE(a.jukor_kapitalisasi, 0) + COALESCE(a.jukor_niladd, 0) 
                            + COALESCE(a.nilai_term, 0)
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
                AND a.kobar_108 NOT IN (
                    '132020101001','132020101002','132020101003','132020101004','132020101005',
                    '132020102001','132020102002','132020102003','132020102004',
                    '132020103001','132020103002','132020103003','132020103004','132020103005','132020103006',
                    '132020104001','132020104002','132020104003','132020104004','132020104005',
                    '132020105001','132020105002','132020105003','132020105004',
                    '132020106001','132020106002','132020106003','132020106004','132020106005',
                    '132020106006','132020106007','132020106008','132020106009','132020106010',
                    '132020106011','132020106012','132020106013','132020106014','132020106015',
                    '132020106016','132020106017','132020106018','132020106019','132020106020',
                    '132020106021','132020106022','132020106023','132020106024','132020106025',
                    '132020106026','132020106027','132020106028','132020106029','132020106030',
                    '132020106031','132020106032','132020106033','132020106034','132020106035',
                    '132020106036','132020106037','132020106038','132020106039','132020106040',
                    '132020106041','132020106042','132020106043','132020106044','132020106045',
                    '132020106046','132020106047','132020106048','132020106049','132020106050',
                    '132020106051','132020106052','132020106053','132020106054','132020106055',
                    '132020106056','132020106057','132020106058','132020106059','132020106060',
                    '132020106061','132020106062','132020106063','132020106064','132020106065',
                    '132020106066','132020106067','132020106068','132020106069','132020106070',
                    '132020106071','132020106072','132020106073','132020106074','132020106075',
                    '132020106076','132020106077','132020106078','132020106079','132020106080',
                    '132020106081','132020106082','132020106083','132020106084','132020106085',
                    '132020106086','132020106087','132020106088','132020106089','132020106090',
                    '132020106091','132020106092','132020106093','132020106094','132020106095',
                    '132020106096','132020106097','132020106098','132020106099','132020106100',
                    '132020106101','132020106102','132020106103','132020106104','132020106105',
                    '132020106106','132020106107','132020106108','132020106109',
                    '132010110002','132010110003','132010105003','132010307001','132010307005'
                )
        ";

        $total = DB::connection('sqlsrv')->select($countQuery, ['userKolok' => $userKolok])[0]->total ?? 0;

        return view('Frontend.AsetForm', [
            'listAset'     => $results,
            'selectedAset' => $selectedAset,
            'total'        => $total,
            'perPage'      => $perPage,
            'page'         => $page,
        ]);
    }


    public function store(Request $request)
    {
        $manager = new ImageManager(new Driver());
        $validated = $request->validate([
            'image' => 'required|image|mimes:jpg,jpeg,png|max:500',
            'image2' => 'image|mimes:jpg,jpeg,png|max:500',
            'image3' => 'image|mimes:jpg,jpeg,png|max:500',
            'image4' => 'image|mimes:jpg,jpeg,png|max:500',
            'image5' => 'image|mimes:jpg,jpeg,png|max:500',
            'nabar' => 'required|string|max:255',
            'main_image' => 'required|in:image,image2,image3,image4,image5',
        ]);

        $imageFields = ['image', 'image2', 'image3', 'image4', 'image5'];
        $storedPaths = [];

        foreach ($imageFields as $field) {
            if ($request->hasFile($field)) {
                $foto = $request->file($field);
                $filename = uniqid() . '.jpg'; // Simpan sebagai JPG setelah kompresi

                // Proses gambar dengan Intervention Image (direkomendasikan)
                $image = $manager->read($foto->getPathname());
                $image->toJpeg(70); // Kompresi ke JPEG dengan kualitas 70%

                // Simpan sementara untuk cek ukuran
                $tempPath = storage_path("app/temp_{$filename}");
                $image->save($tempPath);

                $finalSize = filesize($tempPath);
                if ($finalSize > 500 * 1024) { // Cek ukuran setelah kompresi (500 KB)
                    unlink($tempPath);
                    return back()->withErrors(["{$field}" => "Gambar {$field} terlalu besar setelah dikompresi (Max 500KB)."])->withInput();
                }

                // Simpan permanen ke storage
                Storage::put("uploads/{$filename}", file_get_contents($tempPath));
                unlink($tempPath); // Hapus file sementara

                $storedPaths[$field] = "uploads/{$filename}";
            } else {
                $storedPaths[$field] = null; // Set null jika tidak ada file diupload
            }
        }

        // Tentukan path untuk gambar utama berdasarkan pilihan radio button
        $mainImageInputName = $request->input('main_image'); // e.g., 'image', 'image2'
        $mainImagePath = $storedPaths[$mainImageInputName]; // Ambil path yang sudah disimpan


        $data = IdentifikasiAset::create([
            'guid_aset' => $request->guid_aset,
            'kolok' => $request->kolok,
            'kolokskpd' => $request->kolokskpd,
            'nalok' => $request->nalok,
            'kobar_108' => $request->kobar_108,
            'nabar' => $request->nabar,
            'noreg_108' => $request->noreg_108,
            'bahan' => $request->bahan,
            'merk' => $request->merk,
            'tipe' => $request->tipe,
            'harga' => $request->harga,
            'tgloleh' => $request->tgloleh,
            'kondisi' => $request->kondisi,
            'deskripsi' => $request->deskripsi,
            'penggunaan_bmd' => $request->penggunaan_bmd,
            'image' => $storedPaths['image'],
            'image2' => $storedPaths['image2'],
            'image3' => $storedPaths['image3'],
            'image4' => $storedPaths['image4'],
            'image5' => $storedPaths['image5'],
            'main_image' => $mainImagePath,
            'validasi_kepalaskpd' => 'Belum Tervalidasi',
        ]);
        return redirect()->back()->with('success', 'Aset berhasil diajukan!');
        // return response()->json([
        //     'message' => 'Aset berhasil disimpan!',
        //     'data' => $data,
        // ]);
    }

    public function validasi(Request $request)
    {
        $user = session('user');
        if (!$user) {
            return redirect()->route('login')->withErrors(['login_error' => 'Please log in first.']);
        }

        // Mulai query dengan kolok user
        $query = IdentifikasiAset::query()
            ->where('validasi_kepalaskpd', 'Belum Tervalidasi')
            ->where('kolok', $user->kolok); // filter sesuai kolok user

        // Filter: Nama Barang
        if ($request->filled('nabar')) {
            $query->where('nabar', 'like', '%' . $request->nabar . '%');
        }

        // Filter: Merk
        if ($request->filled('merk')) {
            $query->where('merk', 'like', '%' . $request->merk . '%');
        }

        // Filter: Kondisi
        if ($request->filled('kondisi')) {
            $query->where('kondisi', $request->kondisi);
        }

        // Filter: Tahun (dari tgloleh)
        if ($request->filled('tahun')) {
            $query->whereYear('tgloleh', $request->tahun);
        }

        // Ambil hasil (pakai pagination kalau perlu)
        $asets = $query->limit(100)->get();

        $tahunList = IdentifikasiAset::whereNotNull('tgloleh')
        ->selectRaw('YEAR(tgloleh) as tahun') // pastikan alias 'tahun' dipakai
        ->groupByRaw('YEAR(tgloleh)')
        ->orderByRaw('YEAR(tgloleh) DESC')
        ->pluck('tahun');

        return view('Frontend.ValidasiKepala', compact('asets', 'tahunList'));
    }

    public function validasiKepala(Request $request)
    {
        $user = session('user');
        if (!$user) {
            return redirect()->route('login')->withErrors(['login_error' => 'Silakan login terlebih dahulu.']);
        }

        $guidAset = $request->guid_aset;

        $updated = DB::connection('sqlsrv_2')
            ->table('identifikasi_aset')
            ->where('guid_aset', $guidAset)
            ->update(['validasi_kepalaskpd' => 'Validasi']);

        if ($updated) {
            return back()->with('success', 'Aset berhasil divalidasi.');
        } else {
            return back()->withErrors(['error' => 'Aset gagal divalidasi atau tidak ditemukan.']);
        }
    }

     public function identifikasiDetail(Request $request, $guid_aset)
    {
        // Cek login
        $user = session('user');
        if (!$user) {
            return redirect()->route('login')->withErrors(['login_error' => 'Silakan login terlebih dahulu.']);
        }

        // Cek apakah data aset ada
        $data = DB::connection('sqlsrv_2')
            ->table('identifikasi_aset')
            ->where('guid_aset', $guid_aset)
            ->first();

        if (!$data) {
            return redirect()->back()->withErrors(['error' => 'Aset tidak ditemukan.']);
        }

        // Ambil nilai sekarang, default ke 0 jika null
        $currentCount = is_numeric($data->jumlah_dilihat) ? (int)$data->jumlah_dilihat : 0;

        // Update jumlah_dilihat
        DB::connection('sqlsrv_2')
            ->table('identifikasi_aset')
            ->where('guid_aset', $guid_aset)
            ->update([
                'jumlah_dilihat' => $currentCount + 1
            ]);

        // Ambil ulang data yang sudah diperbarui via model Eloquent
        $aset = IdentifikasiAset::where('guid_aset', $guid_aset)->first();

        // Tahun list
        $tahunList = IdentifikasiAset::whereNotNull('tgloleh')
            ->selectRaw('YEAR(tgloleh) as tahun')
            ->groupByRaw('YEAR(tgloleh)')
            ->orderByRaw('YEAR(tgloleh) DESC')
            ->pluck('tahun');

        return view('Frontend.IdentifikasiAsetDetail', compact('aset', 'tahunList'));
    }

    public function show(Request $request)
    {
        $user = session('user');
        if (!$user) {
            return redirect()->route('login')->withErrors(['login_error' => 'Please log in first.']);
        }

         $query = IdentifikasiAset::query()
        ->where('validasi_kepalaskpd', 'Validasi');

        // Filter: Nama Barang
        if ($request->filled('nabar')) {
            $query->where('nabar', 'like', '%' . $request->nabar . '%');
        }

        // Filter: Merk
        if ($request->filled('merk')) {
            $query->where('merk', 'like', '%' . $request->merk . '%');
        }

        // Filter: Kondisi
        if ($request->filled('kondisi')) {
            $query->where('kondisi', $request->kondisi);
        }

        // Filter: Tahun (dari tgloleh)
        if ($request->filled('tahun')) {
            $query->whereYear('tgloleh', $request->tahun);
        }

        // Ambil hasil (pakai pagination kalau perlu)
        $asets = $query->limit(100)->get();

        $tahunList = IdentifikasiAset::whereNotNull('tgloleh')
        ->selectRaw('YEAR(tgloleh) as tahun') // pastikan alias 'tahun' dipakai
        ->groupByRaw('YEAR(tgloleh)')
        ->orderByRaw('YEAR(tgloleh) DESC')
        ->pluck('tahun');

        return view('Frontend.DisplayAset', compact('asets', 'tahunList'));
    }

    public function detail(Request $request, $guid_aset)
    {
        // Cek login
        $user = session('user');
        if (!$user) {
            return redirect()->route('login')->withErrors(['login_error' => 'Silakan login terlebih dahulu.']);
        }

        // Cek apakah data aset ada
        $data = DB::connection('sqlsrv_2')
            ->table('identifikasi_aset')
            ->where('guid_aset', $guid_aset)
            ->first();

        if (!$data) {
            return redirect()->back()->withErrors(['error' => 'Aset tidak ditemukan.']);
        }

        // Ambil nilai sekarang, default ke 0 jika null
        $currentCount = is_numeric($data->jumlah_dilihat) ? (int)$data->jumlah_dilihat : 0;

        // Update jumlah_dilihat
        DB::connection('sqlsrv_2')
            ->table('identifikasi_aset')
            ->where('guid_aset', $guid_aset)
            ->update([
                'jumlah_dilihat' => $currentCount + 1
            ]);

        // Ambil ulang data yang sudah diperbarui via model Eloquent
        $aset = IdentifikasiAset::where('guid_aset', $guid_aset)->first();

        // Tahun list
        $tahunList = IdentifikasiAset::whereNotNull('tgloleh')
            ->selectRaw('YEAR(tgloleh) as tahun')
            ->groupByRaw('YEAR(tgloleh)')
            ->orderByRaw('YEAR(tgloleh) DESC')
            ->pluck('tahun');

        // Hitung jumlah permohonan untuk tiap aset
        $jumlahPermohonan = PermohonanAset::select('guid_aset', DB::raw('COUNT(*) as total'))
            ->groupBy('guid_aset')
            ->pluck('total', 'guid_aset');

        // Tambahkan ke objek aset
        $aset->jumlahPermohonan = $jumlahPermohonan->get($aset->guid_aset, 0);

        // Cek apakah user sudah pernah mengajukan permohonan untuk aset ini
        $sudahMengajukan = PermohonanAset::where('guid_aset', $guid_aset)
            ->where('kolok', $user->kolok)
            ->exists();

        // Kirim flag ke view untuk disable/enable button
        return view('Frontend.RincianAset', compact('aset', 'tahunList', 'sudahMengajukan'));
    }


    public function permohonan(Request $request)
    {
        $user = session('user');
        if (!$user) {
            return redirect()->route('login')->withErrors(['login_error' => 'Silakan login terlebih dahulu.']);
        }

        // Validasi input sederhana
        $request->validate([
            'guid_aset' => 'required|string',
            'kolokskpd' => 'nullable|string',
            'kolok' => 'nullable|string',
            'nalok' => 'nullable|string',
            'kobar_108' => 'nullable|string',
            'nabar' => 'nullable|string',
            'noreg_108' => 'nullable|string',
            'bahan' => 'nullable|string',
            'merk' => 'nullable|string',
            'tipe' => 'nullable|string',
            'harga' => 'nullable|numeric',
            'tgloleh' => 'nullable|date',
            'kondisi' => 'nullable|string',
            'deskripsi' => 'nullable|string',
            'penggunaan_bmd' => 'nullable|string',
            'alasan_permohonan' => 'nullable|string',
            'disetujui' => 'nullable|string'
        ]);

        // Ambil data request (tanpa 'disetujui' dulu)
        $data = $request->only([
            'guid_aset', 'kolokskpd', 'kolok', 'nalok', 'kobar_108', 'nabar', 'noreg_108',
            'bahan', 'merk', 'tipe', 'harga', 'tgloleh', 'kondisi', 'deskripsi',
            'penggunaan_bmd', 'alasan_permohonan'
        ]);

        // Set default nilai 'disetujui'
        $data['disetujui'] = $request->input('disetujui') ?? 'Menunggu Konfirmasi';

        // dd($data);
        try {
            PermohonanAset::create($data);
            return back()->with('success', 'Permohonan Aset berhasil diajukan.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal menyimpan data: ' . $e->getMessage()]);
        }
    }

    public function permohonanDetail(Request $request)
    {
        $user = session('user');
        if (!$user) {
            return redirect()->route('login')->withErrors(['login_error' => 'Please log in first.']);
        }

        $userKolok = $user->kolok;

        // Ambil semua GUID aset yang memiliki permohonan
        $asetDenganPermohonan = PermohonanAset::select('guid_aset')->distinct()->pluck('guid_aset');

        // Ambil data aset yang divalidasi, memiliki permohonan, dan sesuai kolok user
        $asets = IdentifikasiAset::whereIn('guid_aset', $asetDenganPermohonan)
            ->where('kolok', $userKolok) // filter berdasarkan kolok user
            ->get();

        // Hitung jumlah permohonan untuk tiap aset
        $jumlahPermohonan = PermohonanAset::select('guid_aset', DB::raw('COUNT(*) as total'))
            ->groupBy('guid_aset')
            ->pluck('total', 'guid_aset'); 

        // Daftar tahun untuk filter (opsional)
        $tahunList = IdentifikasiAset::whereNotNull('tgloleh')
            ->selectRaw('YEAR(tgloleh) as tahun')
            ->groupByRaw('YEAR(tgloleh)')
            ->orderByRaw('YEAR(tgloleh) DESC')
            ->pluck('tahun');

        return view('Frontend.PermohonanAset', compact('asets', 'tahunList', 'jumlahPermohonan'));
    }

    public function permohonanAset(Request $request, $guidAset = null)
    {
        $user = session('user');
        if (!$user) {
            return redirect()->route('login')->withErrors(['login_error' => 'Please log in first.']);
        }

        $userKolok = $user->kolok;

        // Base query: aset milik user yang divalidasi dan punya permohonan
        $query = IdentifikasiAset::where('kolok', $userKolok)
            ->where('validasi_kepalaskpd', 'Validasi')
            ->whereHas('permohonan')
            ->with(['permohonan' => function($q) {
                $q->orderBy('id');
            }]);

        // Kalau ada GUID di URL, filter juga
        if ($guidAset) {
            $query->where('guid_aset', $guidAset);
        }

        $asets = $query->get();

        foreach ($asets as $aset) {
            // Cek apakah sudah ada permohonan yang disetujui
            $adaYangDisetujui = $aset->permohonan->contains(function($permohonan) {
                return $permohonan->disetujui === 'Disetujui';
            });
            $aset->adaYangDisetujui = $adaYangDisetujui;

            // Jika sudah ada yang disetujui, update permohonan lain (selain yang disetujui) menjadi 'Tidak Disetujui'
            if ($adaYangDisetujui) {
                foreach ($aset->permohonan as $permohonan) {
                    if ($permohonan->disetujui !== 'Disetujui') {
                        // Update status ke 'Tidak Disetujui' jika belum
                        if ($permohonan->disetujui !== 'Tidak Disetujui') {
                            \App\Models\PermohonanAset::where('id', $permohonan->id)
                                ->update(['disetujui' => 'Tidak Disetujui']);
                            $permohonan->disetujui = 'Tidak Disetujui'; // update di objek agar konsisten di view
                        }
                    }
                }
            }
        }

        // Daftar tahun untuk filter
        $tahunList = IdentifikasiAset::whereNotNull('tgloleh')
            ->selectRaw('YEAR(tgloleh) as tahun')
            ->groupByRaw('YEAR(tgloleh)')
            ->orderByRaw('YEAR(tgloleh) DESC')
            ->pluck('tahun');

        return view('Frontend.DetailPermohonanAset', compact('asets', 'tahunList'));
    }

    public function validasiPermohonan(Request $request)
    {
        $user = session('user');
        if (!$user) {
            return redirect()->route('login')->withErrors(['login_error' => 'Silakan login terlebih dahulu.']);
        }

        $data = $request->input('data', []);

        if (!is_array($data)) {
            return back()->withErrors(['error' => 'Format data tidak valid.']);
        }

        $updatedCount = 0;

        foreach ($data as $item) {
            // Validasi input yang dibutuhkan
            if (
                isset($item['id'], $item['disetujui']) &&
                in_array($item['disetujui'], ['Disetujui', 'Tidak Disetujui'])
            ) {
                $updated = DB::connection('sqlsrv_2')
                    ->table('permohonan_aset')
                    ->where('id', $item['id']) // gunakan kolom ID unik
                    ->update(['disetujui' => $item['disetujui']]);

                $updatedCount += $updated;
            }
        }

        if ($updatedCount > 0) {
            return back()->with('success', 'Beberapa permohonan berhasil divalidasi.');
        } else {
            return back()->withErrors(['error' => 'Tidak ada data yang berhasil divalidasi.']);
        }
    }

    public function cetakBast(Request $request)
    {
        $user = session('user');
        if (!$user) {
            return redirect()->route('login')->withErrors(['login_error' => 'Silakan login terlebih dahulu.']);
        }

        // Ambil semua permohonan aset
        $permohonan = DB::connection('sqlsrv_2')
            ->table('permohonan_aset as p')
            ->leftJoin('bpadmaster.dbo.master_profile as m', 'p.kolok', '=', 'm.id_kolok')
            ->select(
                'p.id',
                'p.guid_aset',
                'p.kobar_108',
                'p.nabar',
                'p.noreg_108',
                'p.tgloleh',
                'p.bahan',
                'p.tipe',
                'p.merk',
                'p.harga',
                'p.alasan_permohonan',
                'p.disetujui',
                'p.kolok',
                'm.nalok'
            )
            ->get();

        // Ambil data kolok asli dari pemilik barang
        $identifikasi = DB::connection('sqlsrv_2')
            ->table('identifikasi_aset as i')
            ->leftJoin('bpadmaster.dbo.master_profile as m', 'i.kolok', '=', 'm.id_kolok')
            ->select(
                'i.guid_aset',
                'i.kolok',
                'm.nalok'
            )
            ->get();

        // Gabungkan ke hasil permohonan
        $allData = $permohonan->map(function ($item) use ($identifikasi) {
            $pemilik = $identifikasi->firstWhere('guid_aset', $item->guid_aset);
            if ($pemilik) {
                return [
                    'id' => $item->id,
                    'guid_aset' => $item->guid_aset,
                    'kobar_108' => $item->kobar_108,
                    'nabar' => $item->nabar,
                    'noreg_108' => $item->noreg_108,
                    'tgloleh' => $item->tgloleh,
                    'bahan' => $item->bahan,
                    'tipe' => $item->tipe,
                    'merk' => $item->merk,
                    'harga' => $item->harga,
                    'alasan_permohonan' => $item->alasan_permohonan,
                    'disetujui' => $item->disetujui,
                    // kolok & nalok pemohon
                    'kolok' => $item->kolok,
                    'nalok' => $item->nalok,
                    // kolok & nalok pemilik barang
                    'pemilik_kolok' => $pemilik->kolok,
                    'pemilik_nalok' => $pemilik->nalok,
                ];
            }
            return (array) $item;
        });

        // Group berdasarkan kolok pemohon DAN kolok pemilik
        $grouped = $allData->groupBy('kolok')->merge(
            $allData->groupBy('pemilik_kolok')
        );

        return view('Frontend.CetakBast', [
            'grouped' => $grouped
        ]);
    }


    public function cetak($kolok)
    {
        // Ambil data permohonan yang disetujui saja sesuai kolok
        $items = PermohonanAset::where('kolok', $kolok)
            ->where('disetujui', 'Disetujui')
            ->get();

        $pdf = PDF::loadView('Frontend.Cetak.bast', compact('items', 'kolok'));
        return $pdf->download("BAST_$kolok.pdf");
    }

}