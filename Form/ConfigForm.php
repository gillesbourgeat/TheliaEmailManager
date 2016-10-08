<?php

namespace TheliaEmailManager\Form\Company;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use TheliaEmailManager\Form\BaseForm;
use TheliaEmailManager\TheliaEmailManager;
use TheliaEmailManager\Util\EmailUtil;

/**
 * @author Gilles Bourgeat <gilles.bourgeat@gmail.com>
 */
class CompanyCreateForm extends BaseForm
{
    public function buildForm()
    {
        $this->formBuilder
            ->add(TheliaEmailManager::CONFIG_DISABLE_SEND, ChoiceType::class, [
                'choices' => [
                    1 => $this->trans('Yes'),
                    0 => $this->trans('No')
                ],
                'required'    => true,
                'label' => $this->trans('Disable sending of all emails'),
                'label_attr'  => ['for' => TheliaEmailManager::CONFIG_DISABLE_SEND]
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
                'constraints' => [
                    new Callback([
                        'methods' => [[$this, 'checkListOfEmail']]
                    ]),
                ],
                'required'    => true,
                'label' => $this->trans('Disable sending of all emails'),
                'label_attr'  => [
                    'for' => TheliaEmailManager::CONFIG_REDIRECT_ALL_TO,
                    'help' => $this->trans('List of emails separated by commas')
                ]
            ]);
    }

    public function checkListOfEmail($value, ExecutionContextInterface $context)
    {
        $value = trim($value);

        if (!empty($value)) {
            $mails = explode(',', $value);

            foreach ($mails as $mail) {
                if (!MailUtil::checkMailStructure($mail)) {
                    $context->addViolation(
                        $this->trans(
                            "Invalid email : %mail.",
                            ['%mail' => $mail]
                        )
                    );

                    break;
                }
            }
        }
    }
}
