<?php

/**
 * Copyright (c) 2015 - Kodear
 */

namespace ApiBundle\Service;

use Symfony\Component\Form\FormInterface;

/**
 * Servicio con helpers Generales de Users
 * @package ApiBundle\Service
 */
class FormErrorsService {

    public function __construct() {

    }

    /**
     * Returns all the errors in a Form
     * @param  FormInterface $form
     * @return array $errors
     */
    public function getFormErrors(FormInterface $form)
    {
        $errors = [];
        foreach ($form->all() as $field) {
            if ($field->getErrors(true)->count() > 0) {
                $fieldName = $field->getName();
                $errors[$fieldName] = [];
                foreach ($field->getErrors(true) as $error) {
//                    dump($error->getOrigin()->getPropertyPath());exit;
//                    dump($error->getOrigin()->getName());exit;
                    $errors[$error->getOrigin()->getName()][] = $error->getMessage();
                }
            }
        }
        if (empty($errors)) {
            $errors = 'This Form should not contain extra fields.';
        }

        return [
            'error' => [
                'code' => 400,
                'message' => $errors,
            ],
        ];
    }
}
