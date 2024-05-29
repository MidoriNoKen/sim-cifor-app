<script setup>
import { Inertia } from "@inertiajs/inertia";
import { router } from "@inertiajs/vue3";
import { defineProps } from "vue";
const props = defineProps(["travelAuthorisation"]);

const showTravelAuthorisation = (travelAuthorisation) => {
    router.get(
        `/travel-authorisations/${travelAuthorisation.id}`,
        travelAuthorisation
    );
};

const approve = (travelAuthorisation) => {
    Inertia.post(`/travel-authorisations/${travelAuthorisation.id}/approve`);
};

const disapprove = (travelAuthorisation) => {
    Inertia.post(`/travel-authorisations/${travelAuthorisation.id}/disapprove`);
};

const reject = (travelAuthorisation) => {
    router.get(`/travel-authorisations/${travelAuthorisation.id}/reject`);
};

const unreject = (travelAuthorisation) => {
    Inertia.post(`/travel-authorisations/${travelAuthorisation.id}/unreject`);
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
        <span v-if="travelAuthorisation.status === 'Need officer Approval'">
            <button
                @click="approve(travelAuthorisation)"
                class="btn btn-success hover-background btn-sm m-1"
                style="color: white"
            >
                Approve
            </button>
            <button
                @click="reject(travelAuthorisation)"
                class="btn btn-warning hover-background btn-sm m-1"
                style="color: white"
            >
                Reject
            </button>
        </span>
        <span v-else-if="travelAuthorisation.status === 'Need HR Approval'">
            <button
                @click="disapprove(travelAuthorisation)"
                class="btn btn-danger hover-background btn-sm m-1"
                style="color: white"
            >
                Disapprove
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
        </span>
        <span v-else-if="travelAuthorisation.status === 'Rejected by Finance'">
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
                Rejected by Finance
            </button>
        </span>
        <span v-else-if="travelAuthorisation.status === 'Rejected by HR'">
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
        <span v-else-if="travelAuthorisation.status === 'Rejected by officer'">
            <button
                @click="unreject(travelAuthorisation)"
                class="btn btn-danger hover-background btn-sm m-1"
                style="color: white"
            >
                Unreject
            </button>
        </span>
    </div>
    <div v-else-if="travelAuthorisation.isManager">
        <span v-if="travelAuthorisation.status === 'Need officer Approval'">
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
        <span v-else-if="travelAuthorisation.status === 'Need HR Approval'">
            <button
                @click="approve(travelAuthorisation)"
                class="btn btn-success hover-background btn-sm m-1"
                style="color: white"
            >
                Approve
            </button>
            <button
                @click="reject(travelAuthorisation)"
                class="btn btn-success hover-background btn-sm m-1"
                style="color: white"
            >
                Reject
            </button>
        </span>
        <span v-if="travelAuthorisation.status === 'Need Finance Approval'">
            <button
                @click="disapprove(travelAuthorisation)"
                class="btn btn-danger hover-background btn-sm m-1"
                style="color: white"
            >
                Disapprove
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
        </span>
        <span v-else-if="travelAuthorisation.status === 'Rejected by officer'">
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
        <span v-else-if="travelAuthorisation.status === 'Rejected by HR'">
            <button
                @click="unreject(travelAuthorisation)"
                class="btn btn-danger hover-background btn-sm m-1"
                style="color: white"
            >
                Unreject
            </button>
        </span>
        <span v-else-if="travelAuthorisation.status === 'Rejected by Finance'">
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
                Rejected by Finance
            </button>
        </span>
    </div>
    <div v-else-if="travelAuthorisation.isFinance">
        <span v-if="travelAuthorisation.status === 'Need officer Approval'">
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
        <span v-else-if="travelAuthorisation.status === 'Need HR Approval'">
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
                Need HR Approval
            </button>
        </span>
        <span v-if="travelAuthorisation.status === 'Need Finance Approval'">
            <button
                @click="approve(travelAuthorisation)"
                class="btn btn-success hover-background btn-sm m-1"
                style="color: white"
            >
                Approve
            </button>
            <button
                @click="reject(travelAuthorisation)"
                class="btn btn-success hover-background btn-sm m-1"
                style="color: white"
            >
                Reject
            </button>
        </span>
        <span v-else-if="travelAuthorisation.status === 'Approved'">
            <button
                @click="disapprove(travelAuthorisation)"
                class="btn btn-danger hover-background btn-sm m-1"
                style="color: white"
            >
                Disapprove
            </button>
        </span>
        <span v-else-if="travelAuthorisation.status === 'Rejected by officer'">
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
        <span v-else-if="travelAuthorisation.status === 'Rejected by HR'">
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
        <span v-else-if="travelAuthorisation.status === 'Rejected by Finance'">
            <button
                @click="unreject(travelAuthorisation)"
                class="btn btn-danger hover-background btn-sm m-1"
                style="color: white"
            >
                Unreject
            </button>
        </span>
    </div>
</template>
