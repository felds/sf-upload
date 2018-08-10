<?php
declare(strict_types=1);

namespace App\Form;

use App\Entity\Upload;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UploadType extends AbstractType implements DataMapperInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var string
     */
    private $uploadsDir;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->uploadsDir = __DIR__ . '/../../var/uploads';
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id', HiddenType::class)
            ->add('file', FileType::class)
            ->add('submit', SubmitType::class)
            ->setDataMapper($this)
            ->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
                $data = $event->getData();

                if ($data['file'] instanceof UploadedFile) {
                    $upload = new Upload($data['file']->move($this->uploadsDir));
                    $this->entityManager->persist($upload);
                    $this->entityManager->flush();

                    $event->setData(array_replace(
                        $event->getData(), [
                        'id' => $upload->getId(),
                    ]));
                }
            })
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'required' => false,
            'data_class' => Upload::class,
            'empty_data' => null,
        ]);
    }

    public function mapDataToForms($data, $forms)
    {
        $fields = iterator_to_array($forms);

        if ($data instanceof Upload) {
            /** @var FormInterface $fields ['id'] */
            $fields['id']->setData($data->getId());
        }
    }

    public function mapFormsToData($forms, &$data)
    {
        /** @var FormInterface $fields ['id'] */
        $fields = iterator_to_array($forms);
        $id = $fields['id']->getData();

        if ($id) {
            $data = $this->entityManager->find(Upload::class, $id);
        }
    }
}