<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, router, usePage } from "@inertiajs/vue3";

const project = usePage().props.project;
const pm = usePage().props.pm;
const loggedRole = usePage().props.loggedRole;
</script>

<template>
    <Head title="Show Projects" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Project List
            </h2>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="p-8 bg-white shadow sm:rounded-lg">
                    <h2
                        class="font-semibold text-xl text-gray-800 leading-tight mb-10"
                    >
                        Project Manager Detail
                    </h2>
                    <div class="row m-4">
                        <div class="col-4">Name</div>
                        <div class="col-4">:</div>
                        <div class="col-4">
                            {{ pm.name }}
                        </div>
                    </div>
                    <div class="row m-4">
                        <div class="col-4">Email</div>
                        <div class="col-4">:</div>
                        <div class="col-4">
                            {{ pm.email }}
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
                        <div class="col-4">Supervisor</div>
                        <div class="col-4">:</div>
                        <div class="col-4">
                            {{ pm.supervisor }}
                        </div>
                    </div>
                    <div class="row m-4">
                        <div class="col-4">Manager</div>
                        <div class="col-4">:</div>
                        <div class="col-4">
                            {{ pm.manager }}
                        </div>
                    </div>
                    <div class="row m-4">
                        <div class="col-4">Position</div>
                        <div class="col-4">:</div>
                        <div class="col-4">
                            {{ pm.position }}
                        </div>
                    </div>
                    <div class="row m-4">
                        <div class="col-4">Born Date</div>
                        <div class="col-4">:</div>
                        <div class="col-4">
                            {{ pm.born_date }}
                        </div>
                    </div>
                </div>
                <div class="p-8 bg-white shadow sm:rounded-lg">
                    <h2
                        class="font-semibold text-xl text-gray-800 leading-tight mb-10"
                    >
                        Project Detail
                    </h2>
                    <div class="row m-4">
                        <div class="col-4">Name</div>
                        <div class="col-4">:</div>
                        <div class="col-4">
                            {{ project.name }}
                        </div>
                    </div>
                    <div class="row m-4">
                        <div class="col-4">Start Date</div>
                        <div class="col-4">:</div>
                        <div class="col-4">
                            {{ project.start_date }}
                        </div>
                    </div>
                    <div class="row m-4">
                        <div class="col-4">End Date</div>
                        <div class="col-4">:</div>
                        <div class="col-4">
                            {{ project.end_date }}
                        </div>
                    </div>
                    <div class="row m-4">
                        <div class="col-4">Status</div>
                        <div class="col-4">:</div>
                        <div class="col-4">
                            {{ project.status }}
                        </div>
                    </div>
                    <div class="row m-4">
                        <div class="col-4">Description</div>
                        <div class="col-4">:</div>
                        <div class="col-4">
                            {{ project.description }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
