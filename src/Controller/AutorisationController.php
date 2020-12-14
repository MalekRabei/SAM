<?php

namespace App\Controller;

use App\Entity\Autorisation;
use App\Entity\Calcul;
use App\Entity\Employee;
use App\Entity\Poste;
use \Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AutorisationController extends AbstractController
{
    /**
     * @Route("/autorisation", name="autorisation")
     */
    public function index(Request $request): Response
    {

        $this->denyAccessUnlessGranted('ROLE_USER', null, 'Unable to access this page!');

        $list = $this->getDoctrine()
            ->getRepository(Autorisation::class)->findAll();
        if ($request->isMethod('POST')) {
            $nom = $request->get('nom');
            $list = $this->getDoctrine()
                ->getRepository(Employee::class)->findBy(array('nom' => $nom));
        }
      //  $notifications = $this->getDoctrine()->getRepository(Notification::class)->findAll();
        $currentdate = new \DateTime('now');

// From your controller or service
        /*$data = array(
            'my-message' => "My custom message",
        );
        $pusher = $this->get('mrad.pusher.notificaitons');
        $channel = 'messages';
        $pusher->trigger($data, $channel);

// or you can keep the channel pram empty and will be broadcasted on "notifications" channel by default
        $pusher->trigger($data);*/


        $poste= $this->getDoctrine()->getRepository(Poste::class)->posteQuery();

        return $this->render('autorisation/index.html.twig', [
            'controller_name' => 'AutorisationController',
            'poste'=>$poste,
            'currentDate' => $currentdate,
            'list' => $list,
            'notifications'=>"test",
        ]);
    }

    public function new()
    { $currentdate = new \DateTime('now');
        $autorisation = new Autorisation();
        if (isset($_POST['motif'])) {
            $motif = $_POST['motif'];

            $nbHeure = $_POST['nbJours'];
            $idEmployee = $_POST['idEmployee'];
            $id = intval($idEmployee);
            $employee = $this->getDoctrine()->getRepository(Employee::class)->find($id);


            $em = $this->getDoctrine()->getManager();

            //recuperer l id user
            // $usr= $this->get('security.token_storage')->getToken()->getUser()->getId();
            // $user=$em->getRepository('UserBundle:User')->find($usr);

            $autorisation->setMotif($employee->getNom()." ".$employee->getPrenom()." ".$motif);
            $autorisation->setDateAutorisation($currentdate);
            $autorisation->setNbHeure($nbHeure);
            $autorisation->setIdEmployee($employee);
            $autorisation->setEtat('Non validé');

            $em->persist($autorisation);
            $em->flush();
            return $this->redirectToRoute("listAutorisation");

        }
        //$notifications = $this->getDoctrine()->getRepository(Notification::class)->findAll();
        $listEmployee = $this->getDoctrine()->getRepository(Employee::class)->findAll();


        return $this->render('autorisation/new.html.twig', array(
            'listEmployee' => $listEmployee,
            'notifications'=>"test",
            'currentDate'=>$currentdate));


    }


    public function update(Request $request, $id)
    {
        $currentdate = new \DateTime('now');
        $em = $this->getDoctrine()->getManager();
        $autorisation = $this->getDoctrine()->getRepository(Autorisation::class)->find($id);

        $motifA = $autorisation->getMotif();
        $nbHeureA= $autorisation->getNbHeure();
        $dateDebut= $autorisation->getDateAutorisation();
        $idEmployee= $autorisation->getIdEmployee();


        if (isset($_POST['motif'])) {
            $motif = $_POST['motif'];
            $nbHeure = $_POST['nbJours'];
           // $idEmployee = $_POST['idEmployee'];
            //$id = intval($idEmployee);
            $employee = $this->getDoctrine()->getRepository(Employee::class)->find($id);


            //recuperer l id user
            // $usr= $this->get('security.token_storage')->getToken()->getUser()->getId();
            // $user=$em->getRepository('UserBundle:User')->find($usr);

            $autorisation->setMotif($employee->getNom()." ".$employee->getPrenom()." ".$motif);
            $autorisation->setDateAutorisation($currentdate);
            $autorisation->setNbHeure($nbHeure);
            $autorisation->setIdEmployee($employee);

            $em->persist($autorisation);
            $em->flush();
            return $this->redirectToRoute("listAutorisation");

        }
       // $notifications = $this->getDoctrine()->getRepository(Notification::class)->findAll();
        $listEmployee = $this->getDoctrine()->getRepository(Employee::class)->findAll();



        return $this->render('autorisation/edit.html.twig', array(
            'id'=>$id,
            'motif'=>$motifA ,
            'nbHeure'=> $nbHeureA,
            'dateDebut'=>$dateDebut,
            'idEmploye'=>$idEmployee,
            'listEmployee' => $listEmployee,
            'notifications'=>"test",
            'currentDate'=>$currentdate));
    }

    public function show()
    {
        return $this->render('autorisation/show.html.twig', array(
            // ...
        ));
    }


    public function delete(request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $autorisation = $em->getRepository(Autorisation::class)->find($id);
        $em->remove($autorisation );
        $em->flush();
        return $this->redirectToRoute('listAutorisation');
    }



}
