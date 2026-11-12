document.addEventListener("DOMContentLoaded", function () {
    const registrationForm = document.getElementById("registrationForm");

    if (registrationForm) {
        registrationForm.addEventListener("submit", function (event) {
            const button = registrationForm.querySelector(
                'button[type="submit"]'
            );

            if (button && !button.disabled) {
                const confirmed = confirm(
                    "Pastikan data dan semua dokumen persyaratan sudah benar. Lanjutkan pendaftaran?"
                );

                if (!confirmed) {
                    event.preventDefault();
                }
            }
        });
    }
});
