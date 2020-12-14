<?php

namespace App\Controller;

use App\Entity\Calcul;
use App\Entity\Conge;
use App\Entity\Employee;
use App\Entity\Presence;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CongeController extends AbstractController
{
    /**
     * @Route("/conge", name="conge")
     */
    public function index(): Response
    {
        return $this->render('conge/index.html.twig', [
            'controller_name' => 'CongeController',
        ]);
    }
    /*************************** Gestion Congé***************************/

    public function ajoutConge(Request $request)
    {
        $conge = new Conge();
        if (isset($_POST['motif'])) {
            $motif = $_POST['motif'];
            $dateDebut = $_POST['dateDebut'];
            $dateFin = $_POST['dateFin'];
            $nbJours = $_POST['nbJours'];
            $idClient = $_POST['idEmployee'];
            $id = intval($idClient);
            $employee = $this->getDoctrine()->getRepository(Employee::class)->find($id);


            $em = $this->getDoctrine()->getManager();

            //recuperer l id user
            // $usr= $this->get('security.token_storage')->getToken()->getUser()->getId();
            // $user=$em->getRepository('UserBundle:User')->find($usr);

            $conge->setMotif($employee->getNom()." ".$employee->getPrenom()." ".$motif);
            $conge->setDateDebut(new \DateTime($_POST['dateDebut']));
            $conge->setDateFin(new \DateTime($_POST['dateFin']));
            $conge->setNbJours($nbJours);
            $conge->setEtat('Non validé');
            $conge->setIdEmployee($employee);


            $em->persist($conge);
            $em->flush();
            return $this->redirectToRoute("listConge");

        }
      //  $notifications = $this->getDoctrine()->getRepository(Notification::class)->findAll();
        $listEmployee = $this->getDoctrine()->getRepository(Employee::class)->findAll();

        $currentdate = new \DateTime('now');
        return $this->render('conge/ajoutConge.html.twig', array(
            'listEmployee' => $listEmployee,
            'notifications'=>"test",
            'currentDate'=>$currentdate));


    }

    public function modifConge(Request $request, $id)
    {
        //$notifications = $this->getDoctrine()->getRepository(Notification::class)->findAll();
        $em = $this->getDoctrine()->getManager();
        //recuperer l id user
        //$usr= $this->get('security.token_storage')->getToken()->getUser()->getId();
        // $user=$em->getRepository('UserBundle:User')->find($usr);
        //recuper le conge
        $conge = $this->getDoctrine()->getRepository(Conge::class)->find($id);

        $motifA = $conge->getMotif();
        $dtDebutA = $conge->getDateDebut();
        $dtFinA = $conge->getDateFin();
        $nbJoursA = $conge->getNbJours();
        $idClientA = $conge->getIdClient();
        if (isset($_POST['motif'])) {
            //recuperer la saisie
            $motif = $_POST['motif'];
            $dateDebut = $_POST['dateDebut'];
            $dateFin = $_POST['dateFin'];
            $nbJours = $_POST['nbJours'];
            $idClient = $_POST['idEmployee'];
            $id = intval($idClient);
            $employee = $this->getDoctrine()->getRepository(Employee::class)->find($id);
            //MAJ nouvelles entrées
            $conge->setMotif($motif);
            $conge->setDateDebut(new \DateTime($_POST['dateDebut']));
            $conge->setDateFin(new \DateTime($_POST['dateFin']));
            $conge->setNbJours($nbJours);
            $conge->setIdClient($employee);

            $em->persist($conge);
            $em->flush();
            return $this->redirectToRoute("listConge");

        }


        $listEmployee = $this->getDoctrine()->getRepository(Employee::class)->findAll();
        $currentdate = new \DateTime('now');

        return $this->render('conge/modifConge.html.twig', array(
            'listEmployee' => $listEmployee,
            'motif' => $motifA,
            'dateDebut' => $dtDebutA,
            'dateFin' => $dtFinA,
            'nbJour' => $nbJoursA,
            'notifications'=>"test",
            'currentDate'=>$currentdate));
    }

    public function suppConge(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $conge = $em->getRepository(Conge::class)->find($id);
        $em->remove($conge);
        $em->flush();
        return $this->redirectToRoute('listConge');

    }

    public function listConge()
    {

        //$notifications = $this->getDoctrine()->getRepository(Notification::class)->findAll();



        $list = $this->getDoctrine()->getRepository(Conge::class)->findAll();

        $currentdate = new \DateTime('now');

        return $this->render('conge/listConge.html.twig', [
            'controller_name' => 'CongeController',
            'list' => $list,
            'notifications'=>"test",
            'currentDate'=>$currentdate
        ]);
    }

    public function soldeConge()
    {

       // $notifications = $this->getDoctrine()->getRepository(Notification::class)->findAll();
        $solde = $this->getDoctrine()->getRepository(Calcul::class)
            ->soldeQuery();


        $currentdate = new \DateTime('now');

        return $this->render('conge/soldeConge.html.twig', array(
            'solde' => $solde,
            'notifications'=>"test",
            'currentDate'=>$currentdate
        ));

    }


    public function listPDFAction(Request $request){

        $snappy =$this->get("knp_snappy.pdf");

        $em=$this->getDoctrine()->getManager();
        $list=$em->getRepository( Presence::class)-> PresentQuery();


        $html = $this->renderView("@SAM/Employee/PDFReport.html.twig", array('list' => $list));
        $fichier = "Votre rapport";
        $pdfPath = $this->getParameter('pdf_directory') . '/"' . $fichier . '.pdf"';
        return new Response(

            $snappy->getOutputFromHtml($html),
            200,
            array(
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment;pdf_directory/filename"' . $fichier . '.pdf"'
            )
        );

    }

}
