<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RedirectResponse;

class SessionFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = [])
    {
        // $session = session();
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = [])
    {
        // No post-processing
    }
}
