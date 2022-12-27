<?php

namespace App\Controller;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

use App\Entity\Event;
use App\Form\EventType;
use App\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/user/events')]
class EventsController extends AbstractController
{

    #[Route('/', name: 'app_events_index', methods: ['GET'])]
    public function index(EventRepository $eventRepository): Response
    {
        $user = $this->get('security.token_storage')->getToken()->getUser()->getUsername();

        return $this->render('events/index.html.twig', [
            'events' => $eventRepository->findAll(),
            'user' => $user ,
        ]);
    }
    #[Route('/mes', name: 'app_events_indexMy', methods: ['GET'])]
    public function indexMy(EventRepository $eventRepository): Response
    {
        $user = $this->get('security.token_storage')->getToken()->getUser()->getUsername();
        $userId = $this->get('security.token_storage')->getToken()->getUser()->getId();


        return $this->render('events/MesEvent.html.twig', [
            'events' => $eventRepository->findAll(),
            'userId' => $userId ,
            'user'=>$user,
        ]);
    }
    #[Route('/add', name: 'app_add')]
    public function createEvents(EventRepository $eventRepository): Response
    {
        return $this->render('events/new.html.twig', [
            'events' => $eventRepository->findAll(),
        ]);
    }
    #[Route('/new', name: 'app_events_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EventRepository $eventRepository): Response
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

    $event = new Event();
    $event ->setTitle($request->query->get('_titre'));
    $event ->setDescription($request->query->get('_description'));
    $event ->setDateD($request->query->get('_date_d'));
    $event ->setDateF($request->query->get('_date_f'));
    $event ->setHeureD($request->query->get('_heure_d'));
    $event ->setHeureF($request->query->get('_heure_f'));
    $event ->setPrix($request->query->get('_prix'));
    $event ->setLieu($request->query->get('_lieu'));
    $event ->setImage($request->query->get('_image'));
    $event ->setUser($user);
    $event ->setValid(false);
    $event ->setArchive(false);
    $entityManager = $this->getDoctrine()->getManager();
    $entityManager->persist($event);
    $entityManager->flush();

        return $this->redirectToRoute('app_events_index');
    }

    #[Route('/{id}', name: 'app_events_show', methods: ['GET'])]
    public function show(Event $event): Response
    {
        $user = $this->get('security.token_storage')->getToken()->getUser()->getUsername();
        return $this->render('events/show.html.twig', [
            'event' => $event,
            'user' => $user ,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_events_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Event $event, EventRepository $eventRepository): Response
    {
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $eventRepository->save($event, true);

            return $this->redirectToRoute('app_events_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('events/edit.html.twig', [
            'event' => $event,
            'form' => $form,
        ]);
    }
    #[Route('/{id}/edita', name: 'app_events_edita', methods: ['GET', 'POST'])]
    public function edita(Request $request, Event $event, EventRepository $eventRepository): Response
    {

        $user = $this->get('security.token_storage')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();
        $event = $em->getRepository(Event::class)->find($event);
            $event ->setTitle($request->query->get('_titre'));
            $event ->setDescription($request->query->get('_description'));
            $event ->setDateD($request->query->get('_date_d'));
            $event ->setDateF($request->query->get('_date_f'));
            $event ->setHeureD($request->query->get('_heure_d'));
            $event ->setHeureF($request->query->get('_heure_f'));
            $event ->setPrix($request->query->get('_prix'));
            $event ->setLieu($request->query->get('_lieu'));
            $event ->setImage($request->query->get('_image'));
            $event ->setUser($user);
            $event ->setValid(false);
            $event ->setArchive(false);
            $em->flush();
            return $this->redirectToRoute('app_events_indexMy', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/delete/{id}', name: 'app_events_delete', methods: ['POST'])]
    public function delete(Request $request, Event $event, EventRepository $eventRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$event->getId(), $request->request->get('_token'))) {
            $eventRepository->remove($event, true);
        }

        return $this->redirectToRoute('app_events_index', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/{id}/participer', name: 'app_events_participer', methods: ['GET', 'POST'])]
    public function participer(Request $request, Event $event, EventRepository $eventRepository): Response
    {

        $user = $this->get('security.token_storage')->getToken()->getUser()->getUsername();
        $em = $this->getDoctrine()->getManager();
        $event = $em->getRepository(Event::class)->find($event);
            $data=$event ->getParticipations();
            if (in_array( $user, $data )){

            }else{
                array_push($data,$user);
                $event ->setParticipations($data);
                $em->flush();
            }
            return $this->redirectToRoute('app_events_indexMy', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/{id}/departiciper', name: 'app_events_departiciper', methods: ['GET', 'POST'])]
    public function Deleteparticiper(Request $request, Event $event, EventRepository $eventRepository): Response
    {

        $user = $this->get('security.token_storage')->getToken()->getUser()->getUsername();
        $em = $this->getDoctrine()->getManager();
        $event = $em->getRepository(Event::class)->find($event);
            $data=$event ->getParticipations();
            if (in_array( $user, $data )){
                $key = array_search($user, $data);
                if (false !== $key) {
                    unset($data[$key]);
                }
            $event ->setParticipations($data);
            $em->flush();
            }else{
                
                
            }
            return $this->redirectToRoute('app_events_participation', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/mes/participations', name: 'app_events_participation', methods: ['GET'])]
    public function participations(EventRepository $eventRepository): Response
    {
        $user = $this->get('security.token_storage')->getToken()->getUser()->getUsername();
        $userId = $this->get('security.token_storage')->getToken()->getUser()->getId();


        return $this->render('events/participations.html.twig', [
            'events' => $eventRepository->findAll(),
            'userId' => $userId ,
            'user'=>$user,
        ]);
    }
}
