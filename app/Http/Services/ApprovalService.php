<?php

namespace App\Http\Services;

use App\Enums\ApprovalStatusEnum;
use App\Enums\PositionEnum;
use App\Enums\RoleEnum;
use App\Utils\Util;
use Exception;

class dataService
{
    public function formattedData($data, $loggedId)
    {
        $this->getAccessData($data, $loggedId);
        $data->start_date = Util::formatDate($data->start_date);
        $data->end_date = Util::formatDate($data->end_date);
    }

    public function getAccessData($data, $loggedId)
    {
        $data->isSupervisor = false;
        $data->isManager = false;

        $supervisorId = $data->supervisor_id;
        $managerId = $data->manager_id;

        if ($loggedId == $supervisorId) {
            $data->isSupervisor = true;
        } else if ($loggedId == $managerId) {
            $data->isManager = true;
        }
    }

    public function approval($data, $loggedUser, $role, $position, $status, $updatedStatus)
    {
        try {
            if ($loggedUser->role->name = $role && $loggedUser->position == $position && $loggedUser->id = $data->supervisor_id) {
                if ($data->status != $status) {
                    throw new Exception("This leave application has been approved or rejected.");
                }
                $data->status = $updatedStatus;
                $data->save();
                return true;
            } else {
                throw new Exception('error', 'You are not allowed to approve this leave application.');
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function reject($loggedRole, $loggedPosition, $data, $request)
    {
        try {
            if ($loggedRole == RoleEnum::MANAGER && $loggedPosition == PositionEnum::MANAGER) {
                $status = ApprovalStatusEnum::MANAGER_PENDING;
                $newStatus = ApprovalStatusEnum::MANAGER_REJECTED;
                $data->manager_reject_reasons = $request->manager_reject_reasons;
            } else if ($loggedRole == RoleEnum::STAFF && $loggedPosition == PositionEnum::SENIOR) {
                $status = ApprovalStatusEnum::SUPERVISOR_PENDING;
                $newStatus = ApprovalStatusEnum::SUPERVISOR_REJECTED;
                $data->supervisor_reject_reasons = $request->supervisor_reject_reasons;
            } else {
                throw new Exception("You are not allowed to reject this leave application.");
            }

            if ($data->status != $status) {
                throw new Exception("This leave application has been approved or rejected.");
            }
            $data->status = $newStatus;
            $data->save();
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function unreject($loggedRole, $loggedPosition, $data)
    {
        try {
            if ($loggedRole == RoleEnum::MANAGER && $loggedPosition == PositionEnum::MANAGER) {
                $status = ApprovalStatusEnum::MANAGER_REJECTED;
                $newStatus = ApprovalStatusEnum::MANAGER_PENDING;
                $data->manager_reject_reasons = null;
            } else if ($loggedRole == RoleEnum::STAFF && $loggedPosition == PositionEnum::SENIOR) {
                $status = ApprovalStatusEnum::SUPERVISOR_REJECTED;
                $newStatus = ApprovalStatusEnum::SUPERVISOR_PENDING;
                $data->supervisor_reject_reasons = null;
            } else {
                throw new Exception("You are not allowed to reject this leave application.");
            }

            if ($data->status != $status) {
                throw new Exception("This leave application has been approved or rejected.");
            }
            $data->status = $newStatus;
            $data->save();
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function checkAccess($data, $loggedId)
    {
        try {
            if ($loggedId == $data->manager_id) {
                return true;
            } else if($loggedId == $data->supervisor_id) {
                return true;
            }
            throw new Exception("You don't have access to this leave application.");
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
}