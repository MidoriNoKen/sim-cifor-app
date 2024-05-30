<?php

namespace App\Interfaces;

interface TravelAuthorisationInterface
{
    public function getAll();
    public function getAllWithPagination($page, $perPage);
    public function getById($id);
    public function getByIdWithAccomodation($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
}