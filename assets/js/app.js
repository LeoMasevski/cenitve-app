document.addEventListener('DOMContentLoaded', function () {
    const deleteButtons = document.querySelectorAll('.delete-valuation-btn');

    deleteButtons.forEach(function (button) {
        button.addEventListener('click', function () {
            const valuationId = button.dataset.id;

            const confirmed = confirm('Ali ste prepričani, da želite izbrisati to cenitev?');

            if (!confirmed) {
                return;
            }

            fetch('../api/valuation_delete.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    id: valuationId
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
    });
});