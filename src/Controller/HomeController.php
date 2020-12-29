<?php

namespace App\Controller;

use App\Entity\Calcul;
use App\Entity\Employee;
use App\Entity\Poste;
use App\Entity\Presence;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
// Include Dompdf required namespaces
use Dompdf\Dompdf;
use Dompdf\Options;


class HomeController extends AbstractController
{

    public function index(Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_USER', null, 'Unable to access this page!');

        $list = $this->getDoctrine()
            ->getRepository(User::class)->findAll();
        if ($request->isMethod('POST')) {
            $nom = $request->get('nom');
            $list = $this->getDoctrine()
                ->getRepository(User::class)->findBy(array('nom' => $nom));
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


    public function listPresent(Request $request)
    {
        $currentdate = new \DateTime('now');
        $list = $this->getDoctrine()
            ->getRepository(Presence::class)->rapport();

        return $this->render('home/updated-index.html.twig', array(
            'currentDate' => $currentdate,
            'list' => $list,
            'notifications'=>"test",
        ));

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

    public function listPDF(){

        $list = $this->getDoctrine()
            ->getRepository(Presence::class)->rapport();
        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);

        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('pdf_report.html.twig', [
            'title' => "Welcome to our PDF Test",
            'list'=>$list
        ]);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'landscape');

        // Render the HTML as PDF
        $dompdf->render();

        //For view
        //$dompdf->stream("",array("Attachment" => false));
      // for download
        return new Response (
            $dompdf->stream());

    }

}
