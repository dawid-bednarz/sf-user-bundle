<?php

namespace DawBed\UserBundle\Form;

use DawBed\PHPClassProvider\ClassProvider;
use DawBed\StatusBundle\Entity\AbstractStatus;
use DawBed\UserBundle\Model\WriteModel;
use DawBed\UserBundle\Service\PasswordService;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 *  * Dawid Bednarz( dawid@bednarz.pro )
 * Read README.md file for more information and licence uses
 */
class WriteType extends AbstractType
{
    private $passwordService;

    public function __construct(PasswordService $passwordService)
    {
        $this->passwordService = $passwordService;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $passwordConstraints = $this->passwordService->getConstraints();

        foreach ($passwordConstraints as $passwordConstraint) {
            $passwordConstraint->groups = $options['validation_groups'];
        }
        $builder->add('email');
        $builder->add('password',null,[
            'constraints' => $passwordConstraints,
        ]);
        $builder->add('statuses', CollectionType::class, [
            'entry_type' => EntityType::class,
            'entry_options' => [
                'class' => ClassProvider::get(AbstractStatus::class),
                'query_builder' => function ($repo) {
                    $statuses = $repo->createQueryBuilder('s');
                    $statuses->join('s.groups', 'sg', 'WITH', 'sg.name=:name')
                        ->setParameter('name', 'user');
                    return $statuses;
                }
            ],
            'allow_add' => true,
            'allow_delete' => true
        ]);
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
}