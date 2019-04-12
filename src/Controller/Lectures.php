<?php

namespace App\Controller;

use App\Entity\Enrollment;
use App\Repository\EnrollmentRepository;

/**
 * Class Lectures
 *
 * Retrieves the collection of Lecture resources, that belongs to given Enrollment
 *
 * @package App\Controller
 */
class Lectures
{
    /**
     * @var EnrollmentRepository
     */
    private $enrollmentRepository;

    /**
     * Lectures constructor.
     *
     * @param EnrollmentRepository $enrollmentRepository
     */
    public function __construct(EnrollmentRepository $enrollmentRepository)
    {
        $this->enrollmentRepository = $enrollmentRepository;
    }

    /**
     * @param Enrollment $data
     * @return array
     */
    public function __invoke(Enrollment $data): array
    {
        return $data->getLectures()->toArray();
    }
}
