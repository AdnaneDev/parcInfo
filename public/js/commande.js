// Supposons que vous avez des liens avec la classe "confirm-link" qui déclenchent la mise à jour du statut
document.querySelectorAll('.confirm-link').forEach(link => {
    link.addEventListener('click', function(event) {
        event.preventDefault();

        const orderId = this.dataset.orderId;

        // Envoyer une requête AJAX au fichier updatecommande.php
        fetch(`../model/updatecommande.php?id=${orderId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Statut de la commande mis à jour avec succès.');
                    // Vous pouvez effectuer des actions supplémentaires ici en cas de succès
                } else {
                    alert('Une erreur est survenue lors de la mise à jour du statut de la commande.');
                    // Gérer l'erreur ici
                }
            })
            .catch(error => {
                console.error('Une erreur s\'est produite :', error);
                // Gérer l'erreur ici
            });
    });
});


function openEditPopupCommande(commandeID) {
    console.log(commandeID)
    // Récupérez les données de la commande en utilisant une requête AJAX
    fetch(`../model/get_commande_details.php?id_commande=${commandeID}`)
        .then(response => response.json())
        .then(data => {
            // Remplir le formulaire avec les données de base de la commande
                document.getElementById('id_commande').value = data.id_commande;
            document.getElementById('date_commande').value = data.date_commande;
            document.getElementById('Num_commande').value = data.Num_commande;
            document.getElementById('id_fournisseur').value = data.id_fournisseur;

            // Remplir le formulaire avec les données des produits
            const produits = data.produits;

            // Sélectionnez le corps du tableau du formulaire
            const tableBody = document.querySelector('.facture-form tbody');

            // Supprimez toutes les lignes existantes sauf la première ligne d'en-tête
            const existingRows = tableBody.querySelectorAll('tr');
            existingRows.forEach((row, index) => {
                if (index !== 0) {
                    row.remove();
                }
            });

            // Parcourez les données des produits et ajoutez-les au tableau
            produits.forEach(produit => {
                const newRow = document.createElement('tr');
                newRow.innerHTML = `
                    <td>
                        <select type="text" name="id_produit[]" class="product-select" onchange="updatePrixUnitaire(this)" required>
                            <!-- Les options seront chargées dynamiquement via JavaScript -->
                        </select>
                    </td>
                    <td><input type="text" name="prix_unitaire[]" onchange="calculatePrixGlobal(this)" required></td>
                    <td><input type="text" name="quantite[]" onchange="calculatePrixGlobal(this)" required></td>
                    <td><input type="text" name="prix_global[]" required readonly></td>
                    <td><button class="remove-facture-button" type="button" onclick="removeFactureRow(this)">Remove</button></td>
                `;

                // Mettez à jour la valeur et le texte de l'option du select avec la désignation du produit
                const selectElement = newRow.querySelector('.product-select');
                const prixUnitaireInput = newRow.querySelector("input[name='prix_unitaire[]']");
                prixUnitaireInput.value = produit.prix_unitaire; // Assurez-vous que la clé correspond à la colonne de prix unitaire dans votre JSON
                const quantiteInput = newRow.querySelector("input[name='quantite[]']");
                quantiteInput.value = produit.quantite;

                // Écoutez les modifications du prix unitaire et de la quantité
                prixUnitaireInput.addEventListener('change', function () {
                    if (this.value !== '') { // Vérifiez si la valeur est définie
                        calculatePrixGlobal(this);
                    }
                });

                quantiteInput.addEventListener('change', function () {
                    if (this.value !== '') { // Vérifiez si la valeur est définie
                        calculatePrixGlobal(this);
                    }
                });

                if (produit.id_produit && produit.designation) {
                    const option = document.createElement('option');
                    option.value = produit.id_produit;
                    option.textContent = produit.designation;
                    selectElement.appendChild(option);
                }

                // Ajoutez la nouvelle ligne au tableau
                tableBody.appendChild(newRow);
            });

            // Ajoutez un bouton "Add" en dehors de la boucle forEach (sous la première ligne)
            const addButton = document.createElement('button');
            addButton.textContent = 'Add';
            addButton.type = 'button';
            addButton.classList.add('add_facture_button');
            addButton.addEventListener('click', addFactureRow);
            
            // Ajoutez le bouton "Add" au formulaire
            const form = document.querySelector('.facture_design');
            form.appendChild(addButton);

            // Affichez le popup de modification
            openIt();
        })
        .catch(error => console.error(error));
}


  



















































function filterCommande() {
    const filterStatus = document.getElementById('filterStatus').value;

    const commandes = document.getElementsByClassName('commande-row');

    for (const commande of commandes) {
        const commandeStatus = commande.getAttribute('data-status');
console.log(commandeStatus)
        if (filterStatus === 'all' || commandeStatus === filterStatus) {
            commande.style.display = 'table-row';
        } else {
            commande.style.display = 'none';
        }
    }
}






































function filterCommande2() {
    // Récupérez la valeur sélectionnée dans le menu déroulant du fournisseur
    const selectedFournisseur = document.getElementById('filterSupplier').value;

    // Récupérez toutes les lignes de la table des commandes
    const rows = document.querySelectorAll('.commande-row');

    // Affichez l'en-tête en premier
    const headerRow = rows[0];
    headerRow.style.display = ''; 

    // Parcourez chaque ligne de la table à partir de l'index 1 (après l'en-tête)
    for (let i = 1; i < rows.length; i++) {
        const row = rows[i];
        const supplierId = row.getAttribute('data-supplier');
        
        if (selectedFournisseur === 'all' || supplierId === selectedFournisseur) {
            row.style.display = ''; // Affichez la ligne si elle correspond au fournisseur sélectionné ou si "Tous les fournisseurs" est sélectionné
        } else { 
            row.style.display = 'none'; // Masquez la ligne si elle ne correspond pas au fournisseur sélectionné
        }   
    }
}




function filterFactures3() {
    const filterStatus = document.getElementById('filterStatus').value;
    const filterSupplier = document.getElementById('filterSupplier').value;
    const filterMonth = document.getElementById('filterMonth').value;

    const commandeTable = document.getElementById('myDataTable');
    const rows = commandeTable.querySelectorAll('.commande-row');
    console.log(rows);

    rows.forEach(row => {
        const status = row.getAttribute('data-status');
        const supplier = row.getAttribute('data-supplier');
        const month = row.getAttribute('data-month');

        const showRow =
            (filterStatus === 'all' || status === filterStatus) &&
            (filterSupplier === 'all' || supplier === filterSupplier) &&
            (filterMonth === 'all' || parseInt(month) === parseInt(filterMonth)); // Comparez les valeurs numériques des mois

        row.style.display = showRow ? 'table-row' : 'none';
    });
}

