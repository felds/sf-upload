<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\Upload;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/upload")
 */
class UploadController extends Controller
{
    /**
     * @Route("/{id}", name="upload")
     */
    public function viewAction(Request $request, Upload $entity)
    {
        $response = new BinaryFileResponse($entity->getFile());
        $response->prepare($request);

        return $response;
    }
}