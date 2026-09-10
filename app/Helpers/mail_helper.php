<?php

require_once APPPATH . 'Libraries/PHPMailer/Exception.php';
require_once APPPATH . 'Libraries/PHPMailer/PHPMailer.php';
require_once APPPATH . 'Libraries/PHPMailer/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (!function_exists('send_profile_email')) {
    function send_profile_email($to, $subject, $rowData, $imagePath = null): bool
    {
        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host       = 'mail-project01.bcc.gov.bd';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'iqbal.hasani228@rooppurnpp.gov.bd';
            $mail->Password   = 'Procharon@2025';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;
            $mail->Timeout    = 300;
            $mail->SMTPKeepAlive = true;
            $mail->SMTPOptions = [
                'ssl' => [
                    'verify_peer'       => false,
                    'verify_peer_name'  => false,
                    'allow_self_signed' => true,
                ]
            ];

            // Charset & Encoding
            $mail->CharSet = 'UTF-8';
            $mail->Encoding = 'base64';

            // From & Reply-To
            $mail->setFrom('iqbal.hasani228@rooppurnpp.gov.bd', 'NPCBL HRM System');
            $mail->addReplyTo('faisal.ahmed293@rooppurnpp.gov.bd', 'Mohammad Faisal Ahmed');

            // Recipients
            $mail->addAddress($to);
            $mail->addCC('farid.uddin294@rooppurnpp.gov.bd');
            $mail->addCC('tanziar.rahman523@rooppurnpp.gov.bd');

            // Subject
            $mail->Subject = $subject;

            // Attach inline image if exists
            if ($imagePath && file_exists($imagePath)) {
                $cid = md5(uniqid(time(), true));
                $mail->addEmbeddedImage($imagePath, $cid);
            } else {
                $cid = null;
            }

            // Build email body
            $html = "<p>Dear Sir/Colleague,</p>
        <p>Please verify your information carefully. If any wrong information/spelling mistake you notice, then please reply to the following person within <strong>next two official days</strong>.</p>
        <table border='1' cellpadding='5' cellspacing='0'>
            <tr><td><strong>নাম (বাংলা)</strong></td><td>{$rowData['B']}</td></tr>
            <tr><td><strong>পদবি (NPCBL)</strong></td><td>{$rowData['C']}</td></tr>
            <tr><td><strong>জন্ম তারিখ</strong></td><td>{$rowData['D']}</td></tr>
            <tr><td><strong>জন্ম তারিখ (বাংলা)</strong></td><td>{$rowData['E']}</td></tr>
            <tr><td><strong>রক্তের গ্রুপ</strong></td><td>{$rowData['F']}</td></tr>
            <tr><td><strong>জরুরি মোবাইল</strong></td><td>{$rowData['G']}</td></tr>
            <tr><td><strong>জরুরি মোবাইল (বাংলা)</strong></td><td>{$rowData['H']}</td></tr>
            <tr><td><strong>Office ID</strong></td><td>{$rowData['I']}</td></tr>
            <tr><td><strong>অফিস আইডি (বাংলা)</strong></td><td>{$rowData['J']}</td></tr>";

            if ($cid) {
                $html .= "<tr><td><strong>প্রোফাইল ছবি</strong></td><td><img src='cid:$cid' height='100' alt='Profile Picture'></td></tr>";
            }

            $html .= "</table>
        <p style='color: crimson; font-size: 16px;'>
            ✦ If everything is correct, <strong style='color: crimson; font-size: 1.2em; font-weight: bold;'>YOU DO NOT NEED TO REPLY</strong>.<br>
            ✦ If you need to correct anything including the profile picture, email to: <strong>faisal.ahmed293@rooppurnpp.gov.bd</strong><br>
            ✦ Keep in CC:<br>
            &nbsp;&nbsp;&nbsp;1. farid.uddin294@rooppurnpp.gov.bd<br>
            &nbsp;&nbsp;&nbsp;2. tanziar.rahman@rooppurnpp.gov.bd
        </p>
        <p style='color: crimson; font-size: 1.2em; font-weight: bold;'>
            ✦ For profile photo correction: send a recent professional photo with white background in <strong>JPG format under 2 MB</strong> as an email attachment.
        </p>
        <p style='color: crimson; font-size: 1.2em; font-weight: bold; text-transform: uppercase;'>
            If the instructions above are not followed properly, your email will not be accepted. Kindly treat this matter with urgency and due attention.
        </p>";

            $mail->isHTML(true);
            $mail->Body = $html;

            return $mail->send();
        }
        catch (Exception $e) {
            log_message('error', "Mail Error: {$mail->ErrorInfo}");
            return false;
        }
    }
}
