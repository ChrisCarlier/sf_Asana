<?php


namespace App\Controller;

use App\Entity\Project;
use App\Entity\ProjectSearch;
use App\Entity\Team;
use App\Form\ProjectSearchType;
use phpDocumentor\Reflection\Types\Array_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class HomeController extends AbstractController
{

    private $project_gid = "1136866803053084";
    protected $client;

    /**
     * HomeController constructor.
     */
    public function __construct()
    {
        $this->client = HttpClient::create(['http_version' => '1.1']);;
    }

    /**
     * @return Response
     * @Route ("/", name="home")
     */
    public function index() : Response
    {
        return $this->render('pages/home.html.twig');
    }





}