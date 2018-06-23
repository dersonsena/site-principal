<?php
namespace App\AdminBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="admin_default_index")
     * @Template("@Admin/default/index.html.twig")
     */
    public function index()
    {
        return [];
    }

    /**
     * @Route("/login", name="admin_default_login")
     * @Template("@Admin/default/login.html.twig")
     */
    public function login()
    {
        return [];
    }
}