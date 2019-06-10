<?php

namespace App\Controller;

use App\Entity\Enrollment;

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
     * @param Enrollment $data
     * @return array
     */
    public function __invoke(Enrollment $data): array
    {
        return $data->getLectures()->toArray();
    }
}
