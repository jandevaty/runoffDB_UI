<?php


namespace App\Form;


use App\Entity\Measurement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MeasurementType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('descriptionCZ', TextareaType::class, ['label' => 'descriptionCZ','required'=>false])
            ->add('parent_id', HiddenType::class, ['mapped' => false])
            ->add('descriptionEN', TextareaType::class, ['label' => 'descriptionEN','required'=>false])
            ->add('noteCZ', TextareaType::class, ['label' => 'noteCZ','required'=>false])
            ->add('noteEN', TextareaType::class, ['label' => 'noteEN','required'=>false])
            ->add('save', SubmitType::class, [
                'attr' => ['class' => 'btn btn-success'],
                'label' => 'save'
            ]);

        $builder->add('records', CollectionType::class, [
            'entry_type' => RecordType::class,
            'label'=>'record',
            'mapped' => true,
            'prototype' => true,
            'allow_add' => true,
            'allow_delete' => true,
            'required'=>false
        ]);
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults([
            'data_class' => Measurement::class,
        ]);
    }
}