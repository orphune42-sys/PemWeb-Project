function toggleEdit() {
    const editProfile = document.getElementById("editProfile");

    if (!editProfile) {
        return;
    }
    editProfile.classList.toggle("hidden");
}


function toggleSkillForm() {
    const skillForm = document.getElementById("addSkillForm");

    if (!skillForm) {
        return;
    }
    skillForm.classList.toggle("hidden");
}

function openAcademicModal() {

    const modal =
        document.getElementById("academicModal");

    if (!modal) {
        return;
    }

    modal.classList.remove("hidden");
}


function closeAcademicModal() {

    const modal =
        document.getElementById("academicModal");

    if (!modal) {
        return;
    }

    modal.classList.add("hidden");
}


const academicModal =
    document.getElementById("academicModal");


if (academicModal) {

    academicModal.addEventListener(
        "click",
        function (event) {

            if (event.target === academicModal) {

                closeAcademicModal();

            }

        }
    );

}

/* KONFIRMASI HAPUS KEAHLIAN */

const deleteSkillForms =
    document.querySelectorAll(".delete-skill-form");

deleteSkillForms.forEach(function (form) {
    form.addEventListener("submit", function (event) {
        const yakin = confirm("Yakin ingin menghapus keahlian ini?");
        if (!yakin) {
            event.preventDefault();
        }
    });
});