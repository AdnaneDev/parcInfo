 let sidebar = document.querySelector(".sidebar");
let sidebarBtn = document.querySelector(".sidebarBtn");
sidebarBtn.onclick = function () {
  sidebar.classList.toggle("active");
  if (sidebar.classList.contains("active")) {
    sidebarBtn.classList.replace("bx-menu", "bx-menu-alt-right");
  } else sidebarBtn.classList.replace("bx-menu-alt-right", "bx-menu");
};

let popup =document.getElementById("popup")

function openIt(){
  popup.classList.add("open")

}
function closePopup(){
  popup.classList.remove("open")
}
function openEditPopup(productId) {
  // Récupérez les données du produit en utilisant une requête AJAX vers get_product_data.php
  fetch(`../model/get_product.php?id=${productId}`)
      .then(response => response.json())
      .then(data => {
          // Remplissez les champs du formulaire de modification avec les données du produit
          document.getElementById('designation').value = data.designation;
          document.getElementById('ref').value = data.ref;
          document.getElementById('categorie').value = data.id_categorie;
          document.getElementById('prix_unitaire').value = data.prix_unitaire;
          document.getElementById('id').value = data.id;
          
        
          openIt();
      })
      .catch(error => console.error(error));
}
function openEditPopupFournisseur(fourniID) {
  console.log(fourniID)
  fetch(`../model/get_fourni.php?id=${fourniID}`)
      .then(response => response.json())
      .then(data => {
          // Remplissez les champs du formulaire de modification avec les données du produit
          document.getElementById('designation').value = data.id;
          document.getElementById('nom').value = data.nom;
          document.getElementById('prenom').value = data.prenom;
          document.getElementById('rc').value = data.rc;
          document.getElementById('nif').value = data.nif;
          document.getElementById('nis').value = data.nis;
          document.getElementById('rib').value = data.rib;
          document.getElementById('email').value = data.email;
          document.getElementById('numero').value = data.numero;
          
          // Affichez le popup de modification
          openIt();
      })
      .catch(error => console.error(error));
}
function openEditUser(userID) {
    // Récupérez les données de l'utilisateur en utilisant une requête AJAX vers get_user.php
    fetch(`../model/get_user.php?id=${userID}`)
        .then(response => response.json())
        .then(data => {
            // Remplissez les champs du formulaire de modification avec les données de l'utilisateur
            document.getElementById('username').value = data.username;
            document.getElementById('password').value = ''; // Réinitialisez le mot de passe
            document.getElementById('confirm_password').value = ''; // Réinitialisez la confirmation du mot de passe
            document.getElementById('name').value = data.name;
            document.getElementById('lastname').value = data.last_name;
            document.getElementById('role').value = data.id_role;
            document.getElementById('id_service').value = data.id_service;
            document.getElementById('id_salle').value = data.id_salle;
            console.log(data.id_salle)
            // Affichez le popup de modification
            openIt();
        })
        .catch(error => console.error(error));
}
