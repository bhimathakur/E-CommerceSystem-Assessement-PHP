<?php

namespace App\Form;

use App\Entity\Category;
use EmilePerron\TinymceBundle\Form\Type\TinymceType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;

class CategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
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
        $builder
            ->add('sub_cat_id')
            ->add('title', TextType::class, ['required' => true, 'error_bubbling' => true])
            ->add('image', FileType::class, [
                'error_bubbling' => true,
                'label' => 'Image (JPG, PNG, or GIF)',
                'mapped' => false, // Important:  This field is not directly mapped to an entity property.
                'required' => $required,
                'constraints' => $constraints,
            ])
            ->add('description', TextareaType::class, ['required' => false, 'error_bubbling' => true])
            ->add(
                'submit',
                SubmitType::class,
                [
                    'attr' => ['class' => 'btn btn-primary']
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Category::class,
            'csrf_protection' => false,
            'allow_extra_fields' => true,
            'csrf_protection' => false,
            'edit' => false,
        ]);
    }
}
