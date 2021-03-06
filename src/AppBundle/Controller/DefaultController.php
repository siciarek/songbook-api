<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Controller\CommonController as Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @Route("/ping", name="ping")
     */
    public function pingAction(Request $request)
    {

    }

    /**
     * @Route("/reset", name="default.reset")
     */
    public function resetAction(Request $request)
    {
        if ($this->get('kernel')->getEnvironment() === 'prod') {
            throw $this->createNotFoundException();
        }

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
