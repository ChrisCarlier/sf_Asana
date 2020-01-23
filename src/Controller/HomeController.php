<?php


namespace App\Controller;

use App\Entity\Project;
use App\Entity\ProjectSearch;
use App\Entity\Team;
use App\Form\ProjectSearchType;
use phpDocumentor\Reflection\Types\Array_;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportException;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\NamedAddress;
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
     * @Route("/accessRequest", name="newAccessRequest")
     * @param Request $request
     * @param MailerInterface $mailer
     * @return Response
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     */
    public function newAccessRequest(Request $request, MailerInterface $mailer)
    {
        $form = $this->createFormBuilder()
            ->add('username',TextType::class)
            ->add('email',TextType::class)
            ->setMethod('POST')
            ->getForm();

        $form->handleRequest($request);

        if ($request->isMethod('POST')) {
            if ($form->isSubmitted() && $form->isValid()) {
                $data = $form->getData();
                $this->sendAccessRequest($data['username'],$data['email'],$mailer);
                return $this->render('pages/message.html.twig',[
                    'message' => 'Demande envoyée !'
                ]);
            }
        }
        return $this->render('security/accessRequest.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/msg", name="sendAccessRequest")
     * @param MailerInterface $mailer
     * @param $username
     * @param $email
     * @return Response
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     */
    public function sendAccessRequest($username,$email,MailerInterface $mailer)
    {
        $email = (new TemplatedEmail())
            ->from(new NamedAddress('no-reply@ccarlier.be','My BIV'))
            ->to(new NamedAddress($email,$username))
            ->subject('Demande d\'accès au site')
            ->htmlTemplate('email\accessRequest.html.twig')
            ->context([
                'user'=> $username,
                'email'=>$email
            ]);
        try{
            $mailer->send($email);
        }
        catch (TransportException $e)
        {
            return $this->render('bundles/TwigBundle/Exception/error.html.twig');
        }

        return $this->render('pages/message.html.twig',[
            'message' => 'Demande envoyée !'
        ]);
    }

    /**
     * @return Response
     * @Route("/emailAccessRequest", name="emailAccessRequest")
     */
    public function viewEmailAccessRequest()
    {
        return $this->render('email/accessRequest.html.twig');
    }


}