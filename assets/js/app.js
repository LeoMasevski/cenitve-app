document.addEventListener('click', function (event) {
    const button = event.target.closest('.delete-valuation-btn');

    if (!button) {
        return;
    }

    const valuationId = button.dataset.id;

    if (!valuationId) {
        alert('ID cenitve ni bil najden.');
        return;
    }

    if (!window.csrfToken) {
        alert('Varnostni žeton ni bil najden. Osvežite stran in poskusite ponovno.');
        return;
    }

    const confirmed = confirm('Ali ste prepričani, da želite izbrisati to cenitev?');

    if (!confirmed) {
        return;
    }

    fetch('/cenitve-app/api/valuation_delete.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            id: valuationId,
            csrf_token: window.csrfToken
        })
    })
        .then(function (response) {
            return response.json();
        })
        .then(function (data) {
            if (data.success) {
                const row = document.getElementById('valuation-row-' + valuationId);

                if (row) {
                    row.remove();
                }

                alert(data.message);
            } else {
                alert(data.message || 'Pri brisanju je prišlo do napake.');
            }
        })
        .catch(function () {
            alert('Pri povezavi s strežnikom je prišlo do napake.');
        });
});