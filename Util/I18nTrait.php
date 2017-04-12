<?php
/*
 * This file is part of the Symfony package.
 *
 * (c) Gilles Bourgeat <gilles.bourgeat@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TheliaEmailManager\Util;

use Propel\Runtime\ActiveQuery\ModelCriteria;
use Thelia\Core\Translation\Translator;
use Thelia\Model\Tools\ModelCriteriaTools;
use TheliaEmailManager\TheliaEmailManager;

/**
 * @author Gilles Bourgeat <gilles.bourgeat@gmail.com>
 */
trait I18nTrait
{
    /**
     * Translates the given message.
     *
     * @param string      $id         The message id (may also be an object that can be cast to string)
     * @param array       $parameters An array of parameters for the message
     * @param string|null $domain     The domain for the message or null to use the default
     *
     * @throws \InvalidArgumentException If the locale contains invalid characters
     *
     * @return string The translated string
     */
    public static function trans($id, array $parameters = [], $domain = null)
    {
        return Translator::getInstance()->trans(
            $id,
            $parameters,
            $domain === null ? TheliaEmailManager::DOMAIN_NAME : $domain
        );
    }

    /**
     * @param ModelCriteria $search
     * @param string $locale
     * @param array $columns
     */
    public static function buildCriteriaI18n($search, $locale, array $columns)
    {
        ModelCriteriaTools::getI18n(
            true,
            null,
            $search,
            $locale,
            $columns,
            null,
            'ID',
            true
        );
    }
}
