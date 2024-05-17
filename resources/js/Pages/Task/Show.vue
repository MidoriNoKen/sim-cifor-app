<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, usePage } from "@inertiajs/vue3";

const task = usePage().props.task;
const project = usePage().props.project;
const user = usePage().props.user;
const loggedRole = usePage().props.loggedRole;
</script>

<template>
    <Head title="Show Tasks" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Task Detail
            </h2>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="row">
                    <div class="col-6">
                        <div class="p-8 bg-white shadow sm:rounded-lg">
                            <h2
                                class="font-semibold text-xl text-gray-800 leading-tight mb-10"
                            >
                                User Assigned Detail
                            </h2>
                            <div class="row m-4">
                                <div class="col-4">Name</div>
                                <div class="col-2">:</div>
                                <div class="col-6">
                                    {{ user.name }}
                                </div>
                            </div>
                            <div class="row m-4">
                                <div class="col-4">Email Address</div>
                                <div class="col-2">:</div>
                                <div class="col-6">
                                    {{ user.email }}
                                </div>
                            </div>
                            <div class="row m-4">
                                <div class="col-4">Role</div>
                                <div class="col-2">:</div>
                                <div class="col-6">
                                    {{ user.role.name }}
                                </div>
                            </div>
                            <div class="row m-4">
                                <div class="col-4">Position</div>
                                <div class="col-2">:</div>
                                <div class="col-6">
                                    {{ user.position }}
                                </div>
                            </div>
                            <div class="row m-4">
                                <div class="col-4">Born Date</div>
                                <div class="col-2">:</div>
                                <div class="col-6">
                                    {{ user.born_date }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-8 bg-white shadow sm:rounded-lg">
                            <h2
                                class="font-semibold text-xl text-gray-800 leading-tight mb-10"
                            >
                                Project Detail
                            </h2>
                            <div class="row m-4">
                                <div class="col-4">Name</div>
                                <div class="col-2">:</div>
                                <div class="col-6">
                                    {{ project.name }}
                                </div>
                            </div>
                            <div class="row m-4">
                                <div class="col-4">Start Date</div>
                                <div class="col-2">:</div>
                                <div class="col-6">
                                    {{ project.start_date }}
                                </div>
                            </div>
                            <div class="row m-4">
                                <div class="col-4">End Date</div>
                                <div class="col-2">:</div>
                                <div class="col-6">
                                    {{ project.end_date }}
                                </div>
                            </div>
                            <div class="row m-4">
                                <div class="col-4">Status</div>
                                <div class="col-2">:</div>
                                <div class="col-6">
                                    {{ project.status }}
                                </div>
                            </div>
                            <div class="row m-4">
                                <div class="col-4">Description</div>
                                <div class="col-2">:</div>
                                <div class="col-6">
                                    {{ project.description }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="p-8 bg-white shadow sm:rounded-lg">
                            <h2
                                class="font-semibold text-xl text-gray-800 leading-tight mb-10"
                            >
                                Task Detail
                            </h2>
                            <div class="row m-4">
                                <div class="col-4">Name</div>
                                <div class="col-4">:</div>
                                <div class="col-4">
                                    {{ task.name }}
                                </div>
                            </div>
                            <div class="row m-4">
                                <div class="col-4">Start Date</div>
                                <div class="col-4">:</div>
                                <div class="col-4">
                                    {{ task.start_date }}
                                </div>
                            </div>
                            <div class="row m-4">
                                <div class="col-4">End Date</div>
                                <div class="col-4">:</div>
                                <div class="col-4">
                                    {{ task.end_date }}
                                </div>
                            </div>
                            <div class="row m-4">
                                <div class="col-4">Priority</div>
                                <div class="col-4">:</div>
                                <div class="col-4">
                                    {{ task.priority }}
                                </div>
                            </div>
                            <div class="row m-4">
                                <div class="col-4">Status</div>
                                <div class="col-4">:</div>
                                <div class="col-4">
                                    {{ task.status }}
                                </div>
                            </div>
                            <div class="row m-4">
                                <div class="col-4">Description</div>
                                <div class="col-4">:</div>
                                <div class="col-4">
                                    {{ task.description }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
