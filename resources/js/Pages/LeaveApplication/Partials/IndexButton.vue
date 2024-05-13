<script setup>
import { Inertia } from "@inertiajs/inertia";
import { router } from "@inertiajs/vue3";
import { defineProps } from "vue";
const props = defineProps(["leaveApplication"]);

const showLeaveApplication = (leaveApplication) => {
    router.get(`/leave-applications/${leaveApplication.id}`, leaveApplication);
};

const approveBySupervisor = (leaveApplication) => {
    Inertia.post(
        `/leave-applications/${leaveApplication.id}/approve-by-supervisor`
    );
};

const disapproveBySupervisor = (leaveApplication) => {
    Inertia.post(
        `/leave-applications/${leaveApplication.id}/disapprove-by-supervisor`
    );
};

const rejectBySupervisor = (leaveApplication) => {
    router.get(`/leave-applications/${leaveApplication.id}/reject`);
};

const unrejectBySupervisor = (leaveApplication) => {
    Inertia.post(`/leave-applications/${leaveApplication.id}/unreject`);
};

const approveByManager = (leaveApplication) => {
    Inertia.post(
        `/leave-applications/${leaveApplication.id}/approve-by-manager`
    );
};

const disapproveByManager = (leaveApplication) => {
    Inertia.post(
        `/leave-applications/${leaveApplication.id}/disapprove-by-manager`
    );
};

const rejectByManager = (leaveApplication) => {
    router.get(`/leave-applications/${leaveApplication.id}/reject`);
};

const unrejectByManager = (leaveApplication) => {
    Inertia.post(`/leave-applications/${leaveApplication.id}/unreject`);
};
</script>

<template>
    <button
        @click="showLeaveApplication(leaveApplication)"
        class="btn btn-primary hover-background btn-sm m-1"
        style="color: white"
    >
        Show
    </button>
    <div v-if="leaveApplication.isSupervisor">
        <span v-if="leaveApplication.status === 'Need Supervisor Approval'">
            <button
                @click="approveBySupervisor(leaveApplication)"
                class="btn btn-success hover-background btn-sm m-1"
                style="color: white"
            >
                Approve
            </button>
            <button
                @click="rejectBySupervisor(leaveApplication)"
                class="btn btn-warning hover-background btn-sm m-1"
                style="color: white"
            >
                Reject
            </button>
        </span>
        <span v-else-if="leaveApplication.status === 'Need Manager Approval'">
            <button
                @click="disapproveBySupervisor(leaveApplication)"
                class="btn btn-danger hover-background btn-sm m-1"
                style="color: white"
            >
                Disapprove
            </button>
            <button
                class="btn btn-sm m-1"
                style="
                    background-color: gray;
                    color: white;
                    opacity: 0.5;
                    cursor: not-allowed;
                "
                disabled
            >
                Reject
            </button>
        </span>
        <span v-else-if="leaveApplication.status === 'Approved'">
            <button
                class="btn btn-sm m-1"
                style="
                    background-color: gray;
                    color: white;
                    opacity: 0.5;
                    cursor: not-allowed;
                "
                disabled
            >
                Approved
            </button>
            <button
                class="btn btn-sm m-1"
                style="
                    background-color: gray;
                    color: white;
                    opacity: 0.5;
                    cursor: not-allowed;
                "
                disabled
            >
                Reject
            </button>
        </span>
        <span v-else-if="leaveApplication.status === 'Rejected by Manager'">
            <button
                class="btn btn-danger hover-background btn-sm m-1"
                style="color: white"
                disabled
            >
                Manager Rejected
            </button>
            <button
                class="btn btn-danger hover-background btn-sm m-1"
                style="color: white"
                disabled
            >
                Manager Rejected
            </button>
        </span>
        <span v-else-if="leaveApplication.status === 'Rejected by Supervisor'">
            <button
                class="btn btn-danger hover-background btn-sm m-1"
                style="color: white"
                disabled
            >
                Rejected
            </button>
            <button
                @click="unrejectBySupervisor(leaveApplication)"
                class="btn btn-danger hover-background btn-sm m-1"
                style="color: white"
            >
                Unreject
            </button>
        </span>
    </div>
    <div v-else-if="leaveApplication.isManager">
        <span v-if="leaveApplication.status === 'Need Supervisor Approval'">
            <button
                class="btn btn-sm m-1"
                style="
                    background-color: gray;
                    color: white;
                    opacity: 0.5;
                    cursor: not-allowed;
                "
                disabled
            >
                Supervisor
            </button>
            <button
                class="btn btn-sm m-1"
                style="
                    background-color: gray;
                    color: white;
                    opacity: 0.5;
                    cursor: not-allowed;
                "
                disabled
            >
                Reject
            </button>
        </span>
        <span v-else-if="leaveApplication.status === 'Need Manager Approval'">
            <button
                @click="approveByManager(leaveApplication)"
                class="btn btn-success hover-background btn-sm m-1"
                style="color: white"
            >
                Approve
            </button>
            <button
                @click="rejectByManager(leaveApplication)"
                class="btn btn-success hover-background btn-sm m-1"
                style="color: white"
            >
                Reject
            </button>
        </span>
        <span v-else-if="leaveApplication.status === 'Approved'">
            <button
                @click="disapproveByManager(leaveApplication)"
                class="btn btn-danger hover-background btn-sm m-1"
                style="color: white"
            >
                Disapprove
            </button>
            <button
                class="btn btn-sm m-1"
                style="
                    background-color: gray;
                    color: white;
                    opacity: 0.5;
                    cursor: not-allowed;
                "
                disabled
            >
                Reject
            </button>
        </span>
        <span v-else-if="leaveApplication.status === 'Rejected by Supervisor'">
            <button
                class="btn btn-danger hover-background btn-sm m-1"
                style="color: white"
                disabled
            >
                Supervisor Rejected
            </button>
            <button
                class="btn btn-danger hover-background btn-sm m-1"
                style="color: white"
            >
                Supervisor Rejected
            </button>
        </span>
        <span v-else-if="leaveApplication.status === 'Rejected by Manager'">
            <button
                class="btn btn-danger hover-background btn-sm m-1"
                style="color: white"
                disabled
            >
                Rejected
            </button>
            <button
                @click="unrejectByManager(leaveApplication)"
                class="btn btn-danger hover-background btn-sm m-1"
                style="color: white"
            >
                Unreject
            </button>
        </span>
    </div>
</template>
