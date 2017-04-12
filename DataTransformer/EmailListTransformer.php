<?php
/*
 * This file is part of the Symfony package.
 *
 * (c) Gilles Bourgeat <gilles.bourgeat@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TheliaEmailManager\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use TheliaEmailManager\Util\EmailUtil;

/**
 * @author Gilles Bourgeat <gilles.bourgeat@gmail.com>
 */
class EmailListTransformer implements DataTransformerInterface
{
    /**
     * @param  string[] $emails
     * @return string
     */
    public function transform($emails)
    {
        if ($emails === null) {
            return '';
        }

        if (!is_array($emails)) {
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
        $return = [];

        if (!empty($emails)) {
            $emails = explode(',', $emails);

            foreach ($emails as $email) {
                $email = rtrim($email);

                if (!empty($email) && EmailUtil::checkEmailStructure($email)) {
                    $return[] = $email;
                }
            }
        }

        return $return;
    }
}
