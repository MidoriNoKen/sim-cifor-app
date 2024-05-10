<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, router, usePage } from "@inertiajs/vue3";

const leaveApplications = usePage().props.leaveApplications;
const loggedRole = usePage().props.loggedRole;

const showLeaveApplication = (leaveApplication) => {
    router.get(`/leave-applications/${leaveApplication.id}`, leaveApplication);
};

const createLeaveApplication = () => {
    router.get(`/leave-applications/create`);
};

const approveBySupervisor = (leaveApplication) => {
    router.post(`/leave-applications/${leaveApplication.id}/approve-by-supervisor`, leaveApplication);
};

const approveByManager = (leaveApplication) => {
    router.post(`/leave-applications/${leaveApplication.id}/approve-by-manager`, leaveApplication);
};

</script>

<template>

    <Head title="Show Leave Applications" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Leave Application List
            </h2>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <table
                        class="table align-middle text-center table-responsive max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                        <thead>
                            <tr>
                                <th>Applicant</th>
                                <th>Leave Type</th>
                                <th>Status</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Day Accumulation</th>
                                <th>Supervisor</th>
                                <th>Manager</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="leaveApplication in leaveApplications" :key="leaveApplication.id">
                                <td>
                                    {{ leaveApplication.applicant }}
                                </td>
                                <td>{{ leaveApplication.leave_type }}</td>
                                <td>{{ leaveApplication.status }}</td>
                                <td>{{ leaveApplication.start_date }}</td>
                                <td>{{ leaveApplication.end_date }}</td>
                                <td>{{ leaveApplication.day_accumulation }}</td>
                                <td>{{ leaveApplication.supervisor }}</td>
                                <td>{{ leaveApplication.manager }}</td>
                                <td>
                                    <button @click="
                                        showLeaveApplication(
                                            leaveApplication
                                        )
                                        " class="btn btn-primary hover-background btn-sm m-1" style="color: white">
                                        Show
                                    </button>
                                    <button @click="
                                        approveBySupervisor(
                                            leaveApplication
                                        )
                                        " v-if="leaveApplication.isSupervisor"
                                        class="btn btn-success hover-background btn-sm m-1" style="color: white">
                                        Approve
                                    </button>
                                    <button @click="
                                        approveByManager(
                                            leaveApplication
                                        )
                                        " v-else-if="leaveApplication.isManager"
                                        class="btn btn-success hover-background btn-sm m-1" style="color: white">
                                        Approve
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="text-center" v-if="loggedRole === 'Staff'">
                        <button @click="createLeaveApplication()" class="btn btn-primary hover-background btn-sm m-1"
                            style="color: white">
                            Create
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
