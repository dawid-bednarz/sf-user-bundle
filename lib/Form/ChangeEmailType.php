<?php

/**
 * @author Dawid Bednarz( dawid@bednarz.pro )
 * @license Read README.md file for more information and licence uses
 */

namespace DawBed\UserBundle\Form;

use DawBed\ComponentBundle\Enum\WriteTypeEnum;
use DawBed\ConfirmationBundle\Entity\AbstractToken;
use DawBed\UserBundle\Enum\TokenEnum;
use DawBed\UserBundle\Model\ChangeEmailModel;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChangeEmailType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('token', EntityType::class, [
            'class' => AbstractToken::class,
            'choice_value' => 'value',
            'query_builder' => function ($repo) {
                $qb = $repo->createQueryBuilder('token');
                $qb->andWhere('token.type=:type')
                    ->setParameter('type', TokenEnum::CHANGE_EMAIL_TYPE);

                return $qb;
            }
        ]);

        $builder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
            /**
             * @var ChangeEmailModel $model
             */
            $model = $event->getData();

            if($model->getToken() === null) {
                return;
            }
            $event->getForm()->add('email');
            $model->setEmail($model->getToken()->getData()['email']);
        });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ChangeEmailModel::class,
            'validation_groups' => WriteTypeEnum::UPDATE,
            'method' => Request::METHOD_PATCH
        ]);
    }

    public function getBlockPrefix(): string
    {
        return 'UserChangeEmail';
    }
}