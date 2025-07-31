<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Product;
use App\Enum\Status;
use App\Repository\CategoryRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\File;


class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $status = Status::ACTIVE;
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
            ->add('price', TextType::class, ['required' => true, 'error_bubbling' => true])

            ->add('subcat_id', TextType::class, ['required' => true])
            ->add('image', FileType::class, [
                'mapped' => false,
                'required' => $required,
                'constraints' => $constraints,

            ])
            ->add('description', TextareaType::class, ['required' => false, 'error_bubbling' => true])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'required' => true,
                'error_bubbling' => true,
                'query_builder' => function (CategoryRepository $categoryRepo) use ($status) {
                    return $categoryRepo->createQueryBuilder('c')
                        ->where('c.sub_cat_id = :sub_cat_id')
                        ->andWhere('c.status = :status')
                        ->setParameter('sub_cat_id', 0)
                        ->setParameter('status', $status);
                },
                'choice_label' => 'title',
                'label' => 'Category',
                'attr' => ['label' => 'Category'],
                'placeholder' => 'select category'
            ])
            ->add('submit', SubmitType::class, [
                'attr' => ['class' => 'btn btn-primary']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
            'csrf_protection' => false,
            'allow_extra_fields' => true,
            'edit' => false

        ]);
    }
}
