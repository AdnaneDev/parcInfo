<?php
include "./header.php";
include "../model/connexion.php";
/**--------------------------------------------------------------------------------------------------------------------------------------------- */


    $idUtilisateurConnecte = $_SESSION['id_user'];
  
    $demandes = getDemandesUtilisateur($idUtilisateurConnecte);

   



/**---------------------------------------------------------------------------------------------------------------------------------------------
*/
$sql = "SELECT COUNT(*) AS total_demandes FROM leparc.demande";

try {
// Étape 2 : Exécuter la requête SQL
$stmt = $connexion->query($sql);
$result = $stmt->fetch(PDO::FETCH_ASSOC);

if ($result) {
// Étape 3 : Récupérer le résultat et l'afficher dans le HTML
$totalDemandes = $result['total_demandes'];

} else {
echo '<div class="total-number">0</div>'; // En cas d'erreur ou de résultat vide
}
} catch (PDOException $e) {
// Gérer les erreurs de base de données si nécessaire
echo '<div class="total-number">Erreur de base de données</div>';
}

?>
<?php
try {
    $sql2 = "SELECT SUM(total-montant_restant) AS total_depenses FROM leparc.facture";
    // Étape 2 : Exécuter la requête SQL
    $stmt = $connexion->query($sql2);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        // Étape 3 : Récupérer le résultat et l'afficher dans le HTML
        $totalDepenses = $result['total_depenses'];
     
    } else {
        echo '<div class="total-number">$0.00</div>'; // En cas d'erreur ou de résultat vide
    }
} catch (PDOException $e) {
    // Gérer les erreurs de base de données si nécessaire
    echo '<div class="total-number">Erreur de base de données</div>';
}?>


<?php
try {
    $sql2 = "SELECT SUM(montant_restant) AS total_dettes FROM leparc.facture";
    // Étape 2 : Exécuter la requête SQL
    $stmt = $connexion->query($sql2);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        // Étape 3 : Récupérer le résultat et l'afficher dans le HTML
        $totalDettes = $result['total_dettes'];
     
    } else {
        echo '<div class="total-number">$0.00</div>'; // En cas d'erreur ou de résultat vide
    }
} catch (PDOException $e) {
    // Gérer les erreurs de base de données si nécessaire
    echo '<div class="total-number">Erreur de base de données</div>';
}?>






<div class="home">
    <div class="container2">

        <?php
        if ($role !== 3 && $role !==6) {
        echo '<div class="lesMini">';

 
        echo '<div class="dashboard-card-mini">
                <h5>Nombre de Demande Total:</h5>
                <div class="total-number">' . $totalDemandes . ' Demande</div>
              </div>';
        echo '<div class="dashboard-card-mini">
                <h5>Dépense Totale:</h5>
                <div class="total-number">' . number_format($totalDepenses, 2) . ' DA</div>
              </div>';
        echo '<div class="dashboard-card-mini">
                <h5>Dettes Totale:</h5>
                <div class="total-number">' . number_format($totalDettes, 2) . ' DA</div>
              </div>';
  
echo'</div>';  }
    ?>
        <div class="mescomm <?php echo ($role === 3 || $role ===6) ? 'user' : ''; ?>">
            <h2>Mes demande</h2>
            <div class="table-wrapper">
                <table id="myDataTable">
                    <thead>
                        <tr>
                            <td>Date</td>
                            <td>produit</td>
                            <td>Quantite</td>
                            <td>statut</td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
              
       
  
                if(!empty($demandes)&&is_array($demandes)){
                    
                    foreach($demandes as $key =>$value){
                     
                ?>
                        <tr>
                            <td><?=$value['date_demande']?></td>

                            <td><?=$value['nom']?></td>
                            <td><?=$value['quantite']?></td>

                            <td><?=$value['etat']?> </td>

                            <?php
      
    }
   }

   ?>
                    </tbody>

                </table>
            </div>
        </div>
    </div>
    <script>
    function animateCounter3(targetElement, targetValue, duration) {
        const element = document.querySelector(targetElement);
        const startValue = parseFloat(element.innerText);
        const increment = (targetValue - startValue) / duration;
        let currentValue = startValue;

        const animationInterval = setInterval(() => {
            currentValue += increment;
            element.innerText = currentValue + ' Demande';

            if (currentValue >= targetValue) {
                clearInterval(animationInterval);
                element.innerText = targetValue + ' Demande';
            }
        }, 10); // Update every 10 milliseconds (adjust if needed)
    }

    // Use the function to animate the "Dépense Totale" counter
    const totalDemandes = <?= $totalDemandes ?>;
    animateCounter3('.dashboard-card-mini:nth-child(1) .total-number', totalDemandes,
        2000); // 1000 milliseconds (1 second) for the animation

    // Function for animating a counter to a given value
    function animateCounter(targetElement, targetValue, duration) {
        const element = document.querySelector(targetElement);
        const startValue = parseFloat(element.innerText);
        const increment = (targetValue - startValue) / duration;
        let currentValue = startValue;

        const animationInterval = setInterval(() => {
            currentValue += increment;
            element.innerText = currentValue.toFixed(2) + ' DA';

            if (currentValue >= targetValue) {
                clearInterval(animationInterval);
                element.innerText = targetValue.toFixed(2) + ' DA';
            }
        }, 10); // Update every 10 milliseconds (adjust if needed)
    }

    // Use the function to animate the "Dépense Totale" counter
    const totalDepenses = <?= $totalDepenses ?>;
    animateCounter('.dashboard-card-mini:nth-child(2) .total-number', totalDepenses,
        500); // 1000 milliseconds (1 second) for the animation


    function animateCounter2(targetElement, targetValue, duration) {
        const element = document.querySelector(targetElement);
        const startValue = parseFloat(element.innerText);
        const increment = (targetValue - startValue) / duration;
        let currentValue = startValue;

        const animationInterval = setInterval(() => {
            currentValue += increment;
            element.innerText = currentValue.toFixed(2) + ' DA';

            if (currentValue >= targetValue) {
                clearInterval(animationInterval);
                element.innerText = targetValue.toFixed(2) + ' DA';
            }
        }, 10); // Update every 10 milliseconds (adjust if needed)
    }

    // Use the function to animate the "Dépense Totale" counter
    const totalDettes = <?= $totalDettes ?>;
    animateCounter2('.dashboard-card-mini:nth-child(3) .total-number', totalDettes,
        1000); // 1000 milliseconds (1 second) for the animation
    </script>


    <?php 
        if ($role !== 3 && $role !== 4 && $role !==6 ) {

        echo '<div class="container">';

         echo'<div class="title">';
             echo'<h2>Fournisseur Dashboards</h2>';
             echo'<div class="dash_fourni">';

                 echo'<div class="dashboard-card">';
                     echo'<h5>Depense par fournisseur</h5>';
                     echo'<canvas id="myChart7"></canvas>';
                 echo'</div>';
                 echo'<div class="dashboard-card">';
                    echo '<h5>Dettes par fournisseur</h5>';
                     echo'<canvas id="myChart6"></canvas>';
                echo' </div>';
                 echo'<div class="dashboard-card">';
                     echo'<h5>Nombre de factures</h5>';
                 echo   '<canvas id="myChart4"></canvas>';
                 echo'</div>';
                 echo'</div>';
                 echo '</div>';


   echo '</div>';
    }?>

    <?php if ($role !== 3 && $role !==6) {?>

    <div class="container">
        <div class="title">
            <h2>Demande Dashboards</h2>
            <div class="dash_demande">

                <div class="dashboard-card">
                    <h5>Nombre de demandes par mois</h5>
                    <canvas id="myChart3"></canvas>
                </div>

                <div class="dashboard-card">
                    <h5>Nombre de demandes par Service</h5>
                    <canvas id="myChart8"></canvas>
                </div>
                <div class="dashboard-card">
                    <h5>Statut Demande</h5>
                    <canvas id="myChart5"></canvas>
                </div>
            </div>
        </div>
    </div>


    <div class="container3">

        <div class="title">
            <h2>Budget Dashboards</h2>
            <div class="dash_budget">

                <div class="dashboard-card">
                    <h5>Budget dépensé</h5>
                    <canvas id="myChart"></canvas>
                </div>
                <div class="dashboard-card">
                    <h5>Statut Facture</h5>
                    <canvas id="myChart2"></canvas>
                </div>


            </div>

        </div>
    </div>
    <?php }?>
</div>


<script>
$(document).ready(function() {
    $('#myDataTable').DataTable({
        lengthChange: false,
    });
});
</script>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<!-- <script>
//setup Block

const ctx = document.getElementById('myChart');
const data = {
    labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
    datasets: [{
        label: '# of Votes',
        data: [12, 19, 3, 5, 2, 3],
        borderWidth: 1
    }]
};
//configue Blocl
const config = {
    type: 'bar',
    data,
    options: {
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
}

//render
const myChart = new Chart(ctx, config);
</script> -->

<script>
const ctx6 = document.getElementById('myChart6');

async function fetchDettes() {
    const response = await fetch('../model/get_dettes.php');
    const data = await response.json();

    return data;
}

async function createChart() {
    const dettes = await fetchDettes();

    const data = {
        labels: dettes.map(item => item.fournisseur
            .toString()),

        datasets: [{
            label: 'Dettes',
            data: dettes.map(item => item.dette),
            borderWidth: 1,
            backgroundColor: '#FD6500',
        }]
    };

    const config = {
        type: 'bar',
        data,
        options: {
            animation: {
                delay: 500, // 1000 milliseconds (1 second) delay before animation starts
                duration: 2000, // Animation duration after the delay
                easing: 'linear', // Easing function for the animation
            },

            scales: {
                x: {
                    ticks: {
                        fontWeight: 'bold',
                        fontSize: 14,
                        color: 'white', // Couleur du texte de l'axe X (rouge)
                    }
                },
                y: {
                    beginAtZero: true,

                    ticks: {
                        fontWeight: 'bold',
                        fontSize: 14,
                        color: 'white',
                        callback: function(value, index) {

                            return value + ' DA';
                        },
                        stepSize: 1000,

                    }
                }
            }
        }
    };

    const myChart6 = new Chart(ctx6, config);
}
createChart();
</script>


<script>
const ctx7 = document.getElementById('myChart7');

async function fetchDepense() {
    const response = await fetch('../model/get_depense.php');
    const data = await response.json();

    return data;
}

async function createChart() {
    const depenses = await fetchDepense();

    const data = {
        labels: depenses.map(item => item.fournisseur
            .toString()),
        datasets: [{
            label: 'Depense',
            data: depenses.map(item => item.Depense),
            borderWidth: 1,
            backgroundColor: '#FD6500',
        }]

    };


    const config = {
        type: 'bar',
        data,
        options: {
            animation: {
                delay: 500, // 1000 milliseconds (1 second) delay before animation starts
                duration: 2000, // Animation duration after the delay
                easing: 'linear', // Easing function for the animation
            },

            scales: {

                x: {
                    ticks: {
                        fontWeight: 'bold',
                        fontSize: 14,
                        color: 'white', // Couleur du texte de l'axe X (rouge)
                    }
                },
                y: {
                    beginAtZero: true,

                    ticks: {
                        fontWeight: 'bold',
                        fontSize: 14,
                        color: 'white',
                        callback: function(value, index) {

                            return value + ' DA'; // Vous pouvez 
                        },
                        stepSize: 500
                    }
                }
            }
        }
    };

    const myChart6 = new Chart(ctx7, config);
}

createChart();
</script>





<script>
const ctx = document.getElementById('myChart');

function fetchMontantsTTC() {
    return fetch('../model/get_montant_ttc.php')
        .then(response => response.json())
        .then(data => data.montantsTTC);
}

async function createChart() {
    const montantsTTC = await fetchMontantsTTC();

    const data = {
        labels: montantsTTC.map(item => item.label),
        datasets: [{
            label: 'Montant TTC',
            data: montantsTTC.map(item => item.montantTTC),
            borderWidth: 1,
            borderColor: '#FD6500',
            backgroundColor: '#FD6500',
        }]
    };

    const config = {
        type: 'line',
        data,
        options: {
            animation: {
                delay: 500, // 1000 milliseconds (1 second) delay before animation starts
                duration: 2000, // Animation duration after the delay
                easing: 'linear', // Easing function for the animation
            },

            scales: {
                x: {
                    ticks: {
                        beginAtZero: true,
                        callback: (value, index, values) => {
                            // Afficher les mois correspondant aux valeurs (0 = Janvier, 1 = Février, etc.)
                            const mois = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Août',
                                'Sept',
                                'Oct', 'Nov', 'Déc'
                            ];
                            return mois[value];
                        },
                        fontWeight: 'bold',
                        fontSize: 14,
                        color: 'white',
                    }
                },
                y: {
                    ticks: {
                        fontWeight: 'bold',
                        fontSize: 14,
                        color: 'white',
                    },
                    beginAtZero: true,

                }
            }
        }
    };

    const myChart = new Chart(ctx, config);
}

createChart();
</script>








<script>
const ctx2 = document.getElementById('myChart2');
async function fetchStatuesData() {
    return fetch('../model/get_statues_data.php')
        .then(response => response.json())
        .then(data => data.statuesData);
}

async function createPolarAreaChart() {
    const statuesData = await fetchStatuesData();

    const data = {
        labels: ['Payé', 'Non payé', 'Partiellement payé'],
        datasets: [{
            data: [statuesData.paye, statuesData.non_paye, statuesData.partiellement_paye],
            backgroundColor: ['#36A2EB', 'red', '#FFCE56'],
            borderWidth: 1
        }]
    };

    const config = {
        type: 'pie',
        data,
        options: {
            animation: {
                delay: 500, // 1000 milliseconds (1 second) delay before animation starts
                duration: 2000, // Animation duration after the delay
                easing: 'linear', // Easing function for the animation
            },

            responsive: true
        }
    };

    const myChart = new Chart(ctx2, config);
}
createPolarAreaChart()
</script>

<script>
const ctx5 = document.getElementById('myChart5');
async function fetchStatuesData() {
    return fetch('../model/get_statues_demmande.php')
        .then(response => response.json())
        .then(data => data.statuesData);
}

async function createPolarAreaChart() {
    const statuesData = await fetchStatuesData();

    const data = {
        labels: ['Confirmer', 'Annuler', 'En Cours'],
        datasets: [{
            data: [statuesData.confirmer, statuesData.annuler, statuesData.encours],
            backgroundColor: ['#36A2EB', 'red', '#FFCE56'],
            borderWidth: 1
        }]
    };

    const config = {


        type: 'pie',
        data,
        options: {
            animation: {
                delay: 500, // 1000 milliseconds (1 second) delay before animation starts
                duration: 1000, // Animation duration after the delay
                easing: 'ease-in', // Easing function for the animation
            },
            responsive: true
        }
    };

    const myChart = new Chart(ctx5, config);
}
createPolarAreaChart()
</script>


<script>
const ctx8 = document.getElementById('myChart8');

async function fetchDemandesParService() {
    return fetch('../model/get_demandes_par_service.php')
        .then(response => response.json())
        .then(data => data.demandesParService);
}

async function createBarChart() {
    const demandesParService = await fetchDemandesParService();

    // Extrayez les noms des services et les nombres de demandes correspondants
    const services = demandesParService.map(item => item.service);
    const nombreDemandes = demandesParService.map(item => item.nombre_demandes);

    const data = {
        labels: services,
        datasets: [{
            label: 'Nombre de demandes',
            data: nombreDemandes,
            backgroundColor: '#36A2EB',
            borderWidth: 1,
            backgroundColor: '#FD6500',
        }]
    };

    const config = {
        animation: {
            delay: 500, // 1000 milliseconds (1 second) delay before animation starts
            duration: 2000, // Animation duration after the delay
            easing: 'linear', // Easing function for the animation
        },

        type: 'bar',
        data,
        options: {
            scales: {
                x: {
                    ticks: {
                        fontWeight: 'bold',
                        fontSize: 14,
                        color: 'white',
                    },

                    beginAtZero: true,
                    maxRotation: 45, // Garde les étiquettes horizontales
                    autoSkip: false,

                },
                y: {

                    beginAtZero: true,
                    stepSize: 1,
                    suggestedMax: Math.max(...nombreDemandes) + 3,
                    ticks: {
                        fontWeight: 'bold',
                        fontSize: 14,
                        color: 'white',
                    },
                }
            }
        }
    };

    const myChart8 = new Chart(ctx8, config);
}

createBarChart();
</script>


















































<script>
const ctx3 = document.getElementById('myChart3');

async function fetchDemandesParMois() {
    return fetch('../model/get_demandes_par_mois.php')
        .then(response => response.json())
        .then(data => data.demandesParMois);
}

async function createBarChart() {
    const demandesParMois = await fetchDemandesParMois();

    const data = {
        labels: Object.keys(demandesParMois).map(month => parseInt(month)),
        datasets: [{
            label: 'Nombre de demandes',
            data: Object.values(demandesParMois),
            backgroundColor: '#36A2EB',
            borderWidth: 1,
            backgroundColor: '#FD6500',
        }]
    };

    const config = {


        type: 'bar',
        data,
        options: {
            animation: {
                delay: 500, // 1000 milliseconds (1 second) delay before animation starts
                duration: 2000, // Animation duration after the delay
                easing: 'linear', // Easing function for the animation
            },
            scales: {
                x: {
                    beginAtZero: true,


                    ticks: {
                        fontWeight: 'bold',
                        fontSize: 14,
                        color: 'white',
                        callback: (value, index, values) => {
                            // Afficher les mois correspondant aux valeurs (1 = Janvier, 2 = Février, etc.)
                            const mois = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Août',
                                'Sept',
                                'Oct', 'Nov', 'Déc'
                            ];
                            return mois[value - 1];
                        }
                    }
                },
                y: {
                    beginAtZero: true,
                    stepSize: 1,
                    suggestedMax: Math.max(...Object.values(demandesParMois)) + 3,
                    ticks: {
                        fontWeight: 'bold',
                        fontSize: 14,
                        color: 'white',
                    },
                }
            }
        }
    };

    const myChart3 = new Chart(ctx3, config);
}

createBarChart();
</script>















<script>
const ctx4 = document.getElementById('myChart4');

async function fetchFournisseursFactures() {
    return fetch('../model/get_fournisseurs_factures.php')
        .then(response => response.json())
        .then(data => data.fournisseursFactures);
}

async function createBarChart() {
    const fournisseursFactures = await fetchFournisseursFactures();

    const data = {
        labels: fournisseursFactures.map(item => item.nom),
        datasets: [{
            label: 'Nombre de factures',
            data: fournisseursFactures.map(item => item.nombre_factures),
            backgroundColor: '#36A2EB',
            borderWidth: 1,
            backgroundColor: '#FD6500',
        }]
    };

    const config = {
        animation: {
            delay: 3400, // 1000 milliseconds (1 second) delay before animation starts
            duration: 3000, // Animation duration after the delay
            easing: 'linear', // Easing function for the animation
        },

        type: 'bar',
        data,
        options: {
            scales: {
                x: {
                    ticks: {
                        fontWeight: 'bold',
                        fontSize: 14,
                        color: 'white',
                    },
                    beginAtZero: true,
                },
                y: {
                    ticks: {
                        fontWeight: 'bold',
                        fontSize: 14,
                        color: 'white',
                    },
                    beginAtZero: true,
                    suggestedMax: Math.max(...fournisseursFactures.map(item => item.nombre_factures)) + 3
                }
            }
        }
    };

    const fournisseursChart = new Chart(ctx4, config);
}

createBarChart();
</script>






























<?php
include "./footer.php";

?>