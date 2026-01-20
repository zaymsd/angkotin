<?php
/**
 * Export PDF - Laporan Performa Armada
 * Admin Only
 */

session_start();
require_once '../../config/database.php';
require_once '../../includes/functions.php';
require_once '../../includes/auth.php';
require_once '../../fpdf/fpdf.php';

require_admin();

// Filter parameters
$bulan = isset($_GET['bulan']) ? (int) $_GET['bulan'] : (int) date('m');
$tahun = isset($_GET['tahun']) ? (int) $_GET['tahun'] : (int) date('Y');

// Build date range
$tanggal_awal = sprintf('%04d-%02d-01', $tahun, $bulan);
$tanggal_akhir = date('Y-m-t', strtotime($tanggal_awal));

// Nama bulan
$nama_bulan = [
    '',
    'Januari',
    'Februari',
    'Maret',
    'April',
    'Mei',
    'Juni',
    'Juli',
    'Agustus',
    'September',
    'Oktober',
    'November',
    'Desember'
];

// Get performance per mobil
$query_mobil = "SELECT 
    m.id_mobil,
    m.no_polisi,
    m.merk,
    m.tahun_pembuatan,
    m.status,
    COUNT(DISTINCT a.id_absensi) as total_hari_operasi,
    COALESCE(SUM(CASE WHEN st.status = 'dikonfirmasi' THEN st.jumlah_setoran ELSE 0 END), 0) as total_setoran,
    COALESCE((SELECT SUM(sv.biaya) FROM servis sv WHERE sv.id_mobil = m.id_mobil AND sv.tanggal_servis BETWEEN ? AND ?), 0) as total_biaya_servis,
    COUNT(DISTINCT sv2.id_servis) as total_servis
FROM mobil m
LEFT JOIN absensi a ON m.id_mobil = a.id_mobil AND a.tanggal BETWEEN ? AND ?
LEFT JOIN setoran st ON m.id_mobil = st.id_mobil AND st.tanggal_setoran BETWEEN ? AND ?
LEFT JOIN servis sv2 ON m.id_mobil = sv2.id_mobil AND sv2.tanggal_servis BETWEEN ? AND ?
GROUP BY m.id_mobil
ORDER BY total_setoran DESC";

$stmt = $conn->prepare($query_mobil);
$stmt->bind_param("ssssssss", $tanggal_awal, $tanggal_akhir, $tanggal_awal, $tanggal_akhir, $tanggal_awal, $tanggal_akhir, $tanggal_awal, $tanggal_akhir);
$stmt->execute();
$performa_mobil = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Get performance per supir
$query_supir = "SELECT 
    s.id_supir,
    s.nama_supir,
    s.no_sim,
    s.status,
    COUNT(DISTINCT a.id_absensi) as total_hari_kerja,
    COALESCE(SUM(CASE WHEN st.status = 'dikonfirmasi' THEN st.jumlah_setoran ELSE 0 END), 0) as total_setoran,
    COALESCE(AVG(CASE WHEN st.status = 'dikonfirmasi' THEN st.jumlah_setoran ELSE NULL END), 0) as rata_setoran
FROM supir s
LEFT JOIN absensi a ON s.id_supir = a.id_supir AND a.tanggal BETWEEN ? AND ?
LEFT JOIN setoran st ON s.id_supir = st.id_supir AND st.tanggal_setoran BETWEEN ? AND ?
GROUP BY s.id_supir
ORDER BY total_setoran DESC";

$stmt = $conn->prepare($query_supir);
$stmt->bind_param("ssss", $tanggal_awal, $tanggal_akhir, $tanggal_awal, $tanggal_akhir);
$stmt->execute();
$performa_supir = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Calculate summary stats
$total_armada = count($performa_mobil);
$armada_operasional = 0;
$total_pendapatan = 0;
$total_pengeluaran = 0;

foreach ($performa_mobil as $mobil) {
    if ($mobil['status'] === 'operasional')
        $armada_operasional++;
    $total_pendapatan += $mobil['total_setoran'];
    $total_pengeluaran += $mobil['total_biaya_servis'];
}

// Custom PDF class
class PDF extends FPDF
{
    function Header()
    {
        $this->SetFont('Arial', 'B', 16);
        $this->Cell(0, 10, 'LAPORAN PERFORMA ARMADA', 0, 1, 'C');
        $this->SetFont('Arial', '', 12);
        $this->Cell(0, 8, 'Sistem Informasi Manajemen Angkot', 0, 1, 'C');
        $this->Ln(5);
    }

    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Dicetak: ' . date('d/m/Y H:i') . ' | Halaman ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }
}

// Create PDF
$pdf = new PDF('L'); // Landscape orientation for wider tables
$pdf->AliasNbPages();
$pdf->AddPage();

// Periode
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 8, 'Periode: ' . $nama_bulan[$bulan] . ' ' . $tahun, 0, 1, 'C');
$pdf->Ln(5);

// Summary Section
$pdf->SetFont('Arial', 'B', 11);
$pdf->SetFillColor(66, 139, 202);
$pdf->SetTextColor(255);
$pdf->Cell(0, 8, 'RINGKASAN PERFORMA', 1, 1, 'C', true);

$pdf->SetTextColor(0);
$pdf->SetFont('Arial', '', 10);
$pdf->SetFillColor(240, 240, 240);

// Summary in 2 columns
$pdf->Cell(70, 7, 'Total Armada', 1, 0, 'L', true);
$pdf->Cell(70, 7, $total_armada . ' unit (' . $armada_operasional . ' operasional)', 1, 0, 'R');
$pdf->Cell(70, 7, 'Total Supir', 1, 0, 'L', true);
$pdf->Cell(70, 7, count($performa_supir) . ' orang', 1, 1, 'R');

$pdf->Cell(70, 7, 'Total Pendapatan', 1, 0, 'L', true);
$pdf->SetTextColor(0, 128, 0);
$pdf->Cell(70, 7, 'Rp ' . number_format($total_pendapatan, 0, ',', '.'), 1, 0, 'R');
$pdf->SetTextColor(0);
$pdf->Cell(70, 7, 'Total Biaya Servis', 1, 0, 'L', true);
$pdf->SetTextColor(220, 53, 69);
$pdf->Cell(70, 7, 'Rp ' . number_format($total_pengeluaran, 0, ',', '.'), 1, 1, 'R');
$pdf->SetTextColor(0);

$pdf->Ln(8);

// Performa Mobil Table
$pdf->SetFont('Arial', 'B', 11);
$pdf->SetFillColor(0, 78, 137);
$pdf->SetTextColor(255);
$pdf->Cell(0, 8, 'PERFORMA PER MOBIL', 1, 1, 'C', true);

$pdf->SetTextColor(0);
$pdf->SetFont('Arial', 'B', 9);
$pdf->SetFillColor(200, 200, 200);
$pdf->Cell(12, 7, 'No', 1, 0, 'C', true);
$pdf->Cell(35, 7, 'No Polisi', 1, 0, 'C', true);
$pdf->Cell(40, 7, 'Merk', 1, 0, 'C', true);
$pdf->Cell(20, 7, 'Tahun', 1, 0, 'C', true);
$pdf->Cell(30, 7, 'Status', 1, 0, 'C', true);
$pdf->Cell(25, 7, 'Hari Op.', 1, 0, 'C', true);
$pdf->Cell(45, 7, 'Total Setoran', 1, 0, 'C', true);
$pdf->Cell(40, 7, 'Biaya Servis', 1, 0, 'C', true);
$pdf->Cell(33, 7, 'Net', 1, 1, 'C', true);

$pdf->SetFont('Arial', '', 8);
$no = 1;
foreach ($performa_mobil as $mobil) {
    $net = $mobil['total_setoran'] - $mobil['total_biaya_servis'];
    $status_text = ucfirst($mobil['status']);

    $pdf->Cell(12, 6, $no++, 1, 0, 'C');
    $pdf->Cell(35, 6, $mobil['no_polisi'], 1, 0, 'L');
    $pdf->Cell(40, 6, substr($mobil['merk'], 0, 20), 1, 0, 'L');
    $pdf->Cell(20, 6, $mobil['tahun_pembuatan'], 1, 0, 'C');
    $pdf->Cell(30, 6, $status_text, 1, 0, 'C');
    $pdf->Cell(25, 6, $mobil['total_hari_operasi'] . ' hari', 1, 0, 'C');
    $pdf->SetTextColor(0, 128, 0);
    $pdf->Cell(45, 6, 'Rp ' . number_format($mobil['total_setoran'], 0, ',', '.'), 1, 0, 'R');
    $pdf->SetTextColor(220, 53, 69);
    $pdf->Cell(40, 6, 'Rp ' . number_format($mobil['total_biaya_servis'], 0, ',', '.'), 1, 0, 'R');

    if ($net >= 0) {
        $pdf->SetTextColor(0, 128, 0);
    } else {
        $pdf->SetTextColor(220, 53, 69);
    }
    $pdf->Cell(33, 6, 'Rp ' . number_format($net, 0, ',', '.'), 1, 1, 'R');
    $pdf->SetTextColor(0);
}

$pdf->Ln(8);

// Performa Supir Table
$pdf->SetFont('Arial', 'B', 11);
$pdf->SetFillColor(0, 78, 137);
$pdf->SetTextColor(255);
$pdf->Cell(0, 8, 'PERFORMA PER SUPIR', 1, 1, 'C', true);

$pdf->SetTextColor(0);
$pdf->SetFont('Arial', 'B', 9);
$pdf->SetFillColor(200, 200, 200);
$pdf->Cell(12, 7, 'No', 1, 0, 'C', true);
$pdf->Cell(60, 7, 'Nama Supir', 1, 0, 'C', true);
$pdf->Cell(50, 7, 'No SIM', 1, 0, 'C', true);
$pdf->Cell(35, 7, 'Status', 1, 0, 'C', true);
$pdf->Cell(30, 7, 'Hari Kerja', 1, 0, 'C', true);
$pdf->Cell(45, 7, 'Total Setoran', 1, 0, 'C', true);
$pdf->Cell(48, 7, 'Rata-rata/Hari', 1, 1, 'C', true);

$pdf->SetFont('Arial', '', 8);
$no = 1;
foreach ($performa_supir as $supir) {
    $status_text = ucfirst($supir['status']);

    $pdf->Cell(12, 6, $no++, 1, 0, 'C');
    $pdf->Cell(60, 6, substr($supir['nama_supir'], 0, 30), 1, 0, 'L');
    $pdf->Cell(50, 6, $supir['no_sim'], 1, 0, 'C');
    $pdf->Cell(35, 6, $status_text, 1, 0, 'C');
    $pdf->Cell(30, 6, $supir['total_hari_kerja'] . ' hari', 1, 0, 'C');
    $pdf->SetTextColor(0, 128, 0);
    $pdf->Cell(45, 6, 'Rp ' . number_format($supir['total_setoran'], 0, ',', '.'), 1, 0, 'R');
    $pdf->SetTextColor(0);
    $pdf->Cell(48, 6, 'Rp ' . number_format($supir['rata_setoran'], 0, ',', '.'), 1, 1, 'R');
}

// Output PDF
$filename = 'Laporan_Performa_Armada_' . $nama_bulan[$bulan] . '_' . $tahun . '.pdf';
$pdf->Output('I', $filename);
?>