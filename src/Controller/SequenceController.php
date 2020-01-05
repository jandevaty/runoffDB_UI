<?php
/**
 * Created by PhpStorm.
 * User: ppavel
 * Date: 13.03.2019
 * Time: 22:14
 */

namespace App\Controller;

use App\Repository\SequenceRepository;
use App\Services\SequenceService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SequenceController extends AbstractController {

    /**
     * @Route("/sequence/{id}", name="sequence")
     */

    public function edit(EntityManagerInterface $entityManager, Request $request, int $id = null, SequenceService $sequenceService) {
        if ($id) {
            /*intenzita povrchového odtoku (homogenizovaná hrubá data)*/
            $mockup[] = [
                ['00:38:00', '3.680'],
                ['00:38:30', '3.770'],
                ['00:39:00', '3.860'],
                ['00:39:30', '3.960'],
                ['00:40:00', '4.050'],
                ['00:40:30', '4.150'],
                ['00:41:00', '4.230'],
                ['00:41:30', '4.310'],
                ['00:42:00', '4.390'],
                ['00:42:30', '4.470'],
                ['00:43:00', '4.550'],
                ['00:43:30', '4.730'],
                ['00:44:00', '4.910'],
                ['00:44:30', '5.090'],
                ['00:45:00', '5.270'],
                ['00:45:30', '5.440'],
                ['00:46:00', '5.610'],
                ['00:46:30', '5.780'],
                ['00:47:00', '5.950'],
                ['00:47:30', '6.120'],
                ['00:48:00', '6.290'],
                ['00:48:30', '6.150'],
                ['00:49:00', '6.020'],
                ['00:49:30', '5.880'],
                ['00:50:00', '5.750'],
                ['00:50:30', '5.610'],
                ['00:51:00', '5.850'],
                ['00:51:30', '6.090'],
                ['00:52:00', '6.330'],
                ['00:52:30', '6.570'],
                ['00:53:00', '6.810'],
                ['00:53:30', '7.030'],
                ['00:54:00', '7.260'],
                ['00:54:30', '7.490'],
                ['00:55:00', '7.720'],
                ['00:55:30', '7.950'],
                ['00:56:00', '8.160'],
                ['00:56:30', '8.380'],
                ['00:57:00', '8.600'],
                ['00:57:30', '8.810'],
                ['00:58:00', '9.030'],
                ['00:58:30', '9.220'],
                ['00:59:00', '9.400'],
                ['00:59:30', '9.590'],
                ['01:00:00', '9.780'],
                ['01:00:30', '9.970'],
                ['01:01:00', '10.270'],
                ['01:01:30', '10.570'],
                ['01:02:00', '10.870'],
                ['01:02:30', '11.170'],
                ['01:03:00', '11.470'],
                ['01:03:30', '11.490'],
                ['01:04:00', '11.520'],
                ['01:04:30', '11.550'],
                ['01:05:00', '11.580'],
                ['01:05:30', '11.610'],
                ['01:06:00', '11.610'],
                ['01:06:30', '11.610'],
                ['01:07:00', '11.610'],
                ['01:07:30', '11.610'],
                ['01:08:00', '11.610']
            ];
            $mockup[] = [
                ['00:38:00'], ['3.676'],
                ['00:40:30'], ['4.148'],
                ['00:43:00'], ['4.552'],
                ['00:45:30'], ['5.444'],
                ['00:48:00'], ['6.289'],
                ['00:50:30'], ['5.611'],
                ['00:53:00'], ['6.806'],
                ['00:55:30'], ['7.946'],
                ['00:58:00'], ['9.029'],
                ['01:00:30'], ['9.966'],
                ['01:03:00'], ['11.466'],
                ['01:05:30'], ['11.608']
            ];
            $mockup[] = [
                ['00:38:00'], ['0.033'],
                ['00:40:30'], ['0.039'],
                ['00:43:00'], ['0.052'],
                ['00:45:30'], ['0.073'],
                ['00:48:00'], ['0.081'],
                ['00:50:30'], ['0.102'],
                ['00:53:00'], ['0.123'],
                ['00:55:30'], ['0.145'],
                ['00:58:00'], ['0.203'],
                ['01:00:30'], ['0.234'],
                ['01:03:00'], ['0.252'],
                ['01:05:30'], ['0.331']
            ];
            $mockup[] = [
                ['00:38:00'], ['3.658'],
                ['00:40:30'], ['3.802'],
                ['00:43:00'], ['4.662'],
                ['00:45:30'], ['5.527'],
                ['00:48:00'], ['5.255'],
                ['00:50:30'], ['5.593'],
                ['00:53:00'], ['7.415'],
                ['00:55:30'], ['7.475'],
                ['00:58:00'], ['9.218'],
                ['01:00:30'], ['9.611'],
                ['01:03:00'], ['9.140'],
                ['01:05:30'], ['11.656']
            ];

            $grafdata = array_map(function ($m){
                $timearray = explode(":",$m[0]);
                return [($timearray[0] * 3600) + ($timearray[1] * 60) + $timearray[2],$m[1]];
            },$mockup[0]);

            $sequence = $sequenceService->getSequenceById($id);

            return $this->render('sequence/edit.html.twig', [
                'header'=>$sequenceService->getSequenceHeader($sequence),
                'runs'=>$sequenceService->getRunsArray($sequence),
                'mockups' => $mockup,
                'grafdata'=>$grafdata
            ]);
        } else {
            return $this->render('sequence/add.html.twig');
        }
    }

    /**
     * @Route("/sequences", name="sequences")
     */
    public function list(ContainerInterface $container, EntityManagerInterface $entityManager) {
        $renderer = $container->get('dtc_grid.renderer.factory')->create('datatables');
        $gridSource = $container->get('dtc_grid.manager.source')->get("App\Entity\Sequence");
        $renderer->bind($gridSource);
        $params = $renderer->getParams();
        return $this->render('sequence/list.html.twig',$params);
    }
}
