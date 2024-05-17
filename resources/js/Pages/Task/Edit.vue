<script setup>
import { Head } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import DeleteProjectForm from "@/Pages/Project/Partials/DeleteProjectForm.vue";
import UpdatePasswordForm from "@/Components/Forms/UpdatePasswordForm.vue";
import UpdateProjectInformationForm from "./Partials/UpdateProjectInformationForm.vue";

const clearAlerts = () => {
    $page.props.success = "";
    $page.props.error = "";
};
</script>

<template>
    <Head title="Edit Project" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Edit Project
            </h2>
        </template>

        <div class="py-12">
            <Alert
                :successMessage="$page.props.success"
                :errorMessage="$page.props.error"
                @close="clearAlerts"
            />
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <UpdateProjectInformationForm />
                </div>

                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <UpdatePasswordForm />
                </div>

                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <DeleteProjectForm />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
