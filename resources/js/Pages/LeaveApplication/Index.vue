<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, router, usePage } from "@inertiajs/vue3";
import IndexButton from "./Partials/IndexButton.vue";
import { Bootstrap5Pagination } from "laravel-vue-pagination";
import { ref } from "vue";

const { role, position } = usePage().props.auth.user;
const leaveApplications = ref(usePage().props.leaveApplications);
const currentPage = ref(usePage().props.leaveApplications.current_page);
const lastPage = ref(usePage().props.leaveApplications.last_page);
const perPage = ref(usePage().props.leaveApplications.per_page);

const createLeaveApplication = () => {
    router.get(`/leave-applications/create`);
};

const getLeaveApplication = async (page = 1) => {
    router.get(
        `/leave-applications`,
        { page },
        {
            preserveState: true,
            replace: true,
            onSuccess: (page) => {
                tasks.value = page.props.leaveApplications;
                currentPage.value = page.props.leaveApplications.current_page;
                lastPage.value = page.props.leaveApplications.last_page;
                perPage.value = page.props.leaveApplications.per_page;
            },
        }
    );
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
                                            position !== 'Junior' &&
                                            role.name !== 'Staff'
                                        "
                                    >
                                        Applicant
                                    </th>
                                    <th>Leave Type</th>
                                    <th>Status</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Accumulation</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="leaveApplication in leaveApplications.data"
                                    :key="leaveApplication.id"
                                >
                                    <td
                                        v-if="
                                            position !== 'Junior' &&
                                            role.name !== 'Staff'
                                        "
                                    >
                                        {{ leaveApplication.applicant.name }}
                                    </td>
                                    <td>{{ leaveApplication.leave_type }}</td>
                                    <td>{{ leaveApplication.status }}</td>
                                    <td>{{ leaveApplication.start_date }}</td>
                                    <td>{{ leaveApplication.end_date }}</td>
                                    <td>
                                        {{ leaveApplication.accumulation }}
                                    </td>
                                    <td>
                                        <IndexButton
                                            :leaveApplication="leaveApplication"
                                        />
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <Bootstrap5Pagination
                            :data="leaveApplications"
                            @pagination-change-page="getLeaveApplication"
                        />
                        <div class="text-center">
                            <div
                                class="mt-4 mb-4 fw-semibold"
                                v-if="leaveApplications.data.length == null"
                            >
                                No Data Available
                            </div>
                            <button
                                @click="createLeaveApplication()"
                                class="btn btn-primary hover-background btn-sm m-1"
                                style="color: white"
                                v-if="role.name === 'Staff'"
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
