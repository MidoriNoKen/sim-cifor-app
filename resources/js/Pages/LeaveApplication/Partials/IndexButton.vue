<script setup>
import { Inertia } from "@inertiajs/inertia";
import { router } from "@inertiajs/vue3";
import { defineProps } from "vue";
const props = defineProps(["leaveApplication"]);

const showLeaveApplication = (leaveApplication) => {
    router.get(`/leave-applications/${leaveApplication.id}`, leaveApplication);
};

const approve = (leaveApplication) => {
    Inertia.post(`/leave-applications/${leaveApplication.id}/approve`);
};

const disapprove = (leaveApplication) => {
    Inertia.post(`/leave-applications/${leaveApplication.id}/disapprove`);
};

const reject = (leaveApplication) => {
    router.get(`/leave-applications/${leaveApplication.id}/reject`);
};

const unreject = (leaveApplication) => {
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
        <span v-if="leaveApplication.status === 'Need officer Approval'">
            <button
                @click="approve(leaveApplication)"
                class="btn btn-success hover-background btn-sm m-1"
                style="color: white"
            >
                Approve
            </button>
            <button
                @click="reject(leaveApplication)"
                class="btn btn-warning hover-background btn-sm m-1"
                style="color: white"
            >
                Reject
            </button>
        </span>
        <span v-else-if="leaveApplication.status === 'Need HR Approval'">
            <button
                @click="disapprove(leaveApplication)"
                class="btn btn-danger hover-background btn-sm m-1"
                style="color: white"
            >
                Disapprove
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
        </span>
        <span v-else-if="leaveApplication.status === 'Rejected by HR'">
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
                Rejected by HR
            </button>
        </span>
        <span v-else-if="leaveApplication.status === 'Rejected by officer'">
            <button
                @click="unreject(leaveApplication)"
                class="btn btn-danger hover-background btn-sm m-1"
                style="color: white"
            >
                Unreject
            </button>
        </span>
    </div>
    <div v-else-if="leaveApplication.isManager">
        <span v-if="leaveApplication.status === 'Need officer Approval'">
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
                Need officer Approval
            </button>
        </span>
        <span v-else-if="leaveApplication.status === 'Need HR Approval'">
            <button
                @click="approve(leaveApplication)"
                class="btn btn-success hover-background btn-sm m-1"
                style="color: white"
            >
                Approve
            </button>
            <button
                @click="reject(leaveApplication)"
                class="btn btn-success hover-background btn-sm m-1"
                style="color: white"
            >
                Reject
            </button>
        </span>
        <span v-else-if="leaveApplication.status === 'Approved'">
            <button
                @click="disapprove(leaveApplication)"
                class="btn btn-danger hover-background btn-sm m-1"
                style="color: white"
            >
                Disapprove
            </button>
        </span>
        <span v-else-if="leaveApplication.status === 'Rejected by officer'">
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
                Rejected by officer
            </button>
        </span>
        <span v-else-if="leaveApplication.status === 'Rejected by HR'">
            <button
                @click="unreject(leaveApplication)"
                class="btn btn-danger hover-background btn-sm m-1"
                style="color: white"
            >
                Unreject
            </button>
        </span>
    </div>
</template>
