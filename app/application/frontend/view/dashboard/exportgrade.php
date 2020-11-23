<?php
ob_end_clean();
ob_start();
$average = $this->average;
$rows = $this->rows;
$account = $this->account;
$method = (isset($_GET['method'])) ? $_GET['method'] : 'I';

class ExportGrade extends TFPDF {
    function information($account, $average) {
        $this->AddFont('DejaVu', '', 'DejaVuSans.ttf', true);
        $this->AddFont('DejaVu', 'B', 'DejaVuSansCondensed-Bold.ttf', true);
        $this->AddFont('DejaVu', 'I', 'DejaVuSerifCondensed-Italic.ttf', true);

        $this->setFont('DejaVu', 'B', 10);
        $this->Text(30, 20, 'ĐỒ ÁN LẬP TRÌNH ỨNG DỤNG WEB');
        $this->setFont('DejaVu', 'I', 10);
        $this->Text(240, 20, 'Mẫu số 1');
        $this->Text(240, 25, 'Số: GRADE/TABLE:.............');
        $this->Ln();

        $this->setFont('DejaVu', '', 10);
        $this->Text(45, 25, 'NT208.L11.MMCL');
        $this->Ln();
        $this->Text(45, 30, '------------------------');
        $this->Ln();

        $this->setFont('DejaVu', 'B', 20);
        $this->Cell(276, 10, 'BẢNG ĐIỂM CÁ NHÂN', 0, 0, 'C');
        $this->Ln();

        $this->setFont('DejaVu', 'B', 12);
        $this->Cell(276, 10, 'Điểm trung bình: ' . number_format(round($average, 2), 2), 0, 0, 'C');
        $this->Ln(11);

        $this->setFont('Dejavu', 'B', 12);
        $this->Cell(50, 10, 'Họ và tên thí sinh: ', 0, 0, 'L');
        $this->setFont('Dejavu', '', 12);
        $this->Cell(100, 10, $account->lastname . ' ' . $account->firstname, 0, 0, 'L');

        $this->setFont('Dejavu', 'B', 12);
        $this->Cell(40, 10, 'Địa chỉ Email: ', 0, 0, 'L');
        $this->setFont('Dejavu', '', 12);
        $this->Cell(100, 10, $account->email, 0, 0, 'L');
        $this->Ln(8);

        $this->setFont('Dejavu', 'B', 12);
        $this->Cell(50, 10, 'Tổ chức: ', 0, 0, 'L');
        $this->setFont('Dejavu', '', 12);
        $this->Cell(100, 10, substr($account->organization, 0, 40), 0, 0, 'L');

        $this->setFont('Dejavu', 'B', 12);
        $this->Cell(40, 10, 'Bộ phận:', 0, 0, 'L');
        $this->setFont('Dejavu', '', 12);
        $this->Cell(100, 10, substr($account->department, 0, 40), 0, 0, 'L');
        $this->Ln(13);
    }

    function footer() {
        $this->SetY(-15);
        $this->AddFont('DejaVu', '', 'DejaVuSans.ttf', true);
        $this->setFont('Dejavu', '', 12);
        $this->Cell(0, 10, 'Trang ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }

    function sign($account) {
        $this->AddFont('DejaVu', '', 'DejaVuSans.ttf', true);
        $this->setFont('Dejavu', 'B', 11);
        $this->Cell(276, 10, 'Người xuất file', 0, 0, 'R');
        $this->Ln(20);
        $this->setFont('Dejavu', 'B', 11);
        $this->Cell(276, 10, $account->lastname . ' ' . $account->firstname, 0, 0, 'R');
        $this->Ln();
        $this->setFont('Dejavu', '', 10);
        $this->Cell(276, 10, 'Vào lúc ' . date_format(new DateTime(), 'H:i:s d/m/yy'), 0, 0, 'R');
    }

    function headerTable() {
        $this->SetFillColor(204, 229, 255);
        $this->SetDrawColor(128, 128, 128);
        $this->setFont('Dejavu', 'B', 10);
        $this->Cell(10, 10, 'STT', 1, 0, 'C', true);
        $this->Cell(160, 10, 'Tên đề thi', 1, 0, 'C', true);
        $this->Cell(20, 10, 'Số lần thi', 1, 0, 'C', true);
        $this->Cell(35, 10, 'Điểm số', 1, 0, 'C', true);
        $this->Cell(25, 10, 'Điểm hệ 10', 1, 0, 'C', true);
        $this->Cell(30, 10, 'Xếp loại', 1, 0, 'C', true);
        $this->Ln();
    }

    function CheckPageBreak($h) {
        if ($this->GetY() + $h > $this->PageBreakTrigger)
            $this->AddPage($this->CurOrientation);
    }

    function viewTable($rows) {
        $this->setFont('Dejavu', '', 10);
        $i = 0;
        foreach ($rows as $key) {
            $this->CheckPageBreak(10);
            $i++;
            $str_width = $this->GetStringWidth($key['name']);
            $lines = ceil($str_width / 159);
            $height = $lines * 10;

            $current_y = $this->GetY();
            $current_x = $this->GetX();
            $this->MultiCell(10, $height, $i, 1, 'C');
            $end_y = $this->GetY();

            $current_x = $current_x + 10;
            $this->SetXY($current_x, $current_y);
            $this->MultiCell(160, $height / $lines, $key['name'], 1, 'J');
            $end_y = ($this->GetY() > $end_y) ? $this->GetY() : $end_y;

            $current_x = $current_x + 160;
            $this->SetXY($current_x, $current_y);
            $this->MultiCell(20, $height, $key['numberOfAttempts'], 1, 'C');
            $end_y = ($this->GetY() > $end_y) ? $this->GetY() : $end_y;

            $current_x = $current_x + 20;
            $this->SetXY($current_x, $current_y);
            $this->MultiCell(35, $height, number_format(round($key['grade'], 2), 2) . ' / ' . number_format(round($key['sumgrade'], 2), 2), 1, 'C');
            $end_y = ($this->GetY() > $end_y) ? $this->GetY() : $end_y;

            $current_x = $current_x + 35;
            $this->SetXY($current_x, $current_y);
            $this->MultiCell(25, $height, number_format(round($key['percentage'] / 10, 2), 2), 1, 'C');
            $end_y = ($this->GetY() > $end_y) ? $this->GetY() : $end_y;

            $current_x = $current_x + 25;
            $this->SetXY($current_x, $current_y);
            $this->MultiCell(30, $height, $key['rank'], 1, 'C');
            $end_y = ($this->GetY() > $end_y) ? $this->GetY() : $end_y;
            $this->SetY($end_y);
        }

    }
}

$pdf = new ExportGrade();
$pdf->AddPage('L', 'A4', 0);
$pdf->AcceptPageBreak();
$pdf->AliasNbPages();
$pdf->information($account, $average);
$pdf->headerTable();
$pdf->viewTable($rows);
$pdf->sign($account);
$pdf->Output($method, 'Grade-Table-' . str_replace(' ', '-', $account->lastname . ' ' . $account->firstname) . '.pdf', true);
ob_end_flush();