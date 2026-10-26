<?php
namespace App\Core;

class Validator
{
    private array $data;
    private array $errors = [];

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function validate(array $rules): bool
    {
        $this->errors = [];

        foreach ($rules as $field => $fieldRules) {
            $value = trim($this->data[$field] ?? '');
            $fieldLabel = ucwords(str_replace('_', ' ', $field));

            foreach ($fieldRules as $rule) {
                if ($rule === 'required' && ($value === '' || $value === null)) {
                    $this->errors[$field][] = "{$fieldLabel} is required.";
                    break; // stop further checks if required failed
                }

                if ($value === '' || $value === null) {
                    continue; // skip other rules if optional and empty
                }

                if ($rule === 'email' && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $this->errors[$field][] = "{$fieldLabel} must be a valid email address.";
                }

                if ($rule === 'phone') {
                    $cleaned = preg_replace('/[^\d+]/', '', $value);
                    if (strlen($cleaned) < 10 || strlen($cleaned) > 16) {
                        $this->errors[$field][] = "{$fieldLabel} must be a valid phone number (at least 10 digits).";
                    }
                }

                if (str_starts_with($rule, 'min:')) {
                    $min = (int) substr($rule, 4);
                    if (mb_strlen($value) < $min) {
                        $this->errors[$field][] = "{$fieldLabel} must be at least {$min} characters.";
                    }
                }

                if (str_starts_with($rule, 'max:')) {
                    $max = (int) substr($rule, 4);
                    if (mb_strlen($value) > $max) {
                        $this->errors[$field][] = "{$fieldLabel} cannot exceed {$max} characters.";
                    }
                }

                if (str_starts_with($rule, 'in:')) {
                    $allowed = explode(',', substr($rule, 3));
                    if (!in_array($value, $allowed)) {
                        $this->errors[$field][] = "Selected {$fieldLabel} is invalid.";
                    }
                }

                if ($rule === 'date') {
                    $d = \DateTime::createFromFormat('Y-m-d', $value);
                    if (!$d || $d->format('Y-m-d') !== $value) {
                        $this->errors[$field][] = "{$fieldLabel} must be a valid date (YYYY-MM-DD).";
                    }
                }
            }
        }

        return empty($this->errors);
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function getFirstError(string $field): ?string
    {
        return $this->errors[$field][0] ?? null;
    }

    public function hasErrors(): bool
    {
        return !empty($this->errors);
    }
}
