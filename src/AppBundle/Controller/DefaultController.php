<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Controller\CommonController as Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/reset", name="default.reset")
     */
    public function resetAction(Request $request)
    {
        $projectDir = realpath(implode(DIRECTORY_SEPARATOR, [
            $this->get('kernel')->getRootDir(),
            '..',
        ]));

        chdir($projectDir);

        $cmd = 'ant db;ant fix';

        $output = `$cmd`;

        $data = [
            'success' => true,
            'msg' => 'Reset',
            'data' => [
                'command' => $cmd,
                'output' => $output,
            ],
        ];

        return $this->getJsonResponse($data);
    }
}
