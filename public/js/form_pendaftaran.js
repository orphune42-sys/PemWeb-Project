
document.addEventListener("DOMContentLoaded", function () {
    const form = document.querySelector(".form-card");

    if (!form) return;

    const documentInputs = form.querySelectorAll(
        'input[type="file"][name^="dokumen["]'
    );

    // Menampilkan pesan error di bawah input
    function showError(input, message) {
        const errorElement = input.parentElement.querySelector(
            ".client-error"
        );

        if (errorElement) {
            errorElement.textContent = message;
            errorElement.hidden = false;
        }

        input.classList.add("input-error");
    }

    // Menghapus pesan error
    function clearError(input) {
        const errorElement = input.parentElement.querySelector(
            ".client-error"
        );

        if (errorElement) {
            errorElement.textContent = "";
            errorElement.hidden = true;
        }

        input.classList.remove("input-error");
    }

    // Validasi ketika file dipilih
    documentInputs.forEach(function (input) {
        input.addEventListener("change", function () {
            clearError(input);

            if (!input.files || input.files.length === 0) return;

            const file = input.files[0];
            const extension = file.name
                .split(".")
                .pop()
                .toLowerCase();

            const allowedExtensions = ["pdf", "jpg", "jpeg", "png"];
            const maxSize = 100 * 1024 * 1024;

            if (!allowedExtensions.includes(extension)) {
                showError(
                    input,
                    "Format file tidak valid. Gunakan PDF, JPG, atau PNG."
                );
                input.value = "";
                return;
            }

            if (file.size > maxSize) {
                showError(
                    input,
                    "Ukuran file maksimal 100 MB."
                );
                input.value = "";
            }
        });
    });

    // Validasi ketika formulir dikirim
    form.addEventListener("submit", function (event) {
        let firstInvalidInput = null;

        documentInputs.forEach(function (input) {
            clearError(input);

            if (!input.files || input.files.length === 0) {
                showError(
                    input,
                    'Dokumen "' +
                    (input.dataset.documentName || "persyaratan") +
                    '" wajib diunggah.'
                );

                if (!firstInvalidInput) {
                    firstInvalidInput = input;
                }
            }
        });

        if (firstInvalidInput) {
            event.preventDefault();
            firstInvalidInput.focus();
            return;
        }

        const confirmed = confirm(
            "Pastikan data dan semua dokumen persyaratan sudah benar. Lanjutkan pendaftaran?"
        );

        if (!confirmed) {
            event.preventDefault();
        }
    });
});