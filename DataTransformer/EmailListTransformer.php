<?php
namespace TheliaEmailManager\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Translation\TranslatorInterface;
use Thelia\Core\Translation\Translator;
use TheliaEmailManager\TheliaEmailManager;
use TheliaEmailManager\Util\EmailUtil;

class EmailListTransformer implements DataTransformerInterface
{
    /** @var Translator */
    protected $translator;

    /**
     * EmailListTransformer constructor.
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @param  string[] $emails
     * @return string
     */
    public function transform($emails)
    {
        if (is_array($emails)) {
            throw new \InvalidArgumentException('The arguments emails is not an array');
        }

        return implode(',', $emails);
    }

    /**
     * @param  string $emails
     * @return string[]
     */
    public function reverseTransform($emails)
    {
        $emails = str_replace(["\r", "\r"], '', trim($emails));

        if (!empty($emails)) {
            $emails = explode(',', $emails);

            foreach ($emails as $email) {
                if (!EmailUtil::checkEmailStructure($email)) {
                    throw new TransformationFailedException(
                        $this->translator->trans('Invalid email list', [], TheliaEmailManager::DOMAIN_NAME)
                    );
                }
            }

            return $emails;
        }

        return [];
    }
}
