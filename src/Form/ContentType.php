<?php

namespace App\Form;

use App\Entity\ContentManagement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class ContentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $required = true;
        $notBlanck =  new NotBlank(['message' => 'Please upload a file.']);
        $newFile =  new File([
            'maxSize' => '2M',
            'mimeTypes' => ['image/jpeg', 'image/png', 'image/gif'],
            'mimeTypesMessage' => 'Please upload a valid image (JPG, PNG, or GIF).',
        ]);
        $constraints = [$notBlanck, $newFile];
        if ($options['edit']) {
            $constraints = [$newFile];
            $required = false;
        }
        $constraints = [$notBlanck, $newFile];
        if ($options['edit']) {
            $constraints = [$newFile];
            $required = false;
        }

        $builder
            ->add('title', TextType::class, ['required' => true, 'error_bubbling' => true])
            ->add('image', FileType::class, [
                'mapped' => false,
                'required' => $required,
                'constraints' => $constraints,

            ])
            ->add('description', TextareaType::class, ['required' => false, 'error_bubbling' => true])
            ->add('submit', SubmitType::class, [
                'attr' => ['class' => 'btn btn-primary']
            ])

            ->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
                $form = $event->getForm();
                $description = $event->getData()['description'];
                if ($description) {
                    $form->add('description', TextareaType::class, ['data' => $description]);
                }
            })
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ContentManagement::class,
            'csrf_protection' => false,
            'allow_extra_fields' => true,
            'edit' => false
        ]);
    }
}
