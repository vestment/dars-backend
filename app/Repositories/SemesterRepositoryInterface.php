<?php
namespace App\Repositories;

interface SemesterRepositoryInterface
{
    /**
     * Get's a semester by it's ID
     *
     * @param int
     */
    public function get($semester_id);

    /**
     * Get's all semesters.
     *
     * @return mixed
     */
    public function all();

    /**
     * Deletes a semester.
     *
     * @param int
     */
    public function delete($semester_id);

    /**
     * Updates a semester.
     *
     * @param int
     * @param array
     */
    public function update($semester_id, array $semester_data);
}