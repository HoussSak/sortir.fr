// filter par status
document.addEventListener("DOMContentLoaded", function () {
    const select = document.querySelector('select[name="etat"]');

    select.addEventListener('change', function () {
        const selectedOption = select.value;
        const rows = document.querySelectorAll('tbody tr');

        rows.forEach(row => {
            const statusCell = row.children[5];
            console.log(statusCell);
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
                const sortieNameCell = row.children[1];
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
// filter les sorties par date
document.addEventListener('DOMContentLoaded', function () {
    const dateDebutInput = document.querySelector('#dateDebut');
    const dateFinInput = document.querySelector('#dateFin');

    if (dateDebutInput) {
        dateDebutInput.addEventListener('change', filterByDate);
    }

    if (dateFinInput) {
        dateFinInput.addEventListener('change', filterByDate);
    }

    function filterByDate() {
        const debutDate = new Date(dateDebutInput.value);
        const finDate = new Date(dateFinInput.value);

        const rows = document.querySelectorAll('tbody tr');

        rows.forEach(row => {
            const dateDebutCell = row.children[2].textContent;
            const formattedSelectedDateDebut = `${debutDate.getDate().toString().padStart(2, '0')}/${(debutDate.getMonth() + 1).toString().padStart(2, '0')}/${debutDate.getFullYear()}`;
            const dateFinCell = row.children[2].textContent;
            const formattedSelectedDateFin = `${finDate.getDate().toString().padStart(2, '0')}/${(finDate.getMonth() + 1).toString().padStart(2, '0')}/${finDate.getFullYear()}`;
            console.log(dateDebutCell)
            console.log(formattedSelectedDateDebut)
            if (dateDebutCell >= formattedSelectedDateDebut && dateFinCell <= formattedSelectedDateFin) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }
});


// sorties dont je suis l organisateur
document.addEventListener('DOMContentLoaded', function () {
    const organisateurCheckbox = document.querySelector('input[name="organisateur"]');
    const userDisplayName = document.getElementById('userDisplayName').textContent;
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

document.addEventListener('DOMContentLoaded', function () {
    const dateInput = document.querySelector('#dateD').textContent;
    console.log(dateInput)
    const today = new Date().toISOString().split('T')[0];

    dateInput.setAttribute('min', today);

    dateInput.addEventListener('change', function () {
        if (dateInput.value < today) {
            dateInput.value = today;
        }
    });
});




