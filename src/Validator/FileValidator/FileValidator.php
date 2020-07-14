<?php

namespace App\Validator\FileValidator;

use App\Exception\InvalidFileException;

/**
 * Class FileValidator
 * @package App\Validator\FileValidator
 */
class FileValidator
{
    /**
     * @var string
     */
    private $file;

    /**
     * @param string $file
     *
     * @throws InvalidFileException
     */
    public function validateFile(string $file)
    {
        $this->file = $file;

        if (filter_var($this->file, FILTER_VALIDATE_URL) === true) {
            $this->validateFileUrl();
        } else {
            $this->validateFileLocal();
        }
    }

    /**
     * @throws InvalidFileException
     */
    private function validateFileUrl(): void
    {
        $headers = get_headers($this->file, 1);
        if (stripos($headers[0], '200 OK') === false || $headers["Content-Type"] !== 'text/csv') {
            throw new InvalidFileException();
        }
    }

    /**
     * @throws InvalidFileException
     */
    private function validateFileLocal(): void
    {
        if (!file_exists($this->file)) {
            throw new InvalidFileException();
        }

        if (substr($this->file, -4) !== '.csv') {
            throw new InvalidFileException();
        }
    }
}
