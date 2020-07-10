<?php

namespace App\DTO;

/**
 * Class ImportResults
 */
class ImportResults
{
    /**
     * @var integer
     */
    private $errors;

    /**
     * @var integer
     */
    private $created;

    /**
     * @var integer
     */
    private $updated;

    /**
     * ImportResults constructor.
     * @param int $errors
     * @param int $created
     * @param int $updated
     */
    public function __construct(
        int $errors,
        int $created,
        int $updated
    ) {
        $this->errors = $errors;
        $this->created = $created;
        $this->updated = $updated;
    }

    /**
     * @return int
     */
    public function errors(): int
    {
        return $this->errors;
    }

    /**
     * @param int $errors
     */
    public function setErrors(int $errors): void
    {
        $this->errors = $errors;
    }

    /**
     * @return int
     */
    public function created(): int
    {
        return $this->created;
    }

    /**
     * @param int $created
     */
    public function setCreated(int $created): void
    {
        $this->created = $created;
    }

    /**
     * @return int
     */
    public function updated(): int
    {
        return $this->updated;
    }

    /**
     * @param int $updated
     */
    public function setUpdated(int $updated): void
    {
        $this->updated = $updated;
    }
}
