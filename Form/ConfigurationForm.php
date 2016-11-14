<?php
/*************************************************************************************/
/*      Copyright (C) 2016 Gilles Bourgeat <gilles.bourgeat@gmail.com>               */
/*                                                                                   */
/*      This file is part of TheliaEmailManager module.                              */
/*                                                                                   */
/*      The module TheliaEmailManager can not be copied and/or distributed without   */
/*      permission of Gilles Bourgeat.                                               */
/*************************************************************************************/

namespace TheliaEmailManager\Form;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use TheliaEmailManager\DataTransformer\EmailListTransformer;
use TheliaEmailManager\TheliaEmailManager;

/**
 * @author Gilles Bourgeat <gilles.bourgeat@gmail.com>
 */
class ConfigurationForm extends BaseForm
{
    /**
     * @return string
     */
    public function getName()
    {
        return Forms::CONFIGURATION;
    }

    public function buildForm()
    {
        $this->formBuilder
            ->add(TheliaEmailManager::CONFIG_DISABLE_SENDING, ChoiceType::class, [
                'choices' => [
                    1 => $this->trans('Yes'),
                    0 => $this->trans('No')
                ],
                'required'    => true,
                'label' => $this->trans('Disable sending of all emails'),
                'label_attr'  => ['for' => TheliaEmailManager::CONFIG_DISABLE_SENDING]
            ])
            ->add(TheliaEmailManager::CONFIG_ENABLE_HISTORY, ChoiceType::class, [
                'choices' => [
                    1 => $this->trans('Yes'),
                    0 => $this->trans('No')
                ],
                'required'    => true,
                'label' => $this->trans('Enable the history of all emails'),
                'label_attr'  => ['for' => TheliaEmailManager::CONFIG_ENABLE_HISTORY]
            ])
            ->add(TheliaEmailManager::CONFIG_REDIRECT_ALL_TO, TextareaType::class, [
                'required'    => false,
                'label' => $this->trans('Disable sending of all emails'),
                'label_attr'  => [
                    'for' => TheliaEmailManager::CONFIG_REDIRECT_ALL_TO,
                    'help' => $this->trans('List of emails separated by commas')
                ],
                'attr' => [
                    'rows' => 4,
                    'placeholder' => $this->trans('email1@my-domain.tld, email2@my-domain.tld')
                ]
            ]);

        $this->formBuilder->get(TheliaEmailManager::CONFIG_REDIRECT_ALL_TO)
            ->addModelTransformer(new EmailListTransformer());
    }
}
