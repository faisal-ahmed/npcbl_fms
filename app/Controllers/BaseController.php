<?php
namespace App\Controllers;

use App\Models\AclModel;
use App\Models\UserModel;
use CodeIgniter\Controller;
use CodeIgniter\Database\BaseConnection;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Database;
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
    protected $request;

    /**
     * Instance of the main Session object.
     */
    protected $session;

    /**
     * Database connection instance.
     *
     * @var BaseConnection
     */
    protected BaseConnection $db;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var list<string>
     */
    protected $helpers = ['mail'];

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
        $group = (ENVIRONMENT === 'development') ? 'default' : 'live';
        $this->db = Database::connect($group);
    }

    // ✅ Session-based login status
    protected function isLoggedIn(): bool
    {
        $expiry = $this->session->get('session_expiry');
        $now = time();

        return (bool) ($this->session->get('user_id') && $expiry && $now <= $expiry);
    }

    protected function redirectLoggedInUser(): ?RedirectResponse
    {
        if ($this->isLoggedIn()) {
            return redirect()->to(site_url('Home'));
        }
        return null;
    }

    /**
     * Authentication & Authorization Combined Check
     * Best called at the start of restricted controller methods.
     */
    protected function restrictAccess(): ?RedirectResponse
    {
        if (!$this->isLoggedIn()) {
            $this->clearSession();
            return redirect()->to(base_url('employee/login'));
        }

        if (!$this->hasPermission()) {
            return redirect()->to(base_url('home'))->with('error', 'You do not have permission to view this resource.');
        }

        return null;
    }

    /**
     * Internal logic to check ACL
     */
    private function hasPermission(): bool
    {
        $uri = service('uri');

        $roleId     = (int) $this->getUserRole();
        $controller = $uri->getSegment(1);
        $method     = $uri->getSegment(2) ?: 'index';

        $aclModel = new AclModel();
        return $aclModel->checkAccess($roleId, $controller, $method);
    }

    protected function clearSession(): void
    {
        if ($this->session->get('user_id')) {
            $this->session->destroy();
        }
    }

    protected function getFullSession(): array
    {
        return [
            'user_id'        => $this->session->get('user_id'),
            'name_en'        => $this->session->get('np_job_person_name'),
            'session_id'     => $this->session->get('session_id'),
            'session_expiry' => $this->session->get('session_expiry'),
            'status'         => $this->session->get('status')
        ];
    }

    protected function getUserRole(): mixed
    {
        return $this->session->get('role_id');
    }

    protected function getSessionAttr(string $attr): mixed
    {
        return $this->session->get($attr) ?? false;
    }

    protected function getSessionID(): mixed
    {
        return $this->session->get('session_id') ?? false;
    }

    protected function setSessionAttr(string $attr, mixed $value): void
    {
        $this->session->set($attr, $value);
    }

    protected function unsetSessionAttr(string $attr): void
    {
        $this->session->remove($attr);
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
        $isLoggedIn = $this->isLoggedIn();

        $data['loggedIn']     = $isLoggedIn ? 'true' : 'false';
        $data['session_data'] = $this->getFullSession();

        if ($isLoggedIn) {
            $userModel = new UserModel();
            $data['user_data'] = $userModel->getUserData($this->getUserId());
        }

        $output  = view('common/header', $data);
        $output .= view('common/nav_top_banner', $data);
        if (!is_null($view)) {
            $output .= view($view, $data);
        }
        $output .= view('common/footer', $data);

        return $output;
    }
}