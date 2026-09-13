<?php

namespace App\Models;

use CodeIgniter\Model;
use Config\Services;
use Exception;

class BaseModel extends Model
{
    protected \CodeIgniter\Email\Email $email;
    protected \CodeIgniter\HTTP\IncomingRequest|\CodeIgniter\HTTP\CLIRequest $request;

    public function __construct()
    {
        parent::__construct();
        $this->request = Services::request();
        $this->email   = Services::email();
    }

    /**
     * ✅ Enhanced Debug Utility
     */
    public function debug(mixed $data, bool $die = false): void
    {
        echo '<pre style="background:#222; color:#00ff00; padding:15px; border-radius:5px; border:1px solid #444;">';
        print_r($data);
        echo '</pre>';
        if ($die) die();
    }

    /**
     * ✅ Streamlined Input Fetching
     */
    public function postGet(string $attr, bool $filter = true): ?string
    {
        $value = $this->request->getVar($attr, $filter);
        return is_string($value) ? trim($value) : $value;
    }

    /**
     * ✅ Cryptographically Secure Random Password
     * Compatible with PHP 7.0 through 8.2+
     */
    public function randomPassword(int $digit = 8): string
    {
        try {
            // random_bytes generates raw binary, bin2hex converts to readable string
            // We divide by 2 because hex conversion doubles the string length
            return bin2hex(random_bytes($digit / 2));
        } catch (Exception $e) {
            // Fallback for rare cases where CSPRNG is unavailable
            return substr(str_shuffle(str_repeat('0123456789abcdefghijklmnopqrstuvwxyz', 5)), 0, $digit);
        }
    }

    /**
     * ✅ Robust Email Sender
     */
    public function sendEmail(string $to, string $subject, string $message): bool
    {
        $this->email->clear();

        $this->email->setFrom('no.reply@faisal-ahmed.com', 'Automated Content-Searching Machine');
        $this->email->setTo($to);
        $this->email->setSubject($subject);

        $fullMessage = $message . "<br/><br/>Thanks,<br/><strong>ACM Team</strong>";
        $this->email->setMessage($fullMessage);

        if ($this->email->send()) {
            return true;
        }

        log_message('error', $this->email->printDebugger(['headers']));
        return false;
    }
}