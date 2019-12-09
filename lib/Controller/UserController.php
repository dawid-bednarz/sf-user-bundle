<?php

namespace DawBed\UserBundle\Controller;

use DawBed\ComponentBundle\Enum\WriteTypeEnum;
use DawBed\ComponentBundle\Helper\EventResponseController;
use DawBed\StatusBundle\Provider;
use DawBed\UserBundle\Enum\StatusEnum;
use DawBed\UserBundle\Event\ChangedEmailEvent;
use DawBed\UserBundle\Event\ChangedPasswordEvent;
use DawBed\UserBundle\Event\CreateEvent;
use DawBed\UserBundle\Event\DeleteEvent;
use DawBed\UserBundle\Event\GetItemEvent;
use DawBed\UserBundle\Event\ListEvent;
use DawBed\UserBundle\Event\ListStatusEvent;
use DawBed\UserBundle\Event\UpdateEvent;
use DawBed\UserBundle\Form\ChangeEmailType;
use DawBed\UserBundle\Form\ChangePasswordType;
use DawBed\UserBundle\Form\ListType;
use DawBed\UserBundle\Form\WriteType;
use DawBed\UserBundle\Model\Criteria\ListCriteria;
use DawBed\UserBundle\Model\Criteria\WriteCriteria;
use DawBed\UserBundle\Service\GetService;
use DawBed\UserBundle\Service\WriteService;
use DawBed\UserBundle\Utils\ChangeEmail;
use DawBed\UserBundle\Utils\ChangePassword;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Dawid Bednarz( dawid@bednarz.pro )
 * @license Read README.md file for more information and licence uses
 */
class UserController extends AbstractController
{
    use EventResponseController;

    public function create(Request $request, WriteService $service): Response
    {
        $criteria = new WriteCriteria(WriteTypeEnum::CREATE);
        $criteria->setByDifferentUser(true);
        $model = $service->prepareModel($criteria);

        $form = $this->createForm(WriteType::class, $model, [
            'validation_groups' => WriteTypeEnum::CREATE
        ]);

        $form->handleRequest($request);

        if (!$form->isSubmitted()) {
            return $this->notSubmittedForm();
        }
        if (!$form->isValid()) {
            return $this->invalidForm($form);
        }
        $em = $service->make($model);

        $response = $this->response(new CreateEvent($model->getEntity()));

        $em->flush();

        return $response;
    }

    public function update(string $id, Request $request, WriteService $service): Response
    {
        $criteria = (new WriteCriteria(WriteTypeEnum::UPDATE))
            ->setByDifferentUser(true)
            ->setId($id);

        $model = $service->prepareModel($criteria);

        $form = $this->createForm(WriteType::class, $model, [
            'validation_groups' => WriteTypeEnum::UPDATE,
            'method' => Request::METHOD_PUT
        ]);
        $form->handleRequest($request);

        if (!$form->isSubmitted()) {
            return $this->notSubmittedForm();
        }
        if (!$form->isValid()) {
            return $this->invalidForm($form);
        }
        $em = $service->make($model);

        $response = $this->response(new UpdateEvent($model->getEntity()));

        $em->flush();

        return $response;
    }

    public function changePassword(Request $request, ChangePassword $service): Response
    {
        $form = $this->createForm(ChangePasswordType::class);

        $form->handleRequest($request);

        if (!$form->isSubmitted()) {
            return $this->notSubmittedForm();
        }
        if (!$form->isValid()) {
            return $this->invalidForm($form);
        }
        $model = $form->getData();

        $em = $service->make($model);

        $response = $this->response(new ChangedPasswordEvent($model->getUser()));

        $em->flush();

        return $response;
    }

    public function changeEmail(Request $request, ChangeEmail $service): Response
    {
        $form = $this->createForm(ChangeEmailType::class);

        $form->handleRequest($request);

        if (!$form->isSubmitted()) {
            return $this->notSubmittedForm();
        }
        if (!$form->isValid()) {
            return $this->invalidForm($form);
        }
        $model = $form->getData();

        $em = $service->make($model);

        $response = $this->response(new ChangedEmailEvent($model->getUser()));

        $em->flush();

        return $response;
    }

    public function item(string $id, GetService $service): Response
    {
        $user = $service->item($id);

        return $this->response(new GetItemEvent($user));
    }

    public function list(Request $request, GetService $service): Response
    {
        $criteria = new ListCriteria();

        $form = $this->createForm(ListType::class, $criteria);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if (!$form->isValid()) {
                return $this->invalidForm($form);
            }
        }
        return $this->response(new ListEvent($service->list($criteria)));
    }

    public function delete(string $id, WriteService $service): Response
    {
        $model = $service->prepareModel(
            (new WriteCriteria(WriteTypeEnum::DELETE))
                ->setId($id)
        );
        $em = $service->make($model);

        $response = $this->response(new DeleteEvent($model->getEntity()));

        $em->flush();

        return $response;
    }

    public function statuses(Provider $statusProvider): Response
    {
        $statuses = $statusProvider->getGroupQueryBuilder(StatusEnum::BASE_GROUP)
            ->getQuery()
            ->getResult();

        return $this->response(new ListStatusEvent($statuses));
    }
}