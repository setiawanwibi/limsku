<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Buku Log Penggunaan Alat Laboratorium - BBPOM Bandar Lampung</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 15mm;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            color: #1a1a1a;
            margin: 0;
            padding: 10px;
        }
        .header-table {
            width: 100%;
            border-bottom: 2px solid #000;
            margin-bottom: 15px;
            padding-bottom: 8px;
        }
        .header-table td {
            vertical-align: middle;
        }
        .kop-instansi {
            text-align: center;
        }
        .kop-instansi h3 {
            margin: 0;
            font-size: 15px;
            text-transform: uppercase;
        }
        .kop-instansi h4 {
            margin: 2px 0;
            font-size: 13px;
        }
        .kop-instansi p {
            margin: 2px 0;
            font-size: 10px;
            color: #333;
        }
        .info-box {
            margin-bottom: 12px;
            border: 1px solid #999;
            padding: 8px 12px;
            background: #fafafa;
        }
        .info-box table {
            width: 100%;
            border-collapse: collapse;
        }
        .info-box td {
            padding: 3px 6px;
            font-size: 10.5px;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .data-table th, .data-table td {
            border: 1px solid #333;
            padding: 6px 8px;
            text-align: left;
            font-size: 10px;
        }
        .data-table th {
            background-color: #f2f2f2;
            text-align: center;
            font-weight: bold;
            text-transform: uppercase;
        }
        .text-center { text-align: center !important; }
        .ttd-table {
            width: 100%;
            margin-top: 25px;
            border-collapse: collapse;
        }
        .ttd-table td {
            text-align: center;
            vertical-align: top;
            width: 50%;
            font-size: 11px;
        }
        .no-print {
            margin-bottom: 15px;
            text-align: right;
        }
        .btn-print {
            padding: 6px 14px;
            background: #0284c7;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
        }
        @media print {
            .no-print { display: none !important; }
        }
    </style>
</head>
<body>

    <div class="no-print">
        <button class="btn-print" onclick="window.print();">Cetak / Simpan PDF</button>
        <button class="btn-print" style="background: #64748b;" onclick="window.close();">Tutup</button>
    </div>

    <!-- Kop Surat Laboratorium -->
    <table class="header-table">
        <tr>
            <td style="width: 80px; text-align: center;">
                <img src="<?php echo base_url('assets/img/limsku-logo.png'); ?>" alt="Logo BPOM" style="height: 60px; width: 60px; object-fit: contain;">
            </td>
            <td class="kop-instansi">
                <h3>BADAN PENGAWAS OBAT DAN MAKANAN</h3>
                <h4>BALAI BESAR PENGAWAS OBAT DAN MAKANAN DI BANDAR LAMPUNG</h4>
                <p>Laboratorium Pengujian Obat dan Makanan | Sistem Informasi Manajemen Laboratorium (LIMSKU)</p>
                <p style="font-weight: bold; margin-top: 4px;">CATATAN BUKU LOG PENGGUNAAN ALAT LABORATORIUM</p>
            </td>
            <td style="width: 80px;"></td>
        </tr>
    </table>

    <?php if (!empty($alat_terpilih)): ?>
        <div class="info-box">
            <table>
                <tr>
                    <td style="width: 15%; font-weight: bold;">Nama Alat</td>
                    <td style="width: 35%;">: <?php echo html_escape($alat_terpilih['nama_barang']); ?></td>
                    <td style="width: 15%; font-weight: bold;">Kode Alat / BMN</td>
                    <td style="width: 35%;">: <?php echo html_escape($alat_terpilih['kode_barang']); ?></td>
                </tr>
                <tr>
                    <td style="font-weight: bold;">Merk / Tipe</td>
                    <td>: <?php echo html_escape($alat_terpilih['merk_tipe'] ?: '-'); ?></td>
                    <td style="font-weight: bold;">Nomor Seri (SN)</td>
                    <td>: <?php echo html_escape(!empty($alat_terpilih['nomor_seri']) ? $alat_terpilih['nomor_seri'] : '-'); ?></td>
                </tr>
                <tr>
                    <td style="font-weight: bold;">Lokasi Ruangan</td>
                    <td>: <?php echo html_escape($alat_terpilih['nama_ruangan'] ?: '-'); ?></td>
                    <td style="font-weight: bold;">Kondisi Alat</td>
                    <td>: <?php echo html_escape($alat_terpilih['kondisi_adm'] ?: 'Baik / Operasional'); ?></td>
                </tr>
            </table>
        </div>
    <?php endif; ?>

    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 4%;">No</th>
                <?php if (empty($alat_terpilih)): ?>
                    <th style="width: 16%;">Nama Alat</th>
                    <th style="width: 10%;">No. Seri / Kode</th>
                    <th style="width: 10%;">Lokasi</th>
                <?php endif; ?>
                <th style="width: 14%;">Tanggal &amp; Waktu</th>
                <th style="width: 20%;">Keterangan / Parameter Uji</th>
                <th style="width: 16%;">Nama / Akun Pengguna</th>
                <th style="width: 16%;">Sampel yang Diuji</th>
                <th style="width: 10%;">Paraf / TTD</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($daftar_logbook)): ?>
                <?php $no = 1; foreach ($daftar_logbook as $row): ?>
                    <tr>
                        <td class="text-center"><?php echo $no++; ?></td>
                        <?php if (empty($alat_terpilih)): ?>
                            <td><strong><?php echo html_escape($row['nama_alat']); ?></strong></td>
                            <td><?php echo html_escape($row['nomor_seri'] ?: $row['kode_barang']); ?></td>
                            <td><?php echo html_escape($row['lokasi_alat'] ?: '-'); ?></td>
                        <?php endif; ?>
                        <td>
                            <?php echo date('d/m/Y', strtotime($row['tanggal_penggunaan'])); ?><br>
                            <span style="font-size: 9px; color: #555;"><?php echo date('H:i', strtotime($row['waktu_penggunaan'])); ?> WIB</span>
                        </td>
                        <td><?php echo html_escape($row['keterangan'] ?: '-'); ?></td>
                        <td>
                            <strong><?php echo html_escape(!empty($row['nama_pengguna']) ? $row['nama_pengguna'] : $row['username']); ?></strong><br>
                            <span style="font-size: 9px; color: #555;"><?php echo !empty($row['nip_pengguna']) ? 'NIP: ' . html_escape($row['nip_pengguna']) : '@' . html_escape($row['username']); ?></span>
                        </td>
                        <td>
                            <strong><?php echo html_escape($row['kode_sampel_manual'] ?: ($row['no_sipt'] ?: 'SMP-' . $row['sample_id'])); ?></strong><br>
                            <span style="font-size: 9px;"><?php echo html_escape($row['nama_sampel']); ?></span>
                        </td>
                        <td class="text-center" style="font-size: 9px; color: #047857;">
                            [Tervalidasi Digital]
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="<?php echo empty($alat_terpilih) ? 8 : 5; ?>" class="text-center" style="padding: 15px;">
                        Tidak ada riwayat penggunaan alat yang tercatat.
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <table class="ttd-table">
        <tr>
            <td>
                Mengetahui,<br>
                <strong>Penanggung Jawab Alat / Laboratorium</strong>
                <br><br><br><br>
                ( ____________________________________ )<br>
                NIP.
            </td>
            <td>
                Bandar Lampung, <?php echo date('d F Y'); ?><br>
                Dicetak oleh: <strong><?php echo html_escape($pencetak); ?></strong>
                <br><br><br><br>
                ( <strong><?php echo html_escape($pencetak); ?></strong> )<br>
                Petugas Laboratorium
            </td>
        </tr>
    </table>

</body>
</html>

