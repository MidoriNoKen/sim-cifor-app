<?php

namespace App\Interfaces;

interface LeaveApplicationInterface
{
    public function getAll();
    public function getAllWithPagination($page, $perPage);
    public function getById($id);
    public function getByIdWithRelations($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
}