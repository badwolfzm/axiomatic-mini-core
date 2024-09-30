<?php

namespace Core;

class Validator {
    public static function validateInput($input, $requiredFields) {
        foreach ($requiredFields as $field) {
            if (!isset($input[$field]) || empty($input[$field])) {
                throw new \Exception("Validation failed: Missing required field - " . $field);
            }
        }
        return true;
    }
}
