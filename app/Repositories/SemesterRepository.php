<?php

namespace App\Repositories;


use App\Models\Semester;

class SemesterRepository implements SemesterRepositoryInterface
{
    /**
     * Get's a post by it's ID
     *
     * @param int
     * @return collection
     */
    public function get($semester_id)
    {
        return Semester::find($semester_id);
    }

    /**
     * Get's all semesters.
     *
     * @return mixed
     */
    public function all()
    {
        return Semester::all();
    }

    /**
     * Deletes a semester.
     *
     * @param int
     */
    public function delete($semester_id)
    {
        Semester::destroy($semester_id);
    }

    /**
     * Updates a semester.
     *
     * @param int
     * @param array
     */
    // public function update($semester_id, array $semester_data)
    // {
    //     Semester::find($semester_id);update($semester_data);
    // }
}