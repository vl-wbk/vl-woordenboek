<?php

declare(strict_types=1);

namespace App\Data\Account;

use Spatie\LaravelData\Data;

/**
 * UserRegistrationData 
 * 
 * This Data Transfer Object (DTO) serves as a structured contract for user registration requests. 
 * it ensures that incoming data from forms or API endpoints is type-safe and consistently formatted before reaching 
 * the application's service or repository layers. 
 * 
 * By extendings Spatie's Laravel Data, this class can be used for automatic validation, request-to-DTO mapping, and resource transformation. 
 * 
 * Maintainer note: All properties are marked as 'readonly' to enforce immutability once the registration payload
 * has been validated and instantiated.
 * 
 * @package App\Data\Account 
 */
final class UserRegistrationData extends Data
{
    /**
     * Initialize the registration DTO
     *
     * @param string      $name         The display of username chosen by the user.
     * @param string      $email        The primary email address for the account (validated as unique).
     * @param string      $password     The raw password from the usr account (to be hashed by the registration action/service).
     * @param string|null $firstname    The legal first name of the user (optional).
     * @param string|null $lastname     The legal last name of the user (optional).
     */
    public function __construct(
        public readonly string $name,
        public readonly string $email,
        public readonly string $password,
        public readonly ?string $firstname = null,
        public readonly ?string $lastname = null,
    ) {}
}
