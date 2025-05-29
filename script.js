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


/*

// Pop up des cartes
// Sélection des éléments du popup
const popup = document.getElementById('profilePopup');
const popupImg = document.getElementById('popupImage');
const popupName = document.getElementById('popupName');
const popupDesc = document.getElementById('popupDesc');
const closeBtn = document.getElementById('closePopup');

// Fonction d'ouverture
function openProfile(image, name, desc) {
  popupImg.src = image;
  popupName.textContent = name;
  popupDesc.textContent = desc;
  popup.classList.remove('hidden');
}

// Fonction de fermeture
closeBtn.addEventListener('click', () => {
  popup.classList.add('hidden');
});

// Attacher les événements aux cartes
document.querySelectorAll('.card').forEach(card => {
  card.addEventListener('click', () => {
    const image = card.querySelector('img').src;
    const name = card.querySelector('.card-text')?.textContent || 'Profil';
    const desc = card.dataset.description || 'Pas de description fournie.';
    openProfile(image, name, desc);
  });
});

*/

// Fermer le popup en cliquant en dehors (corrigé)
document.getElementById('popup-overlay').addEventListener('click', () => {
  document.getElementById('profile-popup').classList.add('hidden');
  document.getElementById('popup-overlay').classList.add('hidden');
});

// Empêcher la fermeture si on clique DANS le popup
document.getElementById('profile-popup').addEventListener('click', (e) => {
  e.stopPropagation();
});

// Fonction d'ouverture du profil
function openProfile(image, name, desc, specialty, available, calendarData) {
  popupImg.src = image;
  popupName.textContent = name;
  popupSpecialty.textContent = specialty || 'Spécialité inconnue';
  popupAvailability.textContent = available ? 'Disponible' : 'Absent';
  popupDesc.textContent = desc;

  // Mise à jour des créneaux de calendrier
  updateCalendar(calendarData);

  popup.classList.remove('hidden');
}

// Mettre à jour les créneaux du calendrier (dummy)
function updateCalendar(data) {
  const days = ['L', 'M', 'M', 'J', 'V', 'S'];
  const calendar = document.getElementById('popupCalendar');
  calendar.innerHTML = '';

  days.forEach((day, index) => {
    const row = document.createElement('div');
    row.className = 'calendar-row';
    row.innerHTML = `
      <div class="day-label">${day}</div>
      <div class="slots">
        <div class="slot ${index === 0 ? 'available' : ''}" style="width: 30%;"></div>
      </div>
    `;
    calendar.appendChild(row);
  });
}

// Ciblage DOM (à mettre au début de script.js ou selon ton ordre)
const popup = document.getElementById('profilePopup');
const popupImg = document.getElementById('popupImage');
const popupName = document.getElementById('popupName');
const popupDesc = document.getElementById('popupDesc');
const popupSpecialty = document.getElementById('popupSpecialty');
const popupAvailability = document.getElementById('popupAvailability');
const closeBtn = document.getElementById('closePopup');

closeBtn.addEventListener('click', () => {
  popup.classList.add('hidden');
});

// Cartes (doivent avoir .carousel-card et des data attribs)
document.querySelectorAll('.card').forEach(card => {
  card.addEventListener('click', () => {
    const image = card.querySelector('img').src;
    const name = card.querySelector('.card-text')?.textContent || 'Nom';
    const specialty = card.dataset.specialty || 'Spécialité';
    const desc = card.dataset.description || 'Description';
    const available = card.dataset.available === 'true';
    openProfile(image, name, desc, specialty, available);
  });
});

// Fonction pour afficher un profil
function openProfile(nom, prenom, specialite, disponibilite) {
  const popup = document.getElementById('profile-popup');
  const overlay = document.getElementById('popup-overlay');

  popup.querySelector('.popup-name').textContent = `${nom} ${prenom}`;
  popup.querySelector('.popup-specialite').textContent = specialite;
  popup.querySelector('.popup-dispo').textContent = disponibilite;

  popup.classList.remove('hidden');
  overlay.classList.remove('hidden');
}

document.getElementById('popup-overlay').addEventListener('click', () => {
  document.getElementById('profile-popup').classList.add('hidden');
  document.getElementById('popup-overlay').classList.add('hidden');
});
