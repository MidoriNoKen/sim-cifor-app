<script setup>
import { router } from "@inertiajs/vue3";
import { defineProps } from "vue";
const props = defineProps(["travelAuthorisation"]);

const showTravelAuthorisation = (travelAuthorisation) => {
    router.get(
        `/travel-authorisations/${travelAuthorisation.id}`,
        travelAuthorisation
    );
};

const approveBySupervisor = (travelAuthorisation) => {
    router.post(
        `/travel-authorisations/${travelAuthorisation.id}/approve-by-supervisor`
    );
};

const disapproveBySupervisor = (travelAuthorisation) => {
    router.post(
        `/travel-authorisations/${travelAuthorisation.id}/disapprove-by-supervisor`
    );
};

const rejectBySupervisor = (travelAuthorisation) => {
    router.get(`/travel-authorisations/${travelAuthorisation.id}/reject`);
};

const unrejectBySupervisor = (travelAuthorisation) => {
    router.post(`/travel-authorisations/${travelAuthorisation.id}/unreject`);
};

const approveByManager = (travelAuthorisation) => {
    router.post(
        `/travel-authorisations/${travelAuthorisation.id}/approve-by-manager`
    );
};

const disapproveByManager = (travelAuthorisation) => {
    router.post(
        `/travel-authorisations/${travelAuthorisation.id}/disapprove-by-manager`
    );
};

const rejectByManager = (travelAuthorisation) => {
    router.get(`/travel-authorisations/${travelAuthorisation.id}/reject`);
};

const unrejectByManager = (travelAuthorisation) => {
    router.post(`/travel-authorisations/${travelAuthorisation.id}/unreject`);
};
</script>

<template>
    <button
        @click="showTravelAuthorisation(travelAuthorisation)"
        class="btn btn-primary hover-background btn-sm m-1"
        style="color: white"
    >
        Show
    </button>
    <div v-if="travelAuthorisation.isSupervisor">
        <span v-if="travelAuthorisation.status === 'Need Supervisor Approval'">
            <button
                @click="approveBySupervisor(travelAuthorisation)"
                class="btn btn-success hover-background btn-sm m-1"
                style="color: white"
            >
                Approve
            </button>
            <button
                @click="rejectBySupervisor(travelAuthorisation)"
                class="btn btn-warning hover-background btn-sm m-1"
                style="color: white"
            >
                Reject
            </button>
        </span>
        <span
            v-else-if="travelAuthorisation.status === 'Need Manager Approval'"
        >
            <button
                @click="disapproveBySupervisor(travelAuthorisation)"
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
        <span v-else-if="travelAuthorisation.status === 'Approved'">
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
        <span v-else-if="travelAuthorisation.status === 'Rejected by Manager'">
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
        <span
            v-else-if="travelAuthorisation.status === 'Rejected by Supervisor'"
        >
            <button
                class="btn btn-danger hover-background btn-sm m-1"
                style="color: white"
                disabled
            >
                Rejected
            </button>
            <button
                @click="unrejectBySupervisor(travelAuthorisation)"
                class="btn btn-danger hover-background btn-sm m-1"
                style="color: white"
            >
                Unreject
            </button>
        </span>
    </div>
    <div v-else-if="travelAuthorisation.isManager">
        <span v-if="travelAuthorisation.status === 'Need Supervisor Approval'">
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
        <span
            v-else-if="travelAuthorisation.status === 'Need Manager Approval'"
        >
            <button
                @click="approveByManager(travelAuthorisation)"
                class="btn btn-success hover-background btn-sm m-1"
                style="color: white"
            >
                Approve
            </button>
            <button
                @click="rejectByManager(travelAuthorisation)"
                class="btn btn-success hover-background btn-sm m-1"
                style="color: white"
            >
                Reject
            </button>
        </span>
        <span v-else-if="travelAuthorisation.status === 'Approved'">
            <button
                @click="disapproveByManager(travelAuthorisation)"
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
        <span
            v-else-if="travelAuthorisation.status === 'Rejected by Supervisor'"
        >
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
        <span v-else-if="travelAuthorisation.status === 'Rejected by Manager'">
            <button
                class="btn btn-danger hover-background btn-sm m-1"
                style="color: white"
                disabled
            >
                Rejected
            </button>
            <button
                @click="unrejectByManager(travelAuthorisation)"
                class="btn btn-danger hover-background btn-sm m-1"
                style="color: white"
            >
                Unreject
            </button>
        </span>
    </div>
</template>
