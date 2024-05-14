<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, router, usePage } from "@inertiajs/vue3";

const travelAuthorisation = usePage().props.travelAuthorisation;
const user = usePage().props.user;
const loggedRole = usePage().props.loggedRole;
</script>

<template>
    <Head title="Travel Authorisation Applications" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Travel Authorisation List
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
                        <div class="col-4">Supervisor</div>
                        <div class="col-4">:</div>
                        <div class="col-4">
                            {{ user.supervisor }}
                        </div>
                    </div>
                    <div class="row m-4">
                        <div class="col-4">Manager</div>
                        <div class="col-4">:</div>
                        <div class="col-4">
                            {{ user.manager }}
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
                        Travel Authorisation Detail
                    </h2>
                    <div class="row m-4">
                        <div class="col-4">Status</div>
                        <div class="col-4">:</div>
                        <div class="col-4">
                            {{ travelAuthorisation.status }}
                        </div>
                    </div>
                    <div class="row m-4">
                        <div class="col-4">Start Date</div>
                        <div class="col-4">:</div>
                        <div class="col-4">
                            {{ travelAuthorisation.start_date }}
                        </div>
                    </div>
                    <div class="row m-4">
                        <div class="col-4">End Date</div>
                        <div class="col-4">:</div>
                        <div class="col-4">
                            {{ travelAuthorisation.end_date }}
                        </div>
                    </div>
                    <div class="row m-4">
                        <div class="col-4">Accumulation</div>
                        <div class="col-4">:</div>
                        <div class="col-4">
                            {{ travelAuthorisation.accumulation }}
                        </div>
                    </div>
                    <div class="row m-4">
                        <div class="col-4">Transport Type</div>
                        <div class="col-4">:</div>
                        <div class="col-4">
                            {{ travelAuthorisation.transport_type }}
                        </div>
                    </div>
                    <div class="row m-4">
                        <div class="col-4">Accomodation Detail</div>
                        <div class="col-4">:</div>
                        <div class="col-4">
                            {{ travelAuthorisation.accomodation_detail }}
                        </div>
                    </div>
                    <div class="row m-4">
                        <div class="col-4">Travel Reason</div>
                        <div class="col-4">:</div>
                        <div class="col-4">
                            {{ travelAuthorisation.travel_reasons }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
