<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, router, usePage } from "@inertiajs/vue3";
import IndexButton from "./Partials/IndexButton.vue";

const leaveApplications = usePage().props.leaveApplications;
const loggedRole = usePage().props.loggedRole;
const user = usePage().props.auth.user;

const createLeaveApplication = () => {
    router.get(`/leave-applications/create`);
};
</script>
<template>
    <Head title="Leave Application" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Leave Application List
            </h2>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <div class="table-responsive">
                        <table class="table align-middle text-center">
                            <thead>
                                <tr>
                                    <th
                                        v-if="
                                            user.position !== 'Junior' &&
                                            loggedRole !== 'Staff'
                                        "
                                    >
                                        Applicant
                                    </th>
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
                                <tr
                                    v-for="leaveApplication in leaveApplications"
                                    :key="leaveApplication.id"
                                >
                                    <td
                                        v-if="
                                            user.position !== 'Junior' &&
                                            loggedRole !== 'Staff'
                                        "
                                    >
                                        {{ leaveApplication.applicant }}
                                    </td>
                                    <td>{{ leaveApplication.leave_type }}</td>
                                    <td>{{ leaveApplication.status }}</td>
                                    <td>{{ leaveApplication.start_date }}</td>
                                    <td>{{ leaveApplication.end_date }}</td>
                                    <td>
                                        {{ leaveApplication.day_accumulation }}
                                    </td>
                                    <td>
                                        {{ leaveApplication.supervisor.name }}
                                    </td>
                                    <td>{{ leaveApplication.manager.name }}</td>
                                    <td>
                                        <IndexButton
                                            :leaveApplication="leaveApplication"
                                        />
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="text-center" v-if="loggedRole === 'Staff'">
                            <button
                                @click="createLeaveApplication()"
                                class="btn btn-primary hover-background btn-sm m-1"
                                style="color: white"
                            >
                                Create
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
