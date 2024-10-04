<?php

namespace App\Core;

class Validator
{
    /**
     * Array for errors catch.
     *
     * @var array
     */
    private static array $errors = [];

    /**
     * Main method for checking attributes
     *
     * @param array $data
     * @param array $rules
     *
     * @return array|true
     */
    public static function check(array $data, array $rules): array|true
    {
        self::$errors = [];

        foreach ($rules as $field => $fieldRules) {
            if (!is_array($fieldRules)) {
                self::$errors[$field][] = 'Invalid rules format for this field';
                continue;
            }

            foreach ($fieldRules as $rule => $ruleValue) {
                $methodName = 'validate' . ucfirst($rule);
                if (method_exists(__CLASS__, $methodName)) {
                    self::$methodName($field, $data, $ruleValue);
                } else {
                    self::$errors[$field][] = "Validation rule '$rule' does not exist";
                }
            }
        }

        if (self::$errors) {
            return self::errors();
        }

        return true;
    }

    /**
     * Getter for errors
     *
     * @return array
     */
    public static function errors(): array
    {
        return self::$errors;
    }

    /**
     * Validation: Is it required
     *
     * @param $field
     * @param $data
     * @param $value
     *
     * @return void
     */
    private static function validateRequired($field, $data, $value): void
    {
        if ($value && empty($data[$field])) {
            self::$errors[$field][] = 'This field is required';
        }
    }

    /**
     * Validation: Is it an email
     *
     * @param $field
     * @param $data
     * @param $value
     *
     * @return void
     */
    private static function validateEmail($field, $data, $value): void
    {
        if ($value && !filter_var($data[$field], FILTER_VALIDATE_EMAIL)) {
            self::$errors[$field][] = 'Invalid email format';
        }
    }

    /**
     * Validation: Minimum size
     *
     * @param $field
     * @param $data
     * @param $length
     *
     * @return void
     */
    private static function validateMin($field, $data, $length): void
    {
        if (strlen($data[$field]) < $length) {
            self::$errors[$field][] = "Minimum length is $length characters";
        }
    }

    /**
     * Validation: Maximum value
     *
     * @param $field
     * @param $data
     * @param $length
     *
     * @return void
     */
    private static function validateMax($field, $data, $length): void
    {
        if (strlen($data[$field]) > $length) {
            self::$errors[$field][] = "Maximum length is $length characters";
        }
    }

    /**
     * Validation: Is it string
     *
     * @param $field
     * @param $data
     * @param $value
     *
     * @return void
     */
    private static function validateString($field, $data, $value): void
    {
        if ($value && !is_string($data[$field])) {
            self::$errors[$field][] = 'This field must be a string';
        }
    }
}
