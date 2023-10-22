<?php

namespace JonathanRayln\UdemyClone\Models;

use JonathanRayln\UdemyClone\Database\DbModel;

class User extends DbModel
{
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_DELETED = 2;

    const ROLE_GLOBAL_ADMIN = 1;
    const ROLE_USER = 2;

    public string $first_name = '';
    public string $last_name = '';
    public string $email = '';
    public int $status = self::STATUS_INACTIVE;
    public int $role_id = self::ROLE_USER;
    public string $password = '';
    public string $confirm_password = '';
    public int|string|null $terms = null;

    /**
     * @inheritDoc
     */
    public static function tableName(): string
    {
        return 'users';
    }

    /**
     * @inheritDoc
     */
    public static function primaryKey(): string
    {
        return 'id';
    }

    /**
     * @inheritDoc
     */
    public function attributes(): array
    {
        return ['first_name', 'last_name', 'email', 'status', 'role_id', 'password'];
    }

    /**
     * @inheritDoc
     */
    public function rules(): array
    {
        return [
            'first_name'       => [self::RULE_REQUIRED],
            'last_name'        => [self::RULE_REQUIRED],
            'email'            => [self::RULE_REQUIRED, self::RULE_EMAIL, [self::RULE_UNIQUE, 'class' => self::class]],
            'password'         => [self::RULE_REQUIRED, [self::RULE_MIN, 'min' => 3], [self::RULE_MAX, 'max' => 5]],
            'confirm_password' => [self::RULE_REQUIRED, [self::RULE_MATCH, 'match' => 'password']],
            'terms'            => [self::RULE_REQUIRED]
        ];
    }

    /**
     * @inheritDoc
     */
    public function labels(): array
    {
        return [
            'first_name'       => 'First Name',
            'last_name'        => 'Last Name',
            'email'            => 'Email',
            'password'         => 'Password',
            'confirm_password' => 'Confirm Password',
            'terms'            => 'Terms &amp; Conditions',
        ];
    }

    /**
     * @inheritDoc
     *
     * Overrides parent method with status code and encrypted password.
     *
     * @return true
     * @throws \PDOException
     */
    public function save(): bool
    {
        $this->status = self::STATUS_ACTIVE;
        $this->password = password_hash($this->password, PASSWORD_DEFAULT);
        return parent::save();
    }
}