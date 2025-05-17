document.addEventListener('DOMContentLoaded', function() {
    const petSelect = document.getElementById('petName');
    const breedSelect = document.getElementById('breed');
    const specieSelect = document.getElementById('specie');
    const userSelect = document.getElementById('petOwner');
    const petSelects = [petSelect, breedSelect, specieSelect];

    const isPetOwnerLoggedIn = userSelect && userSelect.tagName !== 'SELECT';

    // tinatago options pag wala sa pet owner
    petSelects.forEach(function(select) {
        Array.from(select.options).forEach(function(option) {
            if (option.value) option.style.display = 'none';
        });
    });

    if (!isPetOwnerLoggedIn) {
        // Pag admin/superadmin naka log in ididisplay pets depending sa pet owner
        userSelect.addEventListener('change', function () {
            const ownerId = this.value;

            petSelects.forEach(function (select) {
                Array.from(select.options).forEach(function (option) {
                    if (!option.value) {
                        option.style.display = '';
                        return;
                    }
                    option.style.display = (option.getAttribute('data-owner') === ownerId) ? '' : 'none';
                });
                select.value = '';
            });

            breedSelect.value = '';
            specieSelect.value = '';
        });

        if (userSelect.value) {
            userSelect.dispatchEvent(new Event('change'));
        }
    } else {
        // Pag pet owner naka log in, ididisplay pets nya
        petSelects.forEach(function(select) {
            Array.from(select.options).forEach(function(option) {
                option.style.display = ''; // show everything
            });
        });
    }

    // para auto select ang breed at specie kapag na select na ang pet
    petSelect.addEventListener('change', function () {
        const selectedOption = petSelect.options[petSelect.selectedIndex];

        if(!selectedOption.value) {
            breedSelect.value = '';
            specieSelect.value = '';
            return;
        }

        const breed = selectedOption.getAttribute('data-breed');
        const specie = selectedOption.getAttribute('data-specie');

        // nilalagay ung breed
        Array.from(breedSelect.options).forEach(function(option) {
            if (option.value === breed && option.style.display !== 'none') {
                breedSelect.value = breed;
            }
        });

        // nilalagay ung specie
        Array.from(specieSelect.options).forEach(function(option) {
            if (option.value === specie && option.style.display !== 'none') {
                specieSelect.value = specie;
            }
        });
    });
});

document.addEventListener('DOMContentLoaded', function() {

    const declinedRadio = document.getElementById('declined');
    const declinedReasonCard = document.getElementById('declinedReasonCard');
    const declinedReasonInput = document.getElementById('declined_reason');

    const pendingRadio = document.getElementById('pending');
    const acceptedRadio = document.getElementById('accepted');

    function toggleDeclinedReason() {
        if (declinedRadio.checked) {
            declinedReasonCard.style.display = '';
            declinedReasonInput.disabled = false;
        } else {
            declinedReasonCard.style.display = 'none';
            declinedReasonInput.value = '';
            declinedReasonInput.disabled = true;
        }
    }

    [declinedRadio, pendingRadio, acceptedRadio].forEach(function(radio) {
        radio.addEventListener('change', toggleDeclinedReason);
    });

    // On page load, ensure correct state
    toggleDeclinedReason();
});
