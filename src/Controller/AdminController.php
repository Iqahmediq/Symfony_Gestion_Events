<?php
namespace App\Controller;
use App\Repository\EventRepository;
use App\Entity\Event;
use Symfony\Component\HttpFoundation\Request;

use App\Form\EventType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
#[Route('/admin/events')]
class AdminController extends AbstractController
{
 /**
 * @Route("/", name="admin")
 */

 public function index(EventRepository $eventRepository)
 {
     $user = $this->get('security.token_storage')->getToken()->getUser()->getUsername();

     return $this->render('admin/admin.html.twig', [
         'events' => $eventRepository->findAll(),
         'user' => $user ,
     ]);
 }
 #[Route('/new', name: 'admin_app_events_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EventRepository $eventRepository)
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

        return $this->redirectToRoute('admin');
    }
    #[Route('/add', name: 'admin_app_add')]
    public function createEvents(EventRepository $eventRepository)
    {
        return $this->render('admin/new.html.twig', [
            'events' => $eventRepository->findAll(),
        ]);
    }
 #[Route('/delete/{id}', name: 'admin_events_delete', methods: ['POST'])]
 public function delete(Request $request, Event $event, EventRepository $eventRepository)
 {
     if ($this->isCsrfTokenValid('delete'.$event->getId(), $request->request->get('_token'))) {
         $eventRepository->remove($event, true);
     }

     return $this->redirectToRoute('admin', []);
 }
  #[Route('/{id}', name: 'admin_events_show', methods: ['GET'])]
    public function show(Event $event)
    {
        return $this->render('admin/show.html.twig', [
            'event' => $event,
        ]);
    }
    #[Route('/{id}/confirm', name: 'admin_events_confirm', methods: ['GET', 'POST'])]
    public function confirmer(Request $request, Event $event, EventRepository $eventRepository)
    {
        $em = $this->getDoctrine()->getManager();
        $event = $em->getRepository(Event::class)->find($event);
            $event ->setValid(true);
            $event ->setArchive(true);
            $em->flush();
            return $this->redirectToRoute('admin', []);
    }
    
    #[Route('/mes/events', name: 'admin_mes_events', methods: ['GET'])]
    public function indexMy(EventRepository $eventRepository)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser()->getUsername();
        $userId = $this->get('security.token_storage')->getToken()->getUser()->getId();


        return $this->render('admin/MesEvent.html.twig', [
            'events' => $eventRepository->findAll(),
            'userId' => $userId ,
            'user'=>$user,
        ]);
    }
    #[Route('/non/Confirmer', name: 'app_events_nonconfirmer', methods: ['GET'])]
    public function participations(EventRepository $eventRepository)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser()->getUsername();
        $userId = $this->get('security.token_storage')->getToken()->getUser()->getId();


        return $this->render('admin/nonConfirmer.html.twig', [
            'events' => $eventRepository->findAll(),
            'userId' => $userId ,
            'user'=>$user,
        ]);
    }
    #[Route('/{id}/edit', name: 'admin_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Event $event, EventRepository $eventRepository)
    {
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $eventRepository->save($event, true);

            return $this->redirectToRoute('admin', []);
        }

        return $this->renderForm('admin/edit.html.twig', [
            'event' => $event,
            'form' => $form,
        ]);
    }
    #[Route('/{id}/edita', name: 'admin_edit_a', methods: ['GET', 'POST'])]
    public function edita(Request $request, Event $event)
    {

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
            $event ->setValid(false);
            $event ->setArchive(false);
            $em->flush();
            return $this->redirectToRoute('app_events_indexMy', []);
    }

}