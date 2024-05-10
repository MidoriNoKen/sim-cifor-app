<script setup>
import { Head } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import DeleteUserForm from "@/Pages/User/Partials/DeleteUserForm.vue";
import UpdatePasswordForm from "@/Components/Forms/UpdatePasswordForm.vue";
import UpdateUserInformationForm from "./Partials/UpdateUserInformationForm.vue";

const clearAlerts = () => {
    $page.props.success = "";
    $page.props.error = "";
};
</script>

<template>
    <Head title="User" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Edit User
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
                    <UpdateUserInformationForm />
                </div>

                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <UpdatePasswordForm />
                </div>

                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <DeleteUserForm />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
