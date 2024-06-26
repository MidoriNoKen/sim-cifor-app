const Ziggy = {
    url: "http://localhost",
    port: null,
    defaults: {},
    routes: {
        "sanctum.csrf-cookie": {
            uri: "sanctum/csrf-cookie",
            methods: ["GET", "HEAD"],
        },
        "ignition.healthCheck": {
            uri: "_ignition/health-check",
            methods: ["GET", "HEAD"],
        },
        "ignition.executeSolution": {
            uri: "_ignition/execute-solution",
            methods: ["POST"],
        },
        "ignition.updateConfig": {
            uri: "_ignition/update-config",
            methods: ["POST"],
        },
        dashboard: { uri: "dashboard", methods: ["GET", "HEAD"] },
        "profile.edit": { uri: "profile", methods: ["GET", "HEAD"] },
        "profile.update": { uri: "profile", methods: ["PATCH"] },
        "profile.destroy": { uri: "profile", methods: ["DELETE"] },
        "users.index": { uri: "users", methods: ["GET", "HEAD"] },
        "users.create": { uri: "users/create", methods: ["GET", "HEAD"] },
        "users.store": { uri: "users", methods: ["POST"] },
        "users.show": {
            uri: "users/{user}",
            methods: ["GET", "HEAD"],
            parameters: ["user"],
        },
        "users.edit": {
            uri: "users/{user}/edit",
            methods: ["GET", "HEAD"],
            parameters: ["user"],
            bindings: { user: "id" },
        },
        "users.update": {
            uri: "users/{user}",
            methods: ["PUT", "PATCH"],
            parameters: ["user"],
            bindings: { user: "id" },
        },
        "users.destroy": {
            uri: "users/{user}",
            methods: ["DELETE"],
            parameters: ["user"],
            bindings: { user: "id" },
        },
        "leaveApplications.index": {
            uri: "leave-applications",
            methods: ["GET", "HEAD"],
        },
        "leaveApplications.detail": {
            uri: "leave-applications/{id}",
            methods: ["GET", "HEAD"],
            parameters: ["id"],
        },
        "leaveApplications.create": {
            uri: "leave-applications/create",
            methods: ["GET", "HEAD"],
        },
        "leaveApplications.store": {
            uri: "leave-applications",
            methods: ["POST"],
        },
        "leaveApplications.reject": {
            uri: "leave-applications/{id}/reject",
            methods: ["GET", "HEAD"],
            parameters: ["id"],
        },
        "leaveApplications.unreject": {
            uri: "leave-applications/{id}/unreject",
            methods: ["POST"],
            parameters: ["id"],
        },
        "leaveApplications.ignore": {
            uri: "leave-applications/{id}/reject",
            methods: ["POST"],
            parameters: ["id"],
        },
        "leaveApplications.approveBySupervisor": {
            uri: "leave-applications/{id}/approve-by-officer",
            methods: ["POST"],
            parameters: ["id"],
        },
        "leaveApplications.disapproveBySupervisor": {
            uri: "leave-applications/{id}/disapprove-by-officer",
            methods: ["POST"],
            parameters: ["id"],
        },
        "leaveApplications.approveByManager": {
            uri: "leave-applications/{id}/approve-by-HR",
            methods: ["POST"],
            parameters: ["id"],
        },
        "leaveApplications.disapproveByManager": {
            uri: "leave-applications/{id}/disapprove-by-HR",
            methods: ["POST"],
            parameters: ["id"],
        },
        "travel-authorisations.index": {
            uri: "travel-authorisations",
            methods: ["GET", "HEAD"],
        },
        "travelAuthorisations.detail": {
            uri: "travel-authorisations/{id}",
            methods: ["GET", "HEAD"],
            parameters: ["id"],
        },
        "travel-authorisations.create": {
            uri: "travel-authorisations/create",
            methods: ["GET", "HEAD"],
        },
        "travel-authorisations.store": {
            uri: "travel-authorisations",
            methods: ["POST"],
        },
        "travelAuthorisations.reject": {
            uri: "travel-authorisations/{id}/reject",
            methods: ["GET", "HEAD"],
            parameters: ["id"],
        },
        "travelAuthorisations.unreject": {
            uri: "travel-authorisations/{id}/unreject",
            methods: ["POST"],
            parameters: ["id"],
        },
        "travelAuthorisations.ignore": {
            uri: "travel-authorisations/{id}/reject",
            methods: ["POST"],
            parameters: ["id"],
        },
        "travelAuthorisations.approveBySupervisor": {
            uri: "travel-authorisations/{id}/approve-by-officer",
            methods: ["POST"],
            parameters: ["id"],
        },
        "travelAuthorisations.disapproveBySupervisor": {
            uri: "travel-authorisations/{id}/disapprove-by-officer",
            methods: ["POST"],
            parameters: ["id"],
        },
        "travelAuthorisations.approveByManager": {
            uri: "travel-authorisations/{id}/approve-by-HR",
            methods: ["POST"],
            parameters: ["id"],
        },
        "travelAuthorisations.disapproveByManager": {
            uri: "travel-authorisations/{id}/disapprove-by-HR",
            methods: ["POST"],
            parameters: ["id"],
        },
        "travel-authorisations.show": {
            uri: "travel-authorisations/{travel_authorisation}",
            methods: ["GET", "HEAD"],
            parameters: ["travel_authorisation"],
        },
        "travel-authorisations.edit": {
            uri: "travel-authorisations/{travel_authorisation}/edit",
            methods: ["GET", "HEAD"],
            parameters: ["travel_authorisation"],
        },
        "travel-authorisations.update": {
            uri: "travel-authorisations/{travel_authorisation}",
            methods: ["PUT", "PATCH"],
            parameters: ["travel_authorisation"],
        },
        "travel-authorisations.destroy": {
            uri: "travel-authorisations/{travel_authorisation}",
            methods: ["DELETE"],
            parameters: ["travel_authorisation"],
        },
        register: { uri: "register", methods: ["GET", "HEAD"] },
        login: { uri: "login", methods: ["GET", "HEAD"] },
        "password.request": {
            uri: "forgot-password",
            methods: ["GET", "HEAD"],
        },
        "password.email": { uri: "forgot-password", methods: ["POST"] },
        "password.reset": {
            uri: "reset-password/{token}",
            methods: ["GET", "HEAD"],
            parameters: ["token"],
        },
        "password.store": { uri: "reset-password", methods: ["POST"] },
        "verification.notice": {
            uri: "verify-email",
            methods: ["GET", "HEAD"],
        },
        "verification.verify": {
            uri: "verify-email/{id}/{hash}",
            methods: ["GET", "HEAD"],
            parameters: ["id", "hash"],
        },
        "verification.send": {
            uri: "email/verification-notification",
            methods: ["POST"],
        },
        "password.confirm": {
            uri: "confirm-password",
            methods: ["GET", "HEAD"],
        },
        "password.update": { uri: "password", methods: ["PUT"] },
        logout: { uri: "logout", methods: ["POST"] },
    },
};
if (typeof window !== "undefined" && typeof window.Ziggy !== "undefined") {
    Object.assign(Ziggy.routes, window.Ziggy.routes);
}
export { Ziggy };
