<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 16/02/2020
 * Time: 01:52
 */

namespace App\Form\Type;


use App\Entity\Media\Image;
use App\Service\ImageManager;
use App\Util\UploadedBase64File;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image as AssertImage;

class ImageType extends AbstractType {
    /**
     * @var ImageManager
     */
    private ImageManager $imageManager;

    public function __construct(ImageManager $imageManager) {
        $this->imageManager = $imageManager;
    }

    public function configureOptions(OptionsResolver $resolver): void {
        $resolver->setDefaults([
            'data_class' => Image::class,
            'file_options' => [],
            'checkbox_options' => [],
            'multiple' => false,
        ]);
        $resolver->setRequired('context');
        $resolver->setAllowedTypes('context', 'string');
        $resolver->setDefined('multiple');
    }

    public function getBlockPrefix(): string {
        return 'image_type';
    }

    /**
     * Builds the Image entity based on the UploadedFile
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('image', FileType::class, array_merge([
            'mapped' => false,
            'multiple' => false,
            'constraints' => array_merge([
                new AssertImage(),
            ], $options['constraints']),
        ], $options['file_options']));
        $builder->add('remove', CheckboxType::class, array_merge([
            'required' => false,
            'mapped' => false,
        ], $options['checkbox_options']));

        $builder->addEventListener(FormEvents::PRE_SUBMIT, static function (FormEvent $event){
            $image = $event->getData()['image'] ?? false;
            if($image && !$image instanceof UploadedFile){
                $image = new UploadedBase64File($image);
                $data = $event->getData();
                $data['image'] = $image;
                $event->setData($data);
            }
        });

        $builder->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) use ($options) {
            //TODO delete image
            $form = $event->getForm();

            $file = $event->getForm()->get('image')->getData();
            $remove = (bool) ($event->getForm()->get('remove')->getData() ?? false);

            if ($remove === true) {
                $form->setData(null);
                return;
            }

            if ($file === null) {
                return;
            }

            if($form->isSubmitted() && $form->isValid()) {
                $this->imageManager->upload($file, $options['context'], $form->getData());
            }
        });

    }
}