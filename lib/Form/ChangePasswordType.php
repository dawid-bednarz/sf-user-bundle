<?php

/**
 * @author Dawid Bednarz( dawid@bednarz.pro )
 * @license Read README.md file for more information and licence uses
 */

namespace DawBed\UserBundle\Form;

use DawBed\ConfirmationBundle\Entity\AbstractToken;
use DawBed\UserBundle\Enum\TokenEnum;
use DawBed\UserBundle\Model\ChangePasswordModel;
use DawBed\UserBundle\Service\PasswordService;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChangePasswordType extends AbstractType
{
    private $passwordService;

    public function __construct(PasswordService $passwordService)
    {
        $this->passwordService = $passwordService;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('password', RepeatedType::class, [
            'constraints' => $this->passwordService->getConstraints()
        ]);
        $builder->add('token', EntityType::class, [
            'class' => AbstractToken::class,
            'choice_value' => 'value',
            'query_builder' => function ($repo) {
                $qb = $repo->getValidTokenQueryBuilder('token');
                $qb->andWhere('token.type=:type')
                    ->setParameter('type', TokenEnum::CHANGE_PASSWORD_TYPE);

                return $qb;
            }
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ChangePasswordModel::class
        ]);
    }

    public function getBlockPrefix(): string
    {
        return 'UserChangePassword';
    }
}