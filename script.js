const carousels = document.querySelectorAll('[data-carousel]');
let scrollVelocity = 0;
let raf = null;

carousels.forEach(carousel => {
  const track = carousel.querySelector('.carousel-track');
  const cards = Array.from(track.children);

  // Dupliquer les cartes pour effet infini
  for (let i = 0; i < 2; i++) {
    cards.forEach(card => track.appendChild(card.cloneNode(true)));
  }

  carousel.scrollLeft = track.scrollWidth / 3;

  carousel.addEventListener('wheel', e => {
    e.preventDefault();
    scrollVelocity += e.deltaY * 0.1;
    if (!raf) requestAnimationFrame(() => smoothScroll(carousel));
  });

  carousel.addEventListener('scroll', () => {
    const scrollLeft = carousel.scrollLeft;
    const scrollWidth = track.scrollWidth;
    const third = scrollWidth / 3;

    if (scrollLeft < third / 2) {
      carousel.scrollLeft += third;
    } else if (scrollLeft > third * 1.5) {
      carousel.scrollLeft -= third;
    }
  });
});

function smoothScroll(carousel) {
  carousel.scrollLeft += scrollVelocity;
  scrollVelocity *= 0.9;
  if (Math.abs(scrollVelocity) > 0.5) {
    raf = requestAnimationFrame(() => smoothScroll(carousel));
  } else {
    raf = null;
  }
}

const buttons = document.querySelectorAll('.carousel-buttons button');
const titleEl = document.getElementById('carouselTitle');

const titleMap = {
  generaliste: 'Médecin généraliste',
  specialiste: 'Médecin spécialisé',
  laboratoire: 'Laboratoire de santé',
};

// Fonction pour changer de carrousel avec animation
function changeCarousel(type) {
  // Mise à jour des boutons
  buttons.forEach(b => b.classList.remove('active'));
  document.querySelector(`[data-carousel-type="${type}"]`).classList.add('active');

  // Animation du titre
  titleEl.style.opacity = 0;

  // Masquer tous les carrousels avec animation
  carousels.forEach(c => {
    c.classList.add('fade-out');
    setTimeout(() => {
      c.classList.add('hidden');
      c.classList.remove('fade-out');
    }, 400);
  });

  // Afficher le bon carrousel après le délai
  setTimeout(() => {
    const selected = document.querySelector(`[data-type="${type}"]`);
    selected.classList.remove('hidden');
    selected.classList.add('fade-in');

    // Changement du titre après fondu
    titleEl.textContent = titleMap[type];
    titleEl.style.opacity = 1;

    // Nettoyage de la classe fade-in après l'animation
    setTimeout(() => {
      selected.classList.remove('fade-in');
    }, 400);
  }, 400);
}

// Écouteurs sur les boutons
buttons.forEach(btn => {
  btn.addEventListener('click', () => {
    const type = btn.dataset.carouselType;
    changeCarousel(type);
  });
});


document.querySelectorAll('.card').forEach(card => {
  card.addEventListener('click', () => {
    const nom = card.dataset.nom;
    const prenom = card.dataset.prenom;
    const specialite = card.dataset.specialite;
    const imgSrc = card.dataset.img;

    console.log(">>> Carte cliquée");
    console.log("Nom:", nom, "Prénom:", prenom, "Spécialité:", specialite, "Image:", imgSrc);

    const popup = document.getElementById('profilePopup');
    popup.classList.remove('hidden');
    setTimeout(() => popup.classList.add('show'), 10);

    document.getElementById('popupImg').src = imgSrc;
    document.getElementById('popupNomPrenom').textContent = `${prenom} ${nom}`;
    document.getElementById('popupSpecialite').textContent = specialite;

    // Efface toutes les barres
    document.querySelectorAll('.popup-day').forEach(el => {
      console.log(`Réinitialisation de : ${el.dataset.day}`);
      el.innerHTML = el.dataset.day;
    });

    ['L', 'M', 'MM', 'J', 'V', 'S'].forEach(day => {
      const horaires = card.dataset[day.toLowerCase()];
      console.log(`Jour : ${day} - Horaires :`, horaires);
      if (!horaires) return;

      const dayEl = document.querySelector(`.popup-day[data-day="${day}"]`);
      if (!dayEl) {
        console.warn(`⚠️ Élément introuvable pour le jour : ${day}`);
        return;
      }

      dayEl.innerHTML = day;

      const plages = horaires.split(',');
      plages.forEach(plage => {
        const [start, end] = plage.split('-').map(Number);
        if (isNaN(start) || isNaN(end)) {
          console.warn(`⛔ Mauvais format d'horaire pour ${plage}`);
          return;
        }

        const plageTotale = 12; // de 8h à 20h
        const width = ((end - start) / plageTotale) * 100;
        const left = ((start - 8) / plageTotale) * 100;

        console.log(`🟦 Création d'une barre : start=${start}, end=${end}, left=${left}%, width=${width}%`);

        const span = document.createElement('span');
        span.style.left = `${left}%`;
        span.style.width = `${width}%`;
        span.style.position = 'absolute';
        span.style.top = '50%';
        span.style.height = '24px';
        span.style.backgroundColor = '#007BFF';
        span.style.borderRadius = '8px';
        span.style.transform = 'translateY(-50%)';

        dayEl.style.position = 'relative'; // important pour que les barres soient bien positionnées
        dayEl.appendChild(span);
      });
    });

  });
});




// Fermer le popup
document.querySelector('.close').addEventListener('click', () => {
  const popup = document.getElementById('profilePopup');
  popup.classList.remove('show');
  setTimeout(() => popup.classList.add('hidden'), 300);
});

// Fermer si clic en dehors
document.getElementById('profilePopup').addEventListener('click', e => {
  if (e.target.id === 'profilePopup') {
    const popup = document.getElementById('profilePopup');
    popup.classList.remove('show');
    setTimeout(() => popup.classList.add('hidden'), 300);
  }
});
