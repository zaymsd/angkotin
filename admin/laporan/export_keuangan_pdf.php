<?php
/**
 * Export PDF - Laporan Keuangan
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

// Build date range for the selected month
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

// Get total setoran (income) - only confirmed
$stmt = $conn->prepare("SELECT COALESCE(SUM(jumlah_setoran), 0) as total FROM setoran WHERE tanggal_setoran BETWEEN ? AND ? AND status = 'dikonfirmasi'");
$stmt->bind_param("ss", $tanggal_awal, $tanggal_akhir);
$stmt->execute();
$total_setoran = $stmt->get_result()->fetch_assoc()['total'];

// Get total servis (expenses)
$stmt = $conn->prepare("SELECT COALESCE(SUM(biaya), 0) as total FROM servis WHERE tanggal_servis BETWEEN ? AND ?");
$stmt->bind_param("ss", $tanggal_awal, $tanggal_akhir);
$stmt->execute();
$total_servis = $stmt->get_result()->fetch_assoc()['total'];

// Calculate net income
$pendapatan_bersih = $total_setoran - $total_servis;

// Get detail setoran
$stmt = $conn->prepare("SELECT st.*, s.nama_supir, m.no_polisi 
                        FROM setoran st 
                        JOIN supir s ON st.id_supir = s.id_supir 
                        JOIN mobil m ON st.id_mobil = m.id_mobil 
                        WHERE st.tanggal_setoran BETWEEN ? AND ? AND st.status = 'dikonfirmasi'
                        ORDER BY st.tanggal_setoran DESC");
$stmt->bind_param("ss", $tanggal_awal, $tanggal_akhir);
$stmt->execute();
$detail_setoran = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Get detail servis
$stmt = $conn->prepare("SELECT sv.*, m.no_polisi 
                        FROM servis sv 
                        JOIN mobil m ON sv.id_mobil = m.id_mobil 
                        WHERE sv.tanggal_servis BETWEEN ? AND ?
                        ORDER BY sv.tanggal_servis DESC");
$stmt->bind_param("ss", $tanggal_awal, $tanggal_akhir);
$stmt->execute();
$detail_servis = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Custom PDF class
class PDF extends FPDF
{
    function Header()
    {
        $this->SetFont('Arial', 'B', 16);
        $this->Cell(0, 10, 'LAPORAN KEUANGAN', 0, 1, 'C');
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
$pdf = new PDF();
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
$pdf->Cell(0, 8, 'RINGKASAN KEUANGAN', 1, 1, 'C', true);

$pdf->SetTextColor(0);
$pdf->SetFont('Arial', '', 10);

// Summary table
$pdf->SetFillColor(240, 240, 240);
$pdf->Cell(95, 7, 'Total Pendapatan (Setoran Dikonfirmasi)', 1, 0, 'L', true);
$pdf->SetTextColor(0, 128, 0);
$pdf->Cell(95, 7, 'Rp ' . number_format($total_setoran, 0, ',', '.'), 1, 1, 'R');

$pdf->SetTextColor(0);
$pdf->Cell(95, 7, 'Total Pengeluaran (Biaya Servis)', 1, 0, 'L', true);
$pdf->SetTextColor(220, 53, 69);
$pdf->Cell(95, 7, 'Rp ' . number_format($total_servis, 0, ',', '.'), 1, 1, 'R');

$pdf->SetTextColor(0);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(95, 7, 'Pendapatan Bersih', 1, 0, 'L', true);
if ($pendapatan_bersih >= 0) {
    $pdf->SetTextColor(0, 128, 0);
} else {
    $pdf->SetTextColor(220, 53, 69);
}
$pdf->Cell(95, 7, 'Rp ' . number_format($pendapatan_bersih, 0, ',', '.'), 1, 1, 'R');
$pdf->SetTextColor(0);

$pdf->Ln(8);

// Detail Pendapatan
$pdf->SetFont('Arial', 'B', 11);
$pdf->SetFillColor(40, 167, 69);
$pdf->SetTextColor(255);
$pdf->Cell(0, 8, 'DETAIL PENDAPATAN (' . count($detail_setoran) . ' transaksi)', 1, 1, 'C', true);

$pdf->SetTextColor(0);
$pdf->SetFont('Arial', 'B', 9);
$pdf->SetFillColor(200, 200, 200);
$pdf->Cell(15, 7, 'No', 1, 0, 'C', true);
$pdf->Cell(35, 7, 'Tanggal', 1, 0, 'C', true);
$pdf->Cell(50, 7, 'Supir', 1, 0, 'C', true);
$pdf->Cell(40, 7, 'No Polisi', 1, 0, 'C', true);
$pdf->Cell(50, 7, 'Jumlah', 1, 1, 'C', true);

$pdf->SetFont('Arial', '', 9);
$no = 1;
foreach ($detail_setoran as $row) {
    $pdf->Cell(15, 6, $no++, 1, 0, 'C');
    $pdf->Cell(35, 6, date('d/m/Y', strtotime($row['tanggal_setoran'])), 1, 0, 'C');
    $pdf->Cell(50, 6, substr($row['nama_supir'], 0, 25), 1, 0, 'L');
    $pdf->Cell(40, 6, $row['no_polisi'], 1, 0, 'C');
    $pdf->Cell(50, 6, 'Rp ' . number_format($row['jumlah_setoran'], 0, ',', '.'), 1, 1, 'R');
}

// Total row
$pdf->SetFont('Arial', 'B', 9);
$pdf->SetFillColor(200, 200, 200);
$pdf->Cell(140, 7, 'TOTAL PENDAPATAN', 1, 0, 'R', true);
$pdf->SetTextColor(0, 128, 0);
$pdf->Cell(50, 7, 'Rp ' . number_format($total_setoran, 0, ',', '.'), 1, 1, 'R', true);
$pdf->SetTextColor(0);

$pdf->Ln(8);

// Detail Pengeluaran
$pdf->SetFont('Arial', 'B', 11);
$pdf->SetFillColor(220, 53, 69);
$pdf->SetTextColor(255);
$pdf->Cell(0, 8, 'DETAIL PENGELUARAN (' . count($detail_servis) . ' servis)', 1, 1, 'C', true);

$pdf->SetTextColor(0);
$pdf->SetFont('Arial', 'B', 9);
$pdf->SetFillColor(200, 200, 200);
$pdf->Cell(15, 7, 'No', 1, 0, 'C', true);
$pdf->Cell(35, 7, 'Tanggal', 1, 0, 'C', true);
$pdf->Cell(35, 7, 'No Polisi', 1, 0, 'C', true);
$pdf->Cell(55, 7, 'Jenis Servis', 1, 0, 'C', true);
$pdf->Cell(50, 7, 'Biaya', 1, 1, 'C', true);

$pdf->SetFont('Arial', '', 9);
$no = 1;
foreach ($detail_servis as $row) {
    $pdf->Cell(15, 6, $no++, 1, 0, 'C');
    $pdf->Cell(35, 6, date('d/m/Y', strtotime($row['tanggal_servis'])), 1, 0, 'C');
    $pdf->Cell(35, 6, $row['no_polisi'], 1, 0, 'C');
    $pdf->Cell(55, 6, substr($row['jenis_servis'], 0, 30), 1, 0, 'L');
    $pdf->Cell(50, 6, 'Rp ' . number_format($row['biaya'], 0, ',', '.'), 1, 1, 'R');
}

// Total row
$pdf->SetFont('Arial', 'B', 9);
$pdf->SetFillColor(200, 200, 200);
$pdf->Cell(140, 7, 'TOTAL PENGELUARAN', 1, 0, 'R', true);
$pdf->SetTextColor(220, 53, 69);
$pdf->Cell(50, 7, 'Rp ' . number_format($total_servis, 0, ',', '.'), 1, 1, 'R', true);
$pdf->SetTextColor(0);

// Output PDF
$filename = 'Laporan_Keuangan_' . $nama_bulan[$bulan] . '_' . $tahun . '.pdf';
$pdf->Output('I', $filename);
?>