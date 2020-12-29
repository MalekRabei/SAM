<?php

namespace App\Controller;

use App\Entity\Calcul;
use App\Entity\Conge;
use App\Entity\Employee;
use App\Entity\Presence;
use App\Entity\User;
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
        $currentdate = new \DateTime('now');
        $congelist = $this->getDoctrine()->getRepository(Conge::class)
            ->congeList();
        return $this->render('conge/index.html.twig', [
            'list'=>$congelist,
            'currentDate'=>$currentdate,
            'notifications'=>'test'
        ]);
    }
    /*************************** Gestion Congé***************************/

    public function ajoutConge(Request $request)
    {
        $conge = new Conge();
        $usr= $this->get('security.token_storage')->getToken()->getUser()->getId();

        if (isset($_POST['motif'])) {
            $motif = $_POST['motif'];
            $dateDebut = $_POST['dateDebut'];
            $dateFin = $_POST['dateFin'];
            $nbJours = $_POST['nbJours'];
           // $idClient = $_POST['idEmployee'];
            // $id = intval($idClient);


            $em = $this->getDoctrine()->getManager();

            //recuperer l id user
             $user=$em->getRepository(User::class)->find($usr);

            $conge->setMotif($user->getUsername()."  ".$motif);
            $conge->setDateDebut(new \DateTime($_POST['dateDebut']));
            $conge->setDateFin(new \DateTime($_POST['dateFin']));
            $conge->setNbJours($nbJours);
            $conge->setEtat('Non validé');
            $conge->setIdUser($user);


            $em->persist($conge);
            $em->flush();
            $usr= $this->get('security.token_storage')->getToken()->getUser()->getRoles();
            foreach ($usr as $role){
                if ($role != "ROLE_ADMIN" ){
                    echo 'supp from user ';
                    return $this->redirectToRoute('ajoutConge');

                } else
                    echo 'supp from admin';
                return $this->redirectToRoute('listConge');

            }

        }
      //  $notifications = $this->getDoctrine()->getRepository(Notification::class)->findAll();
        $listEmployee = $this->getDoctrine()->getRepository(Employee::class)->findAll();
        $solde = $this->getDoctrine()->getRepository(Calcul::class)
            ->soldeQueryByUser($usr);
        $congelist = $this->getDoctrine()->getRepository(Conge::class)
            ->congeByUser($usr);


        $currentdate = new \DateTime('now');

        return $this->render('conge/ajoutConge.html.twig', array(
            'listEmployee' => $listEmployee,
            'notifications'=>"test",
            'solde'=>$solde,
            'currentDate'=>$currentdate,
            'list'=>$congelist
            )
            );


    }

    public function modifConge(Request $request, $id)
    {
        //$notifications = $this->getDoctrine()->getRepository(Notification::class)->findAll();
        $em = $this->getDoctrine()->getManager();
        //recuperer l id user
        $usr= $this->get('security.token_storage')->getToken()->getUser()->getId();
         $user=$em->getRepository(User::class)->find($usr);
        //recuper le conge
        $conge = $this->getDoctrine()->getRepository(Conge::class)->find($id);

        $motifA = $conge->getMotif();
        $dtDebutA = $conge->getDateDebut();
        $dtFinA = $conge->getDateFin();
        $nbJoursA = $conge->getNbJours();
        $idClientA = $conge->getIdUser();
        if (isset($_POST['motif'])) {
            //recuperer la saisie
            $motif = $_POST['motif'];
            $dateDebut = $_POST['dateDebut'];
            $dateFin = $_POST['dateFin'];
            $nbJours = $_POST['nbJours'];
           // $idClient = $_POST['idEmployee'];
           // $id = intval($idClient);
            $employee = $this->getDoctrine()->getRepository(Employee::class)->find($id);
            //MAJ nouvelles entrées
            $conge->setMotif($user->getUsername()."  ".$motif);
            $conge->setDateDebut(new \DateTime($_POST['dateDebut']));
            $conge->setDateFin(new \DateTime($_POST['dateFin']));
            $conge->setNbJours($nbJours);
            $conge->setIdUser($user);

            $em->persist($conge);
            $em->flush();
            $usr= $this->get('security.token_storage')->getToken()->getUser()->getRoles();
            foreach ($usr as $role){
                if ($role != "ROLE_ADMIN" ){
                    echo 'supp from user ';
                    return $this->redirectToRoute('ajoutConge');

                } else
                    echo 'supp from admin';
                return $this->redirectToRoute('listConge');

            }

        }

        $solde = $this->getDoctrine()->getRepository(Calcul::class)
            ->soldeQueryByUser($usr);
        $congelist = $this->getDoctrine()->getRepository(Conge::class)
            ->congeByUser($usr);

        $listEmployee = $this->getDoctrine()->getRepository(Employee::class)->findAll();
        $currentdate = new \DateTime('now');

        return $this->render('conge/modifConge.html.twig', array(
            'listEmployee' => $listEmployee,
            'motif' => $motifA,
            'dateDebut' => $dtDebutA,
            'dateFin' => $dtFinA,
            'nbJour' => $nbJoursA,
            'notifications'=>"test",
            'solde'=>$solde,
            'currentDate'=>$currentdate,
            'list'=>$congelist,
            'id'=> $conge->getId()));
    }

    public function suppConge(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $conge = $em->getRepository(Conge::class)->find($id);
        $em->remove($conge);
        $em->flush();


        $usr= $this->get('security.token_storage')->getToken()->getUser()->getRoles();
        foreach ($usr as $role){
            if ($role != "ROLE_ADMIN" ){
                echo 'supp from user ';
                return $this->redirectToRoute('ajoutConge');

            } else
                echo 'supp from admin';
            return $this->redirectToRoute('listConge');

        }


    }
    public function valider(Request $request, $id){

        $em = $this->getDoctrine()->getManager();
        $conge = $this->getDoctrine()
            ->getRepository(Conge::class)->find($id);
        $conge->setEtat("Validé");
        $em->persist($conge);
        $em->flush();
        return $this->redirectToRoute("listConge");

    }

    public function rejeter(Request $request, $id){

        $em = $this->getDoctrine()->getManager();
        $conge = $this->getDoctrine()
            ->getRepository(Conge::class)->find($id);
        $conge->setEtat("Rejeté");
        $em->persist($conge);
        $em->flush();
        return $this->redirectToRoute("indexConge");

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
