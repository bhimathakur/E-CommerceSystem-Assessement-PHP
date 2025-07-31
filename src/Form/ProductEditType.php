<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Product;
use App\Enum\Status;
use App\Form\ProductType;
use App\Repository\CategoryRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductEditType extends ProductType
{


    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);
        $status = Status::ACTIVE;
        $catId = $options['cat_id'];

        $builder
            ->add('category', EntityType::class, [
                'class' => Category::class,
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
                'data' => $options['category'],
            ])

            ->add('subcat_id', EntityType::class, [
                'class' => Category::class,
                'error_bubbling' => true,
                'query_builder' => function (CategoryRepository $categoryRepo) use ($status, $catId) {
                    return $categoryRepo->createQueryBuilder('c')
                        ->where('c.sub_cat_id = :sub_cat_id')
                        ->andWhere('c.status = :status')
                        ->setParameter('sub_cat_id', $catId)
                        ->setParameter('status', $status);
                },
                'choice_label' => 'title',
                'label' => 'Sub Category',
                'attr' => ['label' => 'Sub Category'],
                'data' => $options['sub_category'],
            ])

            ->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
                $form = $event->getForm();
                $subCat = $event->getData()['subcat_id'];
                if ($subCat) {
                    $form->add('subcat_id', ChoiceType::class, ['choices' => [$subCat => $subCat]]);
                }
            })
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
            'csrf_protection' => false,
            'cat_id' => 0,
            'allow_extra_fields' => true,
            'category' => null,
            'sub_category' => null,
            'edit' => false,
        ]);
    }
}
