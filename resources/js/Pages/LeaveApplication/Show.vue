<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, router, usePage } from "@inertiajs/vue3";

const leaveApplication = usePage().props.leaveApplication;
const user = usePage().props.user;
const loggedRole = usePage().props.loggedRole;
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
                <div class="p-8 bg-white shadow sm:rounded-lg">
                    <h2
                        class="font-semibold text-xl text-gray-800 leading-tight mb-10"
                    >
                        Applicant Detail
                    </h2>
                    <div class="row m-4">
                        <div class="col-4">Applicant Name</div>
                        <div class="col-4">:</div>
                        <div class="col-4">
                            {{ user.name }}
                        </div>
                    </div>
                    <div class="row m-4">
                        <div class="col-4">Email</div>
                        <div class="col-4">:</div>
                        <div class="col-4">
                            {{ user.email }}
                        </div>
                    </div>
                    <div class="row m-4">
                        <div class="col-4">Role</div>
                        <div class="col-4">:</div>
                        <div class="col-4">
                            {{ loggedRole }}
                        </div>
                    </div>
                    <div class="row m-4">
                        <div class="col-4">officer</div>
                        <div class="col-4">:</div>
                        <div class="col-4">
                            {{ user.officer }}
                        </div>
                    </div>
                    <div class="row m-4">
                        <div class="col-4">HR</div>
                        <div class="col-4">:</div>
                        <div class="col-4">
                            {{ user.HR }}
                        </div>
                    </div>
                    <div class="row m-4">
                        <div class="col-4">Position</div>
                        <div class="col-4">:</div>
                        <div class="col-4">
                            {{ user.position }}
                        </div>
                    </div>
                    <div class="row m-4">
                        <div class="col-4">Born Date</div>
                        <div class="col-4">:</div>
                        <div class="col-4">
                            {{ user.born_date }}
                        </div>
                    </div>
                </div>
                <div class="p-8 bg-white shadow sm:rounded-lg">
                    <h2
                        class="font-semibold text-xl text-gray-800 leading-tight mb-10"
                    >
                        Leave Application Detail
                    </h2>
                    <div class="row m-4">
                        <div class="col-4">Status</div>
                        <div class="col-4">:</div>
                        <div class="col-4">
                            {{ leaveApplication.status }}
                        </div>
                    </div>
                    <div class="row m-4">
                        <div class="col-4">Start Date</div>
                        <div class="col-4">:</div>
                        <div class="col-4">
                            {{ leaveApplication.start_date }}
                        </div>
                    </div>
                    <div class="row m-4">
                        <div class="col-4">End Date</div>
                        <div class="col-4">:</div>
                        <div class="col-4">
                            {{ leaveApplication.end_date }}
                        </div>
                    </div>
                    <div class="row m-4">
                        <div class="col-4">Accumulation</div>
                        <div class="col-4">:</div>
                        <div class="col-4">
                            {{ leaveApplication.accumulation }}
                        </div>
                    </div>
                    <div class="row m-4">
                        <div class="col-4">Leave Type</div>
                        <div class="col-4">:</div>
                        <div class="col-4">
                            {{ leaveApplication.leave_type }}
                        </div>
                    </div>
                    <div class="row m-4">
                        <div class="col-4">officer Reject Reasons</div>
                        <div class="col-4">:</div>
                        <div class="col-4">
                            {{ leaveApplication.supervisor_reject_reasons }}
                        </div>
                    </div>
                    <div class="row m-4">
                        <div class="col-4">HR Reject Reasons</div>
                        <div class="col-4">:</div>
                        <div class="col-4">
                            {{ leaveApplication.manager_reject_reasons }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
