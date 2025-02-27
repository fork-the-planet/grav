<?php

/**
 * @package    Grav\Common\User
 *
 * @copyright  Copyright (c) 2015 - 2025 Trilby Media, LLC. All rights reserved.
 * @license    MIT License; see LICENSE file for details.
 */

namespace Grav\Common\User;

use RuntimeException;

/**
 * Class Authentication
 * @package Grav\Common\User
 */
abstract class Authentication
{
    /**
     * Create password hash from plaintext password.
     *
     * @param string $password Plaintext password.
     *
     * @throws RuntimeException
     * @return string
     */
    public static function create($password): string
    {
        if (!$password) {
            throw new RuntimeException('Password hashing failed: no password provided.');
        }

        $hash = password_hash($password, PASSWORD_DEFAULT);

        if (!$hash) {
            throw new RuntimeException('Password hashing failed: internal error.');
        }

        return $hash;
    }

    /**
     * Verifies that a password matches a hash.
     *
     * @param string $password Plaintext password.
     * @param string $hash     Hash to verify against.
     *
     * @return int              Returns 0 if the check fails, 1 if password matches, 2 if hash needs to be updated.
     */
    public static function verify($password, $hash): int
    {
        // Fail if hash doesn't match
        if (!$password || !$hash || !password_verify($password, $hash)) {
            return 0;
        }

        // Otherwise check if hash needs an update.
        return password_needs_rehash($hash, PASSWORD_DEFAULT) ? 2 : 1;
    }
}
