<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, usePage } from "@inertiajs/vue3";

const user = usePage().props.user;
const loggedRole = usePage().props.loggedRole;
</script>

<template>
    <Head title="Show Tasks" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                User Detail
            </h2>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="row">
                    <div class="col-12">
                        <div class="p-8 bg-white shadow sm:rounded-lg">
                            <h2
                                class="font-semibold text-xl text-gray-800 leading-tight mb-10"
                            >
                                User Detail
                            </h2>
                            <div class="row m-4">
                                <div class="col-4">Name</div>
                                <div class="col-4">:</div>
                                <div class="col-4">
                                    {{ user.name }}
                                </div>
                            </div>
                            <div class="row m-4">
                                <div class="col-4">Email Address</div>
                                <div class="col-4">:</div>
                                <div class="col-4">
                                    {{ user.email }}
                                </div>
                            </div>
                            <div class="row m-4">
                                <div class="col-4">Role</div>
                                <div class="col-4">:</div>
                                <div class="col-4">
                                    {{ user.role.name }}
                                </div>
                            </div>
                            <div class="row m-4">
                                <div class="col-4">Supervisor</div>
                                <div class="col-4">:</div>
                                <div class="col-4">
                                    {{
                                        user.supervisor ? user.supervisor : "-"
                                    }}
                                </div>
                            </div>
                            <div class="row m-4">
                                <div class="col-4">Manager</div>
                                <div class="col-4">:</div>
                                <div class="col-4">
                                    {{ user.manager ? user.manager : "-" }}
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
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
