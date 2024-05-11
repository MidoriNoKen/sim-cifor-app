<template>
    <Head title="Travel Authorisation" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Travel Authorisation List
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
                                    <th>Transport Type</th>
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
                                    v-for="travelAuthorisation in travelAuthorisations"
                                    :key="travelAuthorisation.id"
                                >
                                    <td
                                        v-if="
                                            user.position !== 'Junior' &&
                                            loggedRole !== 'Staff'
                                        "
                                    >
                                        {{ travelAuthorisation.applicant }}
                                    </td>
                                    <td>
                                        {{ travelAuthorisation.transport_type }}
                                    </td>
                                    <td>{{ travelAuthorisation.status }}</td>
                                    <td>
                                        {{ travelAuthorisation.start_date }}
                                    </td>
                                    <td>{{ travelAuthorisation.end_date }}</td>
                                    <td>
                                        {{
                                            travelAuthorisation.day_accumulation
                                        }}
                                    </td>
                                    <td>
                                        {{ travelAuthorisation.supervisor }}
                                    </td>
                                    <td>{{ travelAuthorisation.manager }}</td>
                                    <td>
                                        <IndexButton
                                            :travelAuthorisation="
                                                travelAuthorisation
                                            "
                                        />
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="text-center" v-if="loggedRole === 'Staff'">
                            <button
                                @click="createTravelAuthorisation"
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

<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, router, usePage } from "@inertiajs/vue3";
import IndexButton from "./Partials/IndexButton.vue";
const { travelAuthorisations, loggedRole } = usePage().props;
const user = usePage().props.auth.user;

const createTravelAuthorisation = () => {
    router.get(`/travel-authorisations/create`);
};
</script>
