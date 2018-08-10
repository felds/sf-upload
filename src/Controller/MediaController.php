<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\Media;
use App\Form\MediaType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/media")
 */
class MediaController extends Controller
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/")
     */
    public function indexAction()
    {
        $entities = $this->entityManager->getRepository(Media::class)->findAll();

        return $this->render('media/index.html.twig', [
            'entities' => $entities,
        ]);
    }

    /**
     * @Route("/new")
     */
    public function newAction(Request $request)
    {
        $entity = new Media();
        $form = $this->createForm(MediaType::class, $entity);
        $form->add('submit', SubmitType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($entity);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_media_edit', ['id' => $entity->getId()]);
        }

        return $this->render('media/form.html.twig', [
            'form' => $form->createView(),
            'entity' => $entity,
        ]);
    }

    /**
     * @Route("/{id}/edit")
     */
    public function editAction(Request $request, Media $entity)
    {
        $form = $this->createForm(MediaType::class, $entity);
        $form->add('submit', SubmitType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($entity);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_media_edit', ['id' => $entity->getId()]);
        }

        return $this->render('media/form.html.twig', [
            'form' => $form->createView(),
            'entity' => $entity,
        ]);
    }
}