<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @license       https://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace Authentication\PasswordHasher;

use Cake\Core\Configure;
use Cake\Error\Debugger;
use Cake\Utility\Security;
use RuntimeException;

/**
 * Password hashing class that use weak hashing algorithms. This class is
 * intended only to be used with legacy databases where passwords have
 * not been migrated to a stronger algorithm yet.
 */
class LegacyPasswordHasher extends AbstractPasswordHasher
{

    /**
     * Default config for this object.
     *
     * @var array
     */
    protected $_defaultConfig = [
        'hashType' => null,
    ];

    /**
     * {@inheritDoc}
     */
    public function __construct(array $config = [])
    {
        parent::__construct($config);
        if (Configure::read('debug')) {
            Debugger::checkSecurityKeys();
        }
        if (!class_exists(Security::class)) {
            throw new RuntimeException('You must install the cakephp/utility dependency to use this password hasher');
        };
    }

    /**
     * Generates password hash.
     *
     * @param string $password Plain text password to hash.
     * @return string Password hash
     */
    public function hash($password)
    {
        return Security::hash($password, $this->_config['hashType'], true);
    }

    /**
     * Check hash. Generate hash for user provided password and check against existing hash.
     *
     * @param string $password Plain text password to hash.
     * @param string $hashedPassword Existing hashed password.
     * @return bool True if hashes match else false.
     */
    public function check($password, $hashedPassword)
    {
        return $hashedPassword === $this->hash($password);
    }
}
