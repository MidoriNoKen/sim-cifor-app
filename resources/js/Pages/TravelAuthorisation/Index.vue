<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, router, usePage } from "@inertiajs/vue3";
import IndexButton from "./Partials/IndexButton.vue";
import { Bootstrap5Pagination } from "laravel-vue-pagination";
import { ref } from "vue";

const { role, position } = usePage().props.auth.user;
const travelAuthorisations = ref(usePage().props.travelAuthorisations);
const currentPage = ref(usePage().props.travelAuthorisations.current_page);
const lastPage = ref(usePage().props.travelAuthorisations.last_page);
const perPage = ref(usePage().props.travelAuthorisations.per_page);

const createTravelAuthorisation = () => {
    router.get(`/travel-authorisations/create`);
};

const getTravelAuthorisations = async (page = 1) => {
    router.get(
        `/travel-authorisations`,
        { page },
        {
            preserveState: true,
            replace: true,
            onSuccess: (page) => {
                travelAuthorisations.value = page.props.travelAuthorisations;
                currentPage.value =
                    page.props.travelAuthorisations.current_page;
                lastPage.value = page.props.travelAuthorisations.last_page;
                perPage.value = page.props.travelAuthorisations.per_page;
            },
        }
    );
};
</script>
<template>
    <Head title="Travel Authorisation" />

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
                                    <th>Transport</th>
                                    <th>Status</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Accumulation</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="travelAuthorisation in travelAuthorisations.data"
                                    :key="travelAuthorisation.id"
                                >
                                    <td
                                        v-if="
                                            position !== 'Junior' &&
                                            role.name !== 'Staff'
                                        "
                                    >
                                        {{ travelAuthorisation.applicant.name }}
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
                                        {{ travelAuthorisation.accumulation }}
                                    </td>
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
                        <Bootstrap5Pagination
                            :data="travelAuthorisations"
                            @pagination-change-page="getTravelAuthorisations"
                        />
                        <div class="text-center">
                            <div
                                class="mt-4 mb-4 fw-semibold"
                                v-if="travelAuthorisations.data.length == null"
                            >
                                No Data Available
                            </div>
                            <button
                                @click="createTravelAuthorisation()"
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
