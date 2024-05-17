<script setup>
import { Head } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import DeleteTaskForm from "@/Pages/Task/Partials/DeleteTaskForm.vue";
import UpdatePasswordForm from "@/Components/Forms/UpdatePasswordForm.vue";
import UpdateTaskInformationForm from "./Partials/UpdateTaskInformationForm.vue";

const clearAlerts = () => {
    $page.props.success = "";
    $page.props.error = "";
};
</script>

<template>
    <Head title="Edit Task" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Edit Task
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <UpdateTaskInformationForm />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
