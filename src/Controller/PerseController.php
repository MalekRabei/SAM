<?php

namespace App\Controller;

use App\Entity\Employee;
use App\Entity\Perse;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PerseController extends Controller
{

    public function new()
    {
        $currentdate = new \DateTime('now');
        $perse = new Perse();
        if (isset($_POST['numeroPerse'])) {
            $numeroPerse = $_POST['numeroPerse'];
            $note = $_POST['note'];


            $em = $this->getDoctrine()->getManager();

            //recuperer l id user
            $usr= $this->get('security.token_storage')->getToken()->getUser()->getId();
            $user=$em->getRepository(User::class)->find($usr);

            $perse->setNumPerse($numeroPerse);
            $perse->setNote($note);
            $perse->setDatePerse($currentdate);
            $perse->setIdEmployee($user);

            $em->persist($perse);
            $em->flush();
            return $this->redirectToRoute("ajoutPerse");

        }
       /// $notifications = $this->getDoctrine()->getRepository(Notification::class)->findAll();
        $listEmployee = $this->getDoctrine()->getRepository(Employee::class)->findAll();

        //recuperer l id user
        $usr= $this->get('security.token_storage')->getToken()->getUser()->getId();
        $list = $this->getDoctrine()->getRepository(Perse::class)->listByUser($usr);

        $manager = $this->get('mgilet.notification');
        $notif = $manager->createNotification('Hello world !');
        $notif->setMessage('This a notification.');
        $notif->setLink('http://symfony.com/');
        $manager->addNotification(array($perse), $notif, true);

        return $this->render('perse/new.html.twig', array(

            'listEmployee' => $listEmployee,
            'notifications'=>"test",
            'list' => $list ,
            'currentDate'=>$currentdate));

    }

    public function edit(Request $request, $id)
    {
        $currentdate = new \DateTime('now');
        $em = $this->getDoctrine()->getManager();

        $perse =  $this->getDoctrine()->getRepository(Perse::class)->find($id);

        $numeroPerseA= $perse->getNumPerse();
        $noteA= $perse->getNote();

        if (isset($_POST['numeroPerse'])) {
            $numeroPerse = $_POST['numeroPerse'];
            $note = $_POST['note'];
            //recuperer l id user
            $usr= $this->get('security.token_storage')->getToken()->getUser()->getId();
            $user=$em->getRepository(User::class)->find($usr);
            $perse->setNumPerse($numeroPerse);
            $perse->setNote($note);
            $perse->setDatePerse($currentdate);
            $perse->setIdEmployee($user);

            $em->persist($perse);
            $em->flush();
            return $this->redirectToRoute("ajoutPerse");

        }
       // $notifications = $this->getDoctrine()->getRepository(Notification::class)->findAll();
        $listEmployee = $this->getDoctrine()->getRepository(Employee::class)->findAll();
        $list = $this->getDoctrine()->getRepository(Perse::class)->findAll();


        return $this->render('perse/edit.html.twig', array(
            'id'=>$id,
            'numeroPerse'=> $numeroPerseA,
            'note'=> $noteA,
            'listEmployee' => $listEmployee,
            'notifications'=>"test",
            'list' => $list ,
            'currentDate'=>$currentdate));
    }


    public function delete(request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $perse = $em->getRepository(Perse::class)->find($id);
        $em->remove($perse);
        $em->flush();
        return $this->redirectToRoute('ajoutPerse');
    }


    public function admin()
    {
        $currentdate = new \DateTime('now');
        $em = $this->getDoctrine()->getManager();

        //recuperer l id user
        $usr= $this->get('security.token_storage')->getToken()->getUser()->getId();
        $user=$em->getRepository(User::class)->find($usr);
        /// $notifications = $this->getDoctrine()->getRepository(Notification::class)->findAll();


        $listEmployee = $this->getDoctrine()->getRepository(Employee::class)->findAll();
        $list = $this->getDoctrine()->getRepository(Perse::class)->perseGroupedByUser();

        $listByUser = $this->getDoctrine()->getRepository(Perse::class)->listByUser($usr);
        $totalPerDay = $this->getDoctrine()->getRepository(Perse::class)->TotalPersePerDay($currentdate);

        var_dump($totalPerDay[0]["total"]);

        return $this->render('perse/index.html.twig', array(

            'listEmployee' => $listEmployee,
            'listByUser'=>$listByUser,
            'notifications'=>"test",
            'list' => $list ,
            'currentDate'=>$currentdate,
            'totalPerDay'=>$totalPerDay[0]["total"]));

    }

//par mois + jours + user
    public function calcul()
    {
        return $this->render('perse/calcul.html.twig', array(
            // ...
        ));
    }


}
