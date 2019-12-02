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
    protected $privateKey;

    /**
     * HomeController constructor.
     */
    public function __construct()
    {
        $this->client = HttpClient::create(['http_version' => '1.1']);;
        $this->privateKey = '0/0b1b7b5d59f146cdbb5de67e5e0ad52e';
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
            'form' => $form->createView(),
            'current_menu' => 'projects'
        ]);
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
        $fields = 'name,notes';
        $tasks = [];
        try{
            $proj_task = $this->client->request('GET', sprintf("https://app.asana.com/api/1.0/projects/%d/tasks?opt_fields=%s", $project,$fields),[
                'auth_bearer' => $this->privateKey
            ]);
            if($proj_task->toArray()['data'] != null)
            {
                foreach ($proj_task->toArray()['data'] as $task)
                {
                    if(isset($task['name']))
                        array_push($tasks,$task['name']);
                }
            }
        } catch (TransportExceptionInterface | ClientExceptionInterface | DecodingExceptionInterface | RedirectionExceptionInterface | ServerExceptionInterface $e) {

        }

        return $tasks;
    }

    public function getSectionsFromProject(string $project): array
    {
        $sections = [];
        try {
            $projectSections = $this->client->request('GET', sprintf('https://app.asana.com/api/1.0/projects/%d/sections', $project), [
                'auth_bearer' => $this->privateKey
            ]);
            if($projectSections->toArray()['data'] != null)
            {
                foreach ($projectSections->toArray()['data'] as $section)
                {
                    if(isset($section['name']))
                        array_push($sections,[$section['gid'],$section['name']]);
                }
            }
        } catch (TransportExceptionInterface | ClientExceptionInterface | DecodingExceptionInterface | RedirectionExceptionInterface | ServerExceptionInterface $e) {

        }
        return $sections;
    }

    public function getTasksFromSection(array $sections): array
    {
        $newSections = [];

        $fields = 'name,notes';
        try {
            foreach ($sections as $section)
            {
                $tasksList = [];

                $sectionGid = $section[0];
                $tasks = $this->client->request('GET', sprintf('https://app.asana.com/api/1.0/sections/%d/tasks?opt_fields=%s', $sectionGid,$fields), [
                    'auth_bearer' => $this->privateKey
                ]);
                if($tasks->toArray()['data'] != null)
                {
                    foreach ($tasks->toArray()['data'] as $task)
                    {
                        if(isset($task['name']))
                            array_push($tasksList,$task['name']);
                    }
                }
                array_push($newSections,[$section[0],$section[1],$tasksList]);
            }
        } catch (TransportExceptionInterface | ClientExceptionInterface | DecodingExceptionInterface | RedirectionExceptionInterface | ServerExceptionInterface $e) {

        }
        return $newSections;
    }

    /**
     * @Route ("/projects/{project}/{name}", name="projectId")
     * @param $project
     * @param $name
     * @return Response
     */
    public function showProjectDetails($project, $name): Response
    {
        $tasksSections = [];
//        $tasks = $this->getTasksFromProject($project);
        $sections = $this->getSectionsFromProject($project);
        $tasksSections = $this->getTasksFromSection($sections);
//        dump($tasksSections);
        return $this->render('pages/projectTasks.html.twig',[
            'name' => $name,
            'tasksSections' => $tasksSections
        ]);
    }
}