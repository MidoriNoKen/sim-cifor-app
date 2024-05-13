<script setup>
import { Head, usePage } from "@inertiajs/vue3";
import { useForm } from "@inertiajs/inertia-vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { Inertia } from "@inertiajs/inertia";

const loggedRole = usePage().props.loggedRole;
const leaveApplication = usePage().props.leaveApplication;

const form = useForm({
    supervisor_reject_reasons: "",
    manager_reject_reasons: "",
});

const handleSubmit = () => {
    const data = {
        supervisor_reject_reasons: form.supervisor_reject_reasons,
        manager_reject_reasons: form.manager_reject_reasons,
    };
    Inertia.post(`/leave-applications/${leaveApplication.id}/reject`, data);
};
</script>

<template>
    <Head title="Reject Leave Application" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Reject Leave Application
            </h2>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <section>
                        <header>
                            <h2 class="text-lg font-medium text-gray-900">
                                Reject Reason
                            </h2>
                        </header>
                        <div class="form-group row">
                            <form @submit.prevent="handleSubmit">
                                <div
                                    v-if="leaveApplication.isSupervisor"
                                    class="col-md-10 mb-4 mt-4"
                                >
                                    <textarea
                                        v-model="form.supervisor_reject_reasons"
                                        class="form-control"
                                    ></textarea>
                                </div>
                                <div
                                    v-if="leaveApplication.isManager"
                                    class="col-md-10 mb-4 mt-4"
                                >
                                    <textarea
                                        v-model="form.manager_reject_reasons"
                                        class="form-control"
                                    ></textarea>
                                </div>
                                <div class="flex items-center gap-4">
                                    <PrimaryButton :disabled="form.processing"
                                        >Save</PrimaryButton
                                    >

                                    <Transition
                                        enter-active-class="transition ease-in-out"
                                        enter-from-class="opacity-0"
                                        leave-active-class="transition ease-in-out"
                                        leave-to-class="opacity-0"
                                    >
                                        <p
                                            v-if="form.recentlySuccessful"
                                            class="text-sm text-gray-600"
                                        >
                                            Saved.
                                        </p>
                                    </Transition>
                                </div>
                            </form>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
