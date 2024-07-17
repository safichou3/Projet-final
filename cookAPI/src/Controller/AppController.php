<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\ConstraintViolationList;

class AppController extends AbstractController
{
    protected EntityManagerInterface $em;
    protected Security $security;
    protected SerializerInterface $serializer;

    public function __construct(EntityManagerInterface $em, Security $security, SerializerInterface $serializer)
    {
        $this->em = $em;
        $this->security = $security;
        $this->serializer = $serializer;
    }

    protected function jsonNotFound(string $name = 'entity') : JsonResponse
    {
        return new JsonResponse(
            ['status' => 404, 'message' => $name . ' not found'],
            Response::HTTP_NOT_FOUND
        );
    }

    protected function getFormErrors(ConstraintViolationList $violations) : array
    {
        $errors = array();
        foreach ($violations as $violation) {
            $errors[$violation->getPropertyPath()] = $violation->getMessage();
        }
        $data = array(
            'status'  => Response::HTTP_BAD_REQUEST,
            'success' => false,
            'violations' => $errors,
        );
        return $data;
    }

    protected function getFormErrorsFormType($form) : array
    {
        $errors = array();
        // Global
        foreach ($form->getErrors() as $error) {
            $errors[$form->getName()][] = $error->getMessage();
        }
        // Fields
        foreach ($form as $child /** @var Form $child */) {
            if (!$child->isValid()) {
                foreach ($child->getErrors() as $error) {
                    $errors[$child->getName()] = $error->getMessage();
                }
            }
        }
        return $errors;
    }

    public function jsonBadRequest($errors) : JsonResponse
    {
        return new JsonResponse($this->serializer->serialize($this->getFormErrors($errors), 'json'), Response::HTTP_BAD_REQUEST, [], true);
    }
}
