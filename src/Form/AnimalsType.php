<?php

namespace App\Form;

use App\Entity\Animals;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Image;
use App\Entity\User;


class AnimalsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('missing', CheckboxType::class, [
                'label' => 'Disparu',
                'required' => false,
            ])
            ->add('found', CheckboxType::class, [
                'label' => 'Trouvé',
                'required' => false,
            ])
            ->add('name', TextType::class, [
                'label' => 'Nom',
            ])
            ->add('race')
            ->add('sexe')
            ->add('color', TextType::class, [
                'label' => 'Couleur'
            ])
            ->add('microship', CheckboxType::class, [
                'label' => 'Puce électronique',
                'required' => false
            ])
            ->add('sterelise', CheckboxType::class, [
                'label' => 'Stérélisé',
                'required' => false,
            ])
            ->add('description')
            ->add('particularity', TextareaType::class, [
                'label' => 'Particularité',
                'required' => false,
            ])
            ->add('address',TextType::class, [
                'label' => 'Adresse'
            ] )
            ->add('postcode', IntegerType::class, [
                'label' => 'Code Postal'
            ])
            ->add('city', TextType::class, [
                'label' => 'Ville'
            ])
            ->add('animal_found', CheckboxType::class, [
                'label' => 'Animal retrouvé',
                'required' => false
            ])
            ->add('image', ImageType::class, [])
                        
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Animals::class,
        ]);
    }
}
