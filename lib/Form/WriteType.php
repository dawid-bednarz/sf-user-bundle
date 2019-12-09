<?php

namespace DawBed\UserBundle\Form;

use DawBed\UserBundle\Model\WriteModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 *  * Dawid Bednarz( dawid@bednarz.pro )
 * Read README.md file for more information and licence uses
 */
class WriteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('email');
        if ($options['method'] == Request::METHOD_PUT) {
            $this->updateFields($builder, $options);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => WriteModel::class
        ]);
    }

    public function getBlockPrefix(): string
    {
        return 'UserWrite';
    }

    private function updateFields(FormBuilderInterface $builder, array $options)
    {
        $builder->add('changePassword', CheckboxType::class, [
            'false_values' => ['false', '0'],
        ]);
        $builder->add('changeEmail', CheckboxType::class, [
            'false_values' => ['false', '0'],
        ]);
    }
}