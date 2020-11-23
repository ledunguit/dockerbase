<?php
ob_end_clean();
ob_start();
$account = $this->account;
$quiz = $this->quiz;
$questions = $this->questions;
$method = (isset($_GET['method'])) ? $_GET['method'] : 'I';

class ExportQuiz extends TFPDF {
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
        $this->Cell(250, 10, 'ĐỀ THI', 0, 0, 'C');
        $this->Ln(8);

        $this->setFont('DejaVu', 'I', 10);
        $this->Cell(250, 10, $quiz['categoryName'], 0, 0, 'C');
        $this->Ln(11);

        $this->setFont('Dejavu', 'B', 10);
        $this->Cell(25, 10, 'Tên đề thi: ', 0, 0, 'L');
        $this->setFont('Dejavu', '', 10);
        $this->MultiCell(0, 5, $quiz['name'], 0, 'L');

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

    function content($questions, $quiz) {
        $i = 0;
        $arr = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S'];
        foreach ($questions as $key) {
            $i++;
            $this->setFont('Dejavu', 'B', 10);
            $this->MultiCell(0, 5, 'Câu hỏi số ' . $i . ': ' . html_entity_decode(strip_tags(htmlspecialchars_decode($key['questiontext']))), 0, 'L');
            $this->setFont('Dejavu', '', 10);
            $j = 0;
            foreach ($key['answers'] as $item) {
                if($quiz['review'] == 1){
                    if($item['fraction'] > 0) {
                        $this->setFont('Dejavu', 'B', 10);
                    }
                    else {
                        $this->setFont('Dejavu', '', 10);
                    }
                }
                $this->MultiCell(0, 5, $arr[$j] . '. ' . html_entity_decode(strip_tags(htmlspecialchars_decode($item['answer']))), 0, 'L');
                $j++;
            }
            $this->Ln(2);
            $this->setFont('Dejavu', 'B', 10);
            if($quiz['review'] && $key['feedback'] != null){
                $this->Cell(180, 5, 'Giải thích:', 0, 0, 'L');
                $this->Ln(5);
                $this->setFont('Dejavu', '', 10);
                $this->MultiCell(0, 5, html_entity_decode(strip_tags(htmlspecialchars_decode($key['feedback']))), 0, 'L');
            }
            $this->Ln(3);
        }
    }

    function CheckPageBreak($h) {
        if ($this->GetY() + $h > $this->PageBreakTrigger)
            $this->AddPage($this->CurOrientation);
    }

}

$pdf = new ExportQuiz();
$pdf->AddPage('P', 'A4', 0);
$pdf->AcceptPageBreak();
$pdf->AliasNbPages();
$pdf->information($quiz);
$pdf->content($questions, $quiz);
$pdf->sign($account);
$pdf->Output($method, 'Grade-Table-' . str_replace(' ', '-', $quiz['name']) . '.pdf', true);
ob_end_flush();