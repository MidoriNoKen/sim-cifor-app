<?php

namespace App\Repositories;

use App\Models\TravelAuthorisation;
use App\Interfaces\TravelAuthorisationInterface;

class TravelAuthorisationRepository implements TravelAuthorisationInterface
{
    public function getAll()
    {
        return TravelAuthorisation::all();
    }

    public function getAllWithPagination($page, $perPage)
    {
        return TravelAuthorisation::with('role')->paginate($perPage, ['*'], 'page', $page);
    }

    public function getById($id)
    {
        return TravelAuthorisation::find($id);
    }

    public function getByIdWithAccomodation($id)
    {
        return TravelAuthorisation::with('accomodationDetails')->find($id);
    }

    public function create(array $data)
    {
        return TravelAuthorisation::create($data);
    }

    public function update($id, array $data)
    {
        $user = TravelAuthorisation::find($id);
        $user->update($data);
        return $user;
    }

    public function delete($id)
    {
        return TravelAuthorisation::destroy($id);
    }
}
