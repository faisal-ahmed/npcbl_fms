<?php
namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;
use Psr\Log\LoggerInterface;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 *
 * For security be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $session;
    protected $request;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var list<string>
     */
    protected $helpers = ['mail'];

    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */
    // protected $session;

    /**
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @param LoggerInterface $logger
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger): void
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        // Load Session and Request service
        $this->session = Services::session();
        $this->request = Services::request();

        // Determine environment and connect to the appropriate database
        if (str_contains($_SERVER['SERVER_NAME'], 'localhost')) {
            $this->db = \Config\Database::connect('default');
        } else {
            $this->db = \Config\Database::connect('live');
        }
    }

    // ✅ Session-based login status
    protected function isLoggedIn(): bool
    {
        $session = service('session');
        $expiry = $session->get('session_expiry');
        $now = time();

        return $session->get('user_id') && $expiry && $now <= $expiry;
    }

    protected function redirectLoggedInUser(): ?\CodeIgniter\HTTP\RedirectResponse
    {
        if ($this->isLoggedIn()) {
            return redirect()->to(site_url('applicants/home'));
        }
        return null;
    }

    protected function redirectGeneralUser(): ?\CodeIgniter\HTTP\RedirectResponse
    {
        $session = service('session');
        $expiry = $session->get('session_expiry') ?? false;
        $now = time();

        if (!$session->get('user_id') || !$expiry || $now > $expiry) {
            $this->clearSession(); // clear session if invalid or expired
            return redirect()->to(site_url('applicants/login'));
        }

        return null;
    }

    protected function clearSession(): void
    {
        if (session()->get('user_id')) {
            session()->destroy();
        }
    }

    protected function getFullSession(): array
    {
        $session = service('session');

        return [
            'user_id'        => $session->get('user_id'),
            'name_en'        => $session->get('np_job_person_name'),
            'session_id'     => $session->get('session_id'),
            'session_expiry' => $session->get('session_expiry'),
            'status'         => $session->get('status')
        ];
    }

    protected function getSessionAttr(string $attr): mixed
    {
        $session = service('session');
        return $session->has($attr) ? $session->get($attr) : false;
    }

    protected function getSessionID(): mixed
    {
        $session = service('session');
        return $session->has('session_id') ? $session->get('session_id') : false;
    }

    protected function setSessionAttr(string $attr, mixed $value): void
    {
        $session = service('session');
        $session->set($attr, $value);
    }

    protected function unsetSessionAttr(string $attr): void
    {
        $session = service('session');
        $session->remove($attr);
    }

    protected function getUserId(): mixed
    {
        return $this->getSessionAttr('user_id');
    }

    // ✅ Debug utility
    protected function debug($debugArray): void
    {
        echo "<pre>";
        print_r($debugArray);
        echo "</pre>";
    }

    // ✅ View Loader
    protected function viewLoad(string $view = null, array $data = []): string
    {
        $userModel = new UserModel();
        $data['loggedIn']       = $this->isLoggedIn() ? 'true' : 'false';
        $data['session_data']   = $this->getFullSession();
        if ($data['loggedIn'] == 'true') {
            $data['user_data'] = $userModel->getUserData($this->getUserId());
        }

        $output = view('common/header', $data);
        $output .= view('common/nav_top_banner', $data);
        if (!is_null($view)) {
            $output .= view($view, $data);
        }
        $output .= view('common/footer', $data);

        return $output;
    }
}
