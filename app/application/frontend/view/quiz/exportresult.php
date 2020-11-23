<?php
ob_end_clean();
ob_start();
$attempts = $this->attempts;
$account = $this->account;
$quiz = $this->quiz;
$method = (isset($_GET['method'])) ? $_GET['method'] : 'I';

class ExportResult extends TFPDF {
    function information($quiz) {
        $this->AddFont('DejaVu', '', 'DejaVuSans.ttf', true);
        $this->AddFont('DejaVu', 'B', 'DejaVuSansCondensed-Bold.ttf', true);
        $this->AddFont('DejaVu', 'I', 'DejaVuSerifCondensed-Italic.ttf', true);

        $this->setFont('DejaVu', 'B', 10);
        $this->Text(15, 15, 'ĐỒ ÁN LẬP TRÌNH ỨNG DỤNG WEB');
        $this->Ln();

        $this->setFont('DejaVu', '', 10);
        $this->Text(30, 20, 'NT208.L11.MMCL');
        $this->Ln();
        $this->Text(30, 25, '------------------------');
        $this->Ln();

        $this->setFont('DejaVu', 'B', 18);
        $this->Cell(250, 10, 'DANH SÁCH KẾT QUẢ THI', 0, 0, 'C');
        $this->Ln(8);

        $this->setFont('DejaVu', 'I', 10);
        $this->Cell(250, 10, 'Mẫu số 2, Website luyện thi trắc nghiệm', 0, 0, 'C');
        $this->Ln(11);

        $this->setFont('Dejavu', 'B', 10);
        $this->Cell(25, 10, 'Tên đề thi: ', 0, 0, 'L');
        $this->setFont('Dejavu', '', 10);
        $this->Cell(200, 10, substr($quiz['name'], 0, 118), 0, 0, 'L');
        $this->Ln(6);

        $this->setFont('Dejavu', 'B', 10);
        $this->Cell(25, 10, 'Danh mục: ', 0, 0, 'L');
        $this->setFont('Dejavu', '', 10);
        $this->Cell(200, 10, $quiz['categoryName'], 0, 0, 'L');
        $this->Ln(6);

        $this->setFont('Dejavu', 'B', 10);
        $this->Cell(25, 10, 'Người tạo: ', 0, 0, 'L');
        $this->setFont('Dejavu', '', 10);
        $this->Cell(100, 10, $quiz['createdByName'], 0, 0, 'L');
        $this->setFont('Dejavu', 'B', 10);
        $this->Cell(15, 10, 'Mã đề: ', 0, 0, 'L');
        $this->setFont('Dejavu', '', 10);
        $this->Cell(100, 10, $quiz['code'], 0, 0, 'L');
        $this->Ln(13);
    }

    function footer() {
        $this->SetY(-15);
        $this->AddFont('DejaVu', '', 'DejaVuSans.ttf', true);
        $this->setFont('Dejavu', '', 12);
        $this->Cell(0, 10, 'Trang ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }

    function sign($account) {
        $this->AddFont('DejaVu', 'B', 'DejaVuSans.ttf', true);
        $this->setFont('Dejavu', 'B', 11);
        $this->Cell(180, 10, 'Người xuất file', 0, 0, 'R');
        $this->Ln(20);
        $this->setFont('Dejavu', 'B', 11);
        $this->Cell(180, 10, $account->lastname . ' ' . $account->firstname, 0, 0, 'R');
        $this->Ln();
        $this->setFont('Dejavu', '', 10);
        $this->Cell(180, 10, 'Vào lúc ' . date_format(new DateTime(), 'H:i:s d/m/yy'), 0, 0, 'R');
    }

    function headerTable() {
        $this->SetFillColor(204, 229, 255);
        $this->SetDrawColor(128, 128, 128);
        $this->setFont('Dejavu', 'B', 10);
        $this->Cell(10, 10, 'STT', 1, 0, 'C', true);
        $this->Cell(60, 10, 'Họ và tên', 1, 0, 'C', true);
        $this->Cell(60, 10, 'Địa chỉ Email', 1, 0, 'C', true);
        $this->Cell(25, 10, 'Điểm hệ 10', 1, 0, 'C', true);
        $this->Cell(30, 10, 'Xếp loại', 1, 0, 'C', true);
        $this->Ln();
    }

    function CheckPageBreak($h) {
        if ($this->GetY() + $h > $this->PageBreakTrigger)
            $this->AddPage($this->CurOrientation);
    }

    function viewTable($attempts) {
        $this->setFont('Dejavu', '', 10);
        $i = 0;
        foreach ($attempts as $key) {
            if($key['userid'] == null) {
                continue;
            }
            $this->CheckPageBreak(10);
            $i++;
            $str_width = $this->GetStringWidth($key['fullname']);
            $lines = ceil($str_width / 59);
            $height = $lines * 10;

            $current_y = $this->GetY();
            $current_x = $this->GetX();
            $this->MultiCell(10, $height, $i, 1, 'C');
            $end_y = $this->GetY();

            $current_x = $current_x + 10;
            $this->SetXY($current_x, $current_y);
            $this->MultiCell(60, $height / 1, $key['fullname'], 1, 'J');
            $end_y = ($this->GetY() > $end_y) ? $this->GetY() : $end_y;

            $current_x = $current_x + 60;
            $this->SetXY($current_x, $current_y);
            $this->MultiCell(60, $height, $key['email'], 1, 'L');
            $end_y = ($this->GetY() > $end_y) ? $this->GetY() : $end_y;

            $current_x = $current_x + 60;
            $this->SetXY($current_x, $current_y);
            $this->MultiCell(25, $height, number_format(round($key['gradeByBaseGrade'], 2), 2), 1, 'C');
            $end_y = ($this->GetY() > $end_y) ? $this->GetY() : $end_y;

            $current_x = $current_x + 25;
            $this->SetXY($current_x, $current_y);
            $this->MultiCell(30, $height, $key['rank'], 1, 'C');
            $end_y = ($this->GetY() > $end_y) ? $this->GetY() : $end_y;
        }
        $this->setFont('DejaVu', 'I', 10);
        $this->MultiCell(276, 10, 'Danh sách này có '. $i. ' lượt làm bài ./.', 0, 'L');
    }
}

$pdf = new ExportResult();
$pdf->AddPage('P', 'A4', 0);
$pdf->AcceptPageBreak();
$pdf->AliasNbPages();
$pdf->information($quiz);
$pdf->headerTable();
$pdf->viewTable($attempts);
$pdf->sign($account);
$pdf->Output($method, 'Grade-Table-' . str_replace(' ', '-', $quiz['name']) . '.pdf', true);
ob_end_flush();