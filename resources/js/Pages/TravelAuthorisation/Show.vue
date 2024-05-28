<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, usePage } from "@inertiajs/vue3";

const { travelAuthorisation, user, finance } = usePage().props;
console.log(travelAuthorisation);
const loggedRole = usePage().props.auth.role;
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
                        <div class="col-4">Finance</div>
                        <div class="col-4">:</div>
                        <div class="col-4">
                            {{ finance ? finance.name : "-" }}
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
                        <div class="col-4">Travel Reason</div>
                        <div class="col-4">:</div>
                        <div class="col-4">
                            {{ travelAuthorisation.travel_reasons }}
                        </div>
                    </div>
                    <div class="row m-4">
                        <div class="col-4">Supervisor Reject Reasons</div>
                        <div class="col-4">:</div>
                        <div class="col-4">
                            {{ travelAuthorisation.supervisor_reject_reasons }}
                        </div>
                    </div>
                    <div class="row m-4">
                        <div class="col-4">Manager Reject Reasons</div>
                        <div class="col-4">:</div>
                        <div class="col-4">
                            {{ travelAuthorisation.manager_reject_reasons }}
                        </div>
                    </div>
                    <div class="row m-4">
                        <div class="col-4">Finance Reject Reasons</div>
                        <div class="col-4">:</div>
                        <div class="col-4">
                            {{ travelAuthorisation.finance_reject_reasons }}
                        </div>
                    </div>
                </div>
                <div class="p-8 bg-white shadow sm:rounded-lg">
                    <h2
                        class="font-semibold text-xl text-gray-800 leading-tight"
                    >
                        Accommodation Detail
                    </h2>
                </div>
                <div
                    class="p-8 bg-white shadow sm:rounded-lg mb-4"
                    v-for="(
                        accommodationDetail, index
                    ) in travelAuthorisation.accommodationDetails"
                    :key="index"
                >
                    <div class="row m-4">
                        <div class="col-4">Name</div>
                        <div class="col-4">:</div>
                        <div class="col-4">
                            {{ accommodationDetail.name }}
                        </div>
                    </div>
                    <div class="row m-4">
                        <div class="col-4">Quantity</div>
                        <div class="col-4">:</div>
                        <div class="col-4">
                            {{ accommodationDetail.quantity }}
                        </div>
                    </div>
                    <div class="row m-4">
                        <div class="col-4">Price</div>
                        <div class="col-4">:</div>
                        <div class="col-4">
                            {{ accommodationDetail.price }}
                        </div>
                    </div>
                    <div class="row m-4">
                        <div class="col-4">Total Price</div>
                        <div class="col-4">:</div>
                        <div class="col-4">
                            {{ accommodationDetail.total_price }}
                        </div>
                    </div>
                    <div class="row m-4">
                        <div class="col-4">Description</div>
                        <div class="col-4">:</div>
                        <div class="col-4">
                            {{ accommodationDetail.description }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
