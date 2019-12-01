<?php


namespace App\Controller;


use App\Entity\Project;
use App\Entity\ProjectSearch;
use App\Form\ProjectSearchType;
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

class ProjectController extends AbstractController
{
    protected $client;

    /**
     * HomeController constructor.
     */
    public function __construct()
    {
        $this->client = HttpClient::create(['http_version' => '1.1']);;
    }

    /**
     * @param Request $request
     * @return Response
     * @Route ("/projects", name="projects")
     */
    public function getProjects(Request $request) : Response
    {
        $search = new ProjectSearch();
        $form = $this->createForm(ProjectSearchType::class, $search);
        $form->handleRequest($request);

        $count = 0;
        $projects = [];
        try {
            $response = $this->client->request('GET', 'https://app.asana.com/api/1.0/teams/1136866803053084/projects/',[
                'auth_bearer' => '0/0b1b7b5d59f146cdbb5de67e5e0ad52e'
            ]);

            foreach ($response->toArray()['data'] as $p)
            {
                $nomProjet = $p["name"];

                // Faut-il faut un filtre ?
                $filter = false;
                if($search->getName() !== null)
                {
                    $critereRecherche = strtoupper($search->getName());
                    $filter = true;
                }

                // Filtre
                if($filter)
                    $pos = strpos($nomProjet, $critereRecherche);

                if($filter == false || ($filter == true && $pos !== false)){
                    $proj = new Project();
                    $proj->setName($nomProjet);
                    $proj->setGid($p["gid"]);
                    array_push($projects,$proj);
                }
            }

        } catch (ClientExceptionInterface | TransportExceptionInterface | DecodingExceptionInterface | RedirectionExceptionInterface | ServerExceptionInterface $e) {
            return new Response($e->getMessage());
        }

        return $this->render('pages/projects.html.twig',[
            'projects' => $projects,
            'form' => $form->createView()
        ]);
    }

}