<?php
session_start();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Medicare - Accueil</title>
    <link rel="stylesheet" href="style.css" />
    <link href="https://fonts.googleapis.com/css2?family=Merriweather&family=Poppins:wght@400;600&display=swap" rel="stylesheet">
</head>

<body>
    <header>
        <div class="container header-container">
            <h1 class="logo">MEDICARE</h1>
            <nav class="nav-links">
                <a href="index.php" class="active">Accueil</a>
                <a href="tout_parcourir.php">Tout Parcourir</a>
                <a href="rechercher.php">Recherche</a>
                <a href="mes_rendezvous.php">Rendez-Vous</a>
                <nav>
                    <?php if (isset($_SESSION['role'])): ?>
                        <a href="<?php
                            if ($_SESSION['role'] === 'admin') {
                                echo 'admin_dashboard.php';
                            } elseif ($_SESSION['role'] === 'client') {
                                echo 'client_dashboard.php';
                            } elseif ($_SESSION['role'] === 'medecin') {
                                echo 'medecin_dashboard.php';
                            }
                        ?>">
                            <button class="btn-login">Mon profil</button>
                        </a>
                        <a href="logout.php"><button class="btn-login">Se déconnecter</button></a>
                    <?php else: ?>
                        <a href="client_login.php"><button class="btn-login">Connexion Client</button></a>
                        <a href="medecin_login.php"><button class="btn-login">Connexion Médecin</button></a>
                    <?php endif; ?>
                </nav>
            </nav>
        </div>
    </header>

    <main class="container">
        <section class="pres">
            <div class="pres-text">
                <h2>Bienvenue sur Medicare</h2>
                <p>Medicare est votre portail de santé en ligne. Retrouvez ici toutes les informations utiles : prises de rendez-vous, spécialistes, laboratoires, actualités santé, et bien plus encore. Chaque semaine, découvrez un événement clé lié à la santé ou à la vie de l'établissement.</p>
            </div>
            <img src="Images/Docteur_dot_bg_.png" alt="Docteur" />
        </section>

        <section class="pres-carousel">
  <div class="carousel-inner">
    <img src="Images/carou1.jpg" alt="not available" class="slides" />
    <img src="Images/carou2.jpg" alt="not available" class="slides" />
    <img src="Images/carou3.jpg" alt="not available" class="slides" />
    <img src="Images/carou4.jpg" alt="not available" class="slides" />

    <button onclick="goBack()" class="carousel-btn left">&#10094;</button>
    <button onclick="goNext()" class="carousel-btn right">&#10095;</button>
  </div>
</section>



        <section class="events">
            <h3>Événement de la semaine</h3>
            <div class="event-cards">
                <div class="card">
                    <img src="Images/a.jpg" alt="Séminaire" />
                    <h4>Séminaire de prévention</h4>
                    <p>Participez à notre séminaire hebdomadaire autour de la prévention des maladies chroniques.</p>
                </div>
                <div class="card">
                    <img src="Images/d.jpg" alt="Administration" />
                    <h4>Rencontre avec l'administration</h4>
                    <p>Discutez de vos besoins de santé avec les responsables de Medicare.</p>
                </div>
            </div>
        </section>

        <section class="bulletin">
            <h3>Bulletin santé de la semaine</h3>
            <div class="bulletin-content">
                <div class="bulletin-text">
                    <h4>Covid | France | Printemps 2025</h4>
                    <p>Plusieurs cas d'un nouveau variant du Covid-19 ont été détectés en France. Le point sur la situation.</p>

                    <h5>Qu'est-ce que ce variant ?</h5>
                    <p>Le NL.81.5, nouveau sous-variant d'Omicron, se montre plus transmissible mais reste contrôlable.</p>

                    <h5>Mesures recommandées</h5>
                    <p>L'OMS rassure : les vaccins actuels restent efficaces contre les formes graves. Restez informés.</p>

                    <a href="#" class="btn-article">Lire l'article complet</a>
                </div>
                <img src="Images/coronavirus.jpg" alt="Covid Virus" />
            </div>
        </section>
    </main>

    <footer>
        <div class="container footer-3cols">
            <div class="footer-column">
                <h4>Contact</h4>
                <p><img src="Images/mail.png" alt="Mail" class="icon"> contact@medicare.fr</p>
                <p><img src="Images/telephone.png" alt="Téléphone" class="icon"> +33 1 23 45 67 89</p>
                <a href="mailto:contact@medicare.fr" class="btn-contact">
                    <img src="Images/envoyer.png" alt="Envoyer" class="icon"> Nous contacter
                </a>
            </div>
            <div class="footer-column">
                <h4>Adresse</h4>
                <p>123 Rue de la Santé<br>75013 Paris, France</p>
            </div>
            <div class="footer-column">
                <h4>Plan</h4>
                <div class="map-container">
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2624.9993690102726!2d2.355317915674823!3d48.82184327928473!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47e671ce71f11531%3A0xb1bfbf48da5185e7!2sH%C3%B4pital%20de%20la%20Piti%C3%A9-Salp%C3%AAtriere!5e0!3m2!1sfr!2sfr!4v1620219452012!5m2!1sfr!2sfr"
                        width="100%" height="200" style="border:0;" allowfullscreen="" loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
            </div>
        </div>
    </footer>
<script>
// variables
let slide = document.querySelectorAll(".slides");
let counter = 0;
const totalLength = slide.length;

// position initiale des images
slide.forEach((img, index) => {
  img.style.left = `${index * 100}%`;
});

// fonctions de navigation
function goBack() {
  counter = (counter - 1 + totalLength) % totalLength;
  updateSlide();
}

function goNext() {
  counter = (counter + 1) % totalLength;
  updateSlide();
}

function updateSlide() {
  slide.forEach((img) => {
    img.style.transform = `translateX(-${counter * 100}%)`;
  });
}

// défilement automatique
setInterval(goNext, 5000); // toutes les 1.5 secondes
</script>


</body>

</html>
