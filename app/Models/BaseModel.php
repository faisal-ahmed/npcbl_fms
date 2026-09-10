<?php

namespace App\Models;

use CodeIgniter\Model;
use Config\Services;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\Email\Email;

class BaseModel extends Model
{
    protected RequestInterface $request;
    protected Email $email;

    public function __construct()
    {
        parent::__construct();

        $this->request = Services::request();
        $this->email   = Services::email();
    }

    /**
     * Debug utility for quick data inspection
     */
    public function debug(mixed $debugArray): void
    {
        echo "<pre style='background: #222; color: #0f0; padding: 20px; border-radius: 5px;'>";
        print_r($debugArray);
        echo "</pre>";
    }

    /**
     * Helper to get trimmed POST data
     */
    public function postGet(string $attr, bool $filter = true): string
    {
        $val = $this->request->getPost($attr, $filter);
        return is_string($val) ? trim($val) : '';
    }

    /**
     * Generates a random alphanumeric string
     */
    public function randomPassword(int $length = 8): string
    {
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        return substr(str_shuffle(str_repeat($chars, ceil($length / strlen($chars)))), 0, $length);
    }

    /**
     * Centralized Email Sender logic
     */
    public function sendEmail(string $to, string $subject, string $message): bool
    {
        // Ensure email is configured to send HTML
        $this->email->setMailType('html');

        $this->email->setFrom('no.reply@faisal-ahmed.com', 'HRM System Notification');
        $this->email->setTo($to);
        $this->email->setSubject($subject);

        // Standardized Email Template
        $body = "
            <div style='font-family: Arial, sans-serif; line-height: 1.6;'>
                {$message}
                <br/><br/>
                <hr style='border: 0; border-top: 1px solid #eee;' />
                <p style='color: #777; font-size: 12px;'>
                    Thanks,<br/>
                    <strong>NPP HRM Team</strong>
                </p>
            </div>
        ";

        $this->email->setMessage($body);

        if ($this->email->send()) {
            return true;
        }

        // Optional: Log errors if email fails
        // log_message('error', $this->email->printDebugger(['headers']));
        return false;
    }
}