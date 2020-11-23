<?php

namespace Venus;

use Application\Frontend\Model\Attempts as Attempts;
use Application\Frontend\Model\Questions as Questions;
use Application\Frontend\Model\Quiz as Quiz;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class SendEmail {
    public static function sendResult($attemptId) {
        $attemptModel = new Attempts();
        $attempt = $attemptModel->getInfo($attemptId);
        if ($attempt) {
            $quizModel = new Quiz();
            $quiz = $quizModel->getInfo($attempt['quizid']);
            $guest = $attempt['guest'];
            if (!isset($guest)) {
                return false;
            }
            $mail = new PHPMailer();
            $mail->isSMTP();
            $mail->SMTPDebug = 3;
            $mail->SMTPAuth = true;
            $mail->SMTPAutoTLS = false;
            $mail->Host = 'mail.phamtrantiendat.online';
            $mail->Port = 25;
            $mail->Username = 'uit@phamtrantiendat.online';
            $mail->Password = 'Hcm123';
            try {
                $mail->setFrom('uit@phamtrantiendat.online', 'Website trac nghiem NT208');
                $mail->addAddress($guest, $guest);
                $mail->Subject = 'Kết quả thi: ' . $quiz['name'];
                $mail->isHTML(true);
                $str = '<p>Ch&agrave;o bạn, lời đầu ti&ecirc;n xin cảm ơn bạn đ&atilde; tham gia thi tại Website luyện thi trắc nghiệm <a href="' . Venus::$baseUrl . '">' . Venus::$baseUrl . '</a>.</p>
                        <table border="0.8" cellpadding="1" cellspacing="0" style="width:500px">
                            <tbody>
                                <tr>
                                    <td>T&ecirc;n đề thi:</td>
                                    <td><a href="' . Venus::$baseUrl . '/quiz/enroll/' . $quiz['id'] . '">' . $quiz['name'] . '</a></td>
                                </tr>
                                <tr>
                                    <td>Danh mục:</td>
                                    <td><a href="' . Venus::$baseUrl . '/categories/' . $quiz['categoryShortName'] . '">' . $quiz['categoryName'] . '</a></td>
                                </tr>
                                <tr>
                                    <td>Nộp b&agrave;i l&uacute;c:</td>
                                    <td>' . date_format(date_create($attempt['timesubmitted']), 'H:i:s d/m/yy') . '</td>
                                </tr>
                                <tr>
                                    <td>Thời gian l&agrave;m b&agrave;i:</td>
                                    <td>' . $attempt['duration']['hours'] . ' giờ ' . $attempt['duration']['minutes'] . ' phút ' . $attempt['duration']['seconds'] . '</td>
                                </tr>
                            </tbody>
                        </table>
                        <p>Kết quả thi của bạn l&agrave;: <big><strong>' . number_format(round($attempt['grade'],2),2) . ' / ' . number_format(round($attempt['sumgrade'],2),2) . '</strong></big> điểm (Xếp loại: ' . $attempt['rank'] . ').</p>
                        <p>Điểm hệ 10: <strong><big>' . number_format(round($attempt['gradeByBaseGrade'],2),2) . ' / 10</big></strong> điểm.</p>';
                if ($quiz['review'] == 0) {
                    $str .= '<p>Hiện đề thi bị cấm xem đ&aacute;p &aacute;n v&agrave; giải th&iacute;ch bởi người sở hữu. Vui l&ograve;ng&nbsp;<a href="' . Venus::$baseUrl . '/signup">đăng k&yacute; t&agrave;i khoản</a>&nbsp;hoặc&nbsp;<a href="' . Venus::$baseUrl . '/login">đăng nhập</a>&nbsp;v&agrave; truy cập v&agrave;o đề thi để biết th&ecirc;m chi tiết.</p><hr />';
                } else {
                    $str .= '<h2><strong>Đ&Aacute;P &Aacute;N V&Agrave; GIẢI TH&Iacute;CH</strong></h2>';
                    $i = 0;
                    $arr = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N'];
                    $questions = (new Questions())->getAllByAttemptId($attemptId);
                    foreach ($questions as $key){
                        $i++;
                        $str .= '<p><strong>C&acirc;u hỏi số&nbsp;'.$i.'</strong>: '.htmlspecialchars_decode($key['questiontext']).'</p>';
                        $str .= '<ol style="list-style-type: upper-latin">';
                        $j = 0;
                        foreach ($key['answers'] as $item) {
                            if($item['fraction'] > 0){
                                $str .= '<li style="color:red">'. htmlspecialchars_decode($item['answer']) .'</span></li>';
                            }
                            else {
                                $str .= '<li>'. htmlspecialchars_decode($item['answer']) .'</li>';
                            }
                            $j++;
                        }
                        $str .= '</ol>';
                        if(isset($key['feedback'])){
                            $str .= '<p><strong>Giải th&iacute;ch:</strong>'. htmlspecialchars_decode($key['feedback']) .' </p>';
                        }
                    }
                }
                $str .= '<hr/><p><img src="' . Venus::$baseUrl . '/publics/images/logo.png" style="height:36px; width:36px" /><br/><big>' . Helper::webInfo()->homename . ' - ' . Helper::webInfo()->homeshort . '</big></p>
                        <p>' . Helper::webInfo()->address . '</p>
                        <p><strong>Powered by</strong>: ' . Helper::webInfo()->poweredby . '</p>';
                $mail->Body = $str;
                $mail->CharSet = 'UTF-8';
                if (!$mail->send()) {
                    return false;
                } else {
                    return true;
                }
            } catch (Exception $e) {
                return false;
            }
        }
        return false;

    }
}