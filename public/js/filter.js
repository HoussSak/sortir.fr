// filter par status
document.addEventListener("DOMContentLoaded", function () {
    const select = document.querySelector('select[name="etat"]');

    select.addEventListener('change', function () {
        const selectedOption = select.value;
        const rows = document.querySelectorAll('tbody tr');

        rows.forEach(row => {
            const statusCell = row.children[5];
            console.log(statusCell);// Changer l'index en fonction de la position de la colonne "Etat"
            if (selectedOption === "Selectionner un statut") {
                row.style.display = '';
            } else if (statusCell.textContent.trim() === selectedOption) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
});

// filter par site
document.addEventListener('DOMContentLoaded', function () {
    const userSite = document.getElementById('userSite').textContent;
    const rows = document.querySelectorAll('tbody tr');
    console.log(userSite);


    rows.forEach(row => {
        const siteCell = row.children[8].textContent.trim();
        if (userSite !== 'null' && siteCell !== userSite) {
            row.style.display = 'none';
        }
    });

    const selectSite = document.querySelector('select[name="site"]');
    if (selectSite) {
        selectSite.value = userSite;
        selectSite.addEventListener('change', function () {
            const selectedSite = selectSite.value;

            rows.forEach(row => {
                const siteCell = row.children[8].textContent.trim();

                if (selectedSite === "Selectionner le site") {
                    row.style.display = '';
                } else if (siteCell === selectedSite) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    }
});


// fitrer par nom de sortie
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.querySelector('#search');
    if (searchInput) {
        searchInput.addEventListener('keyup', function () {
            const searchText = searchInput.value.toLowerCase();
            const rows = document.querySelectorAll('tbody tr');

            rows.forEach(row => {
                const sortieNameCell = row.children[1]; // Changer l'index en fonction de la position de la colonne "Nom de la sortie"
                const sortieName = sortieNameCell.textContent.toLowerCase();

                if (sortieName.startsWith(searchText)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    }
});

// filter par date de début
document.addEventListener('DOMContentLoaded', function () {
    const dateDebutInput = document.querySelector('#dateDebut');
    if (dateDebutInput) {
        dateDebutInput.addEventListener('change', function () {
            const selectedDate = new Date(dateDebutInput.value);
            const rows = document.querySelectorAll('tbody tr');

            rows.forEach(row => {
                const dateDebutCell = row.children[2]; // Changer l'index en fonction de la position de la colonne "Date de début"
                const formattedSelectedDate = `${selectedDate.getDate().toString().padStart(2, '0')}/${(selectedDate.getMonth() + 1).toString().padStart(2, '0')}/${selectedDate.getFullYear()}`;
                if (formattedSelectedDate === dateDebutCell.textContent) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    }
});

// filter par date de fin
document.addEventListener('DOMContentLoaded', function () {
    const dateFinInput = document.querySelector('#dateFin');
    if (dateFinInput) {
        dateFinInput.addEventListener('change', function () {
            const selectedDate = new Date(dateFinInput.value);
            const rows = document.querySelectorAll('tbody tr');

            rows.forEach(row => {
                const dateFinCell = row.children[3]; // Changer l'index en fonction de la position de la colonne "Date de début"
                const formattedSelectedDate = `${selectedDate.getDate().toString().padStart(2, '0')}/${(selectedDate.getMonth() + 1).toString().padStart(2, '0')}/${selectedDate.getFullYear()}`;
                if (formattedSelectedDate === dateFinCell.textContent) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    }
});

// sorties dont je suis l organisateur
document.addEventListener('DOMContentLoaded', function () {
    const organisateurCheckbox = document.querySelector('input[name="organisateur"]');
    const userDisplayName = document.getElementById('userDisplayName').textContent;
    console.log(userDisplayName);

    const filterByOrganisateur = () => {
        const isOrganisateurChecked = organisateurCheckbox.checked;
        const rows = document.querySelectorAll('tbody tr');

        rows.forEach(row => {
            const organisateurCell = row.children[7].textContent;
            const organisateurName = organisateurCell.split(' ')[0].trim();
            if (isOrganisateurChecked) {
                const displayStyle = organisateurName === userDisplayName ? '' : 'none';
                row.style.display = displayStyle;
            } else {
                row.style.display = '';
            }
        });
    };

    if (organisateurCheckbox) {
        organisateurCheckbox.addEventListener('change', filterByOrganisateur);
    }
});

// sorties auxquelles je participe
document.addEventListener('DOMContentLoaded', function () {
    const inscritCheckbox = document.querySelector('input[name="inscrit"]');
    const userDisplayName = document.getElementById('userDisplayName').textContent;

    const filterByInscrit = () => {
        const isInscritChecked = inscritCheckbox.checked;
        const rows = document.querySelectorAll('tbody tr');

        rows.forEach(row => {
            const inscritCell = row.children[6].textContent.trim();

            if (inscritCell === 'X' && isInscritChecked) {
                row.style.display = '';
            } else if (inscritCell !== 'X' && isInscritChecked) {
                row.style.display = 'none';
            } else {
                row.style.display = '';
            }
        });
    };

    if (inscritCheckbox) {
        inscritCheckbox.addEventListener('change', filterByInscrit);
    }
});

// sorties auxquelles je participe pas
document.addEventListener('DOMContentLoaded', function () {
    const nonInscritCheckbox = document.querySelector('input[name="nonInscrit"]');
    const userDisplayName = document.getElementById('userDisplayName').textContent;

    const filterByNonInscrit = () => {
        const isNonInscritChecked = nonInscritCheckbox.checked;
        const rows = document.querySelectorAll('tbody tr');

        rows.forEach(row => {
            const inscritCell = row.children[6].textContent.trim();

            if (inscritCell === 'X' && isNonInscritChecked) {
                row.style.display = 'none';
            } else if (inscritCell !== 'X' && isNonInscritChecked) {
                row.style.display = '';
            } else {
                row.style.display = '';
            }
        });
    };

    if (nonInscritCheckbox) {
        nonInscritCheckbox.addEventListener('change', filterByNonInscrit);
    }
});



