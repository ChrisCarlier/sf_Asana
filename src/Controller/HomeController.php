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



    /**
     * @param string $project
     * @return array
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     *
     */
    public function getTasksFromProject(string $project): array
    {
        $fields = 'completed';
        $tasks = [];
        $proj_task = $this->client->request('GET', sprintf("https://app.asana.com/api/1.0/projects/%d/tasks?opt_fields[]=completed", $project),[
            'auth_bearer' => '0/0b1b7b5d59f146cdbb5de67e5e0ad52e',
//            'opt_fields' => ['completed']
        ]);
        dump($proj_task->toArray()['data']);
        if($proj_task->toArray()['data'] != null)
        {
            foreach ($proj_task->toArray()['data'] as $task)
            {
                array_push($tasks,$task['name']);
            }
        }
        return $tasks;
    }

    /**
     * @Route ("/project/{project}/{name}", name="projectId")
     * @param $project
     * @param $name
     * @return Response
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function showProjectDetails($project, $name): Response
    {
        $tasks = [];
        $tasks = $this->getTasksFromProject($project);

        return $this->render('pages/projectTasks.html.twig',[
            'name' => $name,
            'tasks' => $tasks
        ]);
    }

}