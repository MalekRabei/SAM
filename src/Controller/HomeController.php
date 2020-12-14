<?php

namespace App\Controller;

use App\Entity\Calcul;
use App\Entity\Employee;
use App\Entity\Poste;
use App\Entity\Presence;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{

    public function index(Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_USER', null, 'Unable to access this page!');

        $list = $this->getDoctrine()
            ->getRepository(Employee::class)->findAll();
        if ($request->isMethod('POST')) {
            $nom = $request->get('nom');
            $list = $this->getDoctrine()
                ->getRepository(Employee::class)->findBy(array('nom' => $nom));
        }
        //$notifications = $this->getDoctrine()->getRepository(Notification::class)->findAll();
        $currentdate = new \DateTime('now');


// or you can keep the channel pram empty and will be broadcasted on "notifications" channel by default
       // $pusher->trigger($data);
        $nbterna=0;
        $nbShapeTek=0;
        $nbpresentTerna=0;
        $nbCongeTerna=0;
        $nbCongeShapeTek=0;
        $nbpresentSh=0;

        if(isset($nbemployeeTerna)){
            $nbemployeeTerna = $this->getDoctrine()
                ->getRepository(Calcul::class)->totalEmployeeTernaQuery();
            $nbterna = $nbemployeeTerna[0]["total"];
            var_dump($nbterna);}
        if(!isset($nbCongeTerna )){
            $nbCongeTerna = $this->getDoctrine()
                ->getRepository(Calcul::class)->nbCongeTernaQuery();
            $nbCongeTerna = $nbCongeTerna[0]["nbConge"];
            $nbpresentTerna = intval($nbterna) - intval($nbCongeTerna);}
        if(!isset($nbCongeShapeTek)) {
            $nbemployeeShapeTek = $this->getDoctrine()
                ->getRepository(Calcul::class)->totalEmployeeShapeTekQuery();
            $nbShapeTek = $nbemployeeShapeTek[0]["total"];
        }
        if(!isset($nbCongeShapeTek)) {
            $nbCongeShapeTek = $this->getDoctrine()
                ->getRepository(Calcul::class)->nbCongeShaptekQuery();
            $nbCongeShapeTek = $nbCongeShapeTek[0]["nbConge"];
            $nbpresentSh = intval($nbShapeTek) - intval($nbCongeShapeTek);
        }
        $poste= $this->getDoctrine()->getRepository(Poste::class)->posteQuery();
        return $this->render('home/index.html.twig', array(
            'poste'=>$poste,
            'currentDate' => $currentdate,
            'list' => $list,
            'nbTerna' => $nbterna
        , 'nbShapeTek' => $nbShapeTek
        , 'nbCongeShapeTek' => $nbCongeShapeTek,
            'nbCongeTerna' => $nbCongeTerna
        , 'nbpresent' => $nbpresentTerna,
            'nbpresentSh' => $nbpresentSh,
            'notifications'=>"test",
        ));

    }

    public function updatedIndex(Request $request)
    {

        $list = $this->getDoctrine()
            ->getRepository(Presence::class)->PresentQuery();
        if ($request->isMethod('POST')) {
            $nom = $request->get('nom');
            $list = $this->getDoctrine()
                ->getRepository(Employee::class)->findBy(array('nom' => $nom));
        }
       // $notifications = $this->getDoctrine()->getRepository(Notification::class)->findAll();
        $currentdate = new \DateTime('now');

// From your controller or service
       /* $data = array(
            'my-message' => "My custom message",
        );
        $pusher = $this->get('mrad.pusher.notificaitons');
        $channel = 'messages';
        $pusher->trigger($data, $channel);*/

// or you can keep the channel pram empty and will be broadcasted on "notifications" channel by default
       // $pusher->trigger($data);




        return $this->render('home/updated-index.html.twig', array(
            'currentDate' => $currentdate,
            'list' => $list,
            'notifications'=>"test",
        ));

    }
    public function Present($id){
        $presence = new Presence();
        $em = $this->getDoctrine()->getManager();
        $idE = $em->getRepository(Employee::class)->find($id);
        $listEmployee = $em->getRepository(Employee::class)->find($id);
        $idCurrent = $listEmployee->getId();

        $idPoste = $_POST['idPoste'];
        $idP = intval($idPoste);
        $poste = $this->getDoctrine()->getRepository(Poste::class)->find($idP);

        if (!isset($_POST['switch16' . $idCurrent])) {
            $present = "NON";
            $_POST['switch16' . $idCurrent] = $present;

        } else {
            $present = "OUI";
            $_POST['switch16' . $idCurrent] = $present;


        }

        if (!isset($_POST['switch18'.$idCurrent])) {
            $retard = "NON";
            $_POST['switch18'.$idCurrent] = $retard;

        } else {
            $retard = "OUI";
            $_POST['switch18'.$idCurrent] = $retard;


        }

        if ($present == "OUI") {
            $presence->setPresent("OUI");
            $presence->setDatePresence(new \DateTime('now'));
            $presence->setIdEmployee($idE);
            $listEmployee->setIdPoste($poste);

        } else if ($present == "NON") {
            $presence->setPresent("NON");
            $presence->setDatePresence(new \DateTime('now'));
            $presence->setIdEmployee($idE);
            $listEmployee->setIdPoste($poste);


        }

        if ($retard == "OUI") {
            $presence->setRetard("OUI");
            $presence->setDateRetard(new \DateTime(('now')));
            $presence->setIdEmployee($idE);
            $listEmployee->setIdPoste($poste);


        } else if ($retard == "NON") {
            $presence->setRetard("NON");
            $presence->setDateRetard(new \DateTime(('now')));
            $presence->setIdEmployee($idE);
            $listEmployee->setIdPoste($poste);


        }
        $em->persist($presence);
        $em->flush();

        return $this->redirectToRoute('updated_index');

    }


    public function rechercheAvanceAction(Request $request)
    {


        $em = $this->getDoctrine()->getManager();
        $requestString = $request->get('q');

        $emp = $em->getRepository(Employee::class)->rechercheQuery($requestString);

        if (isset( $emp)) {
            $result['employee']['error'] = "Post Not found :( ";
        } else {
            $result['employee'] = $this->getRealEntities($emp);
        }

        return new Response(json_encode($result));
    }

    public function getRealEntities($emp)
    {
        foreach ($emp as $employee) {
            $realEntities[$employee->getId()] = [
                $employee->getId(),
                $employee->getNom(),
                $employee->getPrenom(),
                $employee->getSociete(),
                $employee->getPoste(),
                $employee->getMatricule(),
                $employee->getCode(),
                $employee->getT(),
                $employee->getIdPositionnement()

            ];
        }
        return $realEntities;
    }

    public function listPDF(Request $request){

        $snappy =$this->get("knp_snappy.pdf");

        $em=$this->getDoctrine()->getManager();
        $list=$em->getRepository( Presence::class)-> PresentQuery();

        $html = $this->renderView("pdf_report.html.twig", array('list' => $list));
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
