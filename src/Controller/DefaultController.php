<?php
declare(strict_types=1);

namespace App\Controller;

use App\Form\UploadType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends Controller
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $form = $this->createForm(UploadType::class);

        $form->handleRequest($request);

        return ['form.html.twig' => $form->createView()];
    }
}
