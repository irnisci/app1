<template>
  <q-page padding class="relax-music-background">
    <div ref="dropContainer" class="animation-container"></div>
    <q-btn flat icon="arrow_back_ios" label="Zurück" no-caps @click="router.go(-1)"/>
    <!-- Header -->
    <div class="text-h4 text-center q-mb-md text-white">Entspannungsmusik</div>

    <!-- Sound-Auswahl -->
    <div class="row justify-center q-mb-md">
      <q-select
        v-model="selectedSound"
        :options="sounds"
        label="Wähle einen Sound"
        outlined
        class="col-12 col-md-6"
      />
    </div>

    <!-- Audio-Player -->
    <div class="row justify-center q-mt-lg">
      <div class="col-12 col-md-6 text-center">
        <!-- Player -->
        <audio ref="audioPlayer" :src="selectedSound ? selectedSound.url : ''" controls class="full-width"></audio>

        <!-- Timer -->
        <div class="q-mt-md">
          <q-input
            v-model.number="timerDuration"
            type="number"
            label="Timer (in Minuten)"
            outlined
            class="q-mb-md"
          />
          <q-btn
            label="Timer starten"
            color="primary"
            @click="startTimer"
          />
        </div>
      </div>
    </div>
    <div class="row justify-center q-mt-md">
    <q-btn
      v-if="!isFavorite"
      label="Zu Favoriten hinzufügen"
      color="positive"
      @click="addToFavorites"
    />
    <q-btn
      v-else
      label="Aus Favoriten entfernen"
      color="negative"
      @click="removeFromFavorites"
    />
  </div>
  </q-page>
</template>

<script setup>
import { ref, watch,computed,onMounted ,onUnmounted} from 'vue';
import { useQuasar } from 'quasar';
import { useRouter } from 'vue-router';
import gsap from 'gsap';

const $q = useQuasar();
const audioPlayer = ref(null);
const selectedSound = ref(null);
const timerDuration = ref(0);
const isTimerRunning = ref(false);
let timerInterval = null;
const favorites = ref([]);
const router = useRouter()
const remainingTime = ref(0);


// Verfügbare Sounds
const sounds = [
{ label: 'Regen', url: '/sounds/rain.mp3' },
  { label: 'Meeresrauschen', url: '/sounds/ocean.mp3' },
  { label: 'Waldgeräusche', url: '/sounds/forest.mp3' },
  { label: 'Meditationsmusik', url: '/sounds/meditation.mp3' },
];



const startTimer = () => {
  if (timerDuration.value > 0 && !isTimerRunning.value) {
    isTimerRunning.value = true;
    remainingTime.value = timerDuration.value * 60; // Sekunden

    console.log(`Timer gestartet für ${timerDuration.value} Minuten.`);

    // Musik in Dauerschleife laufen lassen
    if (audioPlayer.value) {
      audioPlayer.value.loop = true; // Musik in Endlosschleife abspielen
      audioPlayer.value.play();
    }

    // Timer-Countdown starten
    timerInterval = setInterval(() => {
      remainingTime.value--;

      if (remainingTime.value <= 0) {
        stopMusicAndTimer();
      }
    }, 1000);
  } else {
    useQuasar().notify({
      type: 'warning',
      message: 'Bitte eine gültige Zeit eingeben!',
      position: 'top'
    });
  }
};

const stopMusicAndTimer = () => {
  if (audioPlayer.value) {
    audioPlayer.value.loop = false; // Schleife deaktivieren
    audioPlayer.value.pause();
    audioPlayer.value.currentTime = 0; // Zurückspulen
  }

  clearInterval(timerInterval);
  isTimerRunning.value = false;

  $q.notify({
    type: 'info',
    message: 'Timer abgelaufen. Musik wurde gestoppt.',
    position: 'top'
  });

  console.log("Timer abgelaufen, Musik gestoppt.");
};

const stopTimer = () => {
  stopMusicAndTimer();

  useQuasar().notify({
    type: 'negative',
    message: 'Timer gestoppt.',
    position: 'top'
  });

  console.log("Timer gestoppt.");
};

watch(selectedSound, (newSound) => {
  if (newSound && audioPlayer.value) {
    audioPlayer.value.pause();
    audioPlayer.value.load();
    audioPlayer.value.play();
  }
});

const isFavorite = computed(() => {
  return favorites.value.some(fav => fav.sound_name === selectedSound.value.label);
});

// Füge einen Sound zu den Favoriten hinzu
const addToFavorites = async () => {
  try {
    await api.post('/favorite-sounds', {
      sound_name: selectedSound.value.label,
    });
    $q.notify({
      type: 'positive',
      message: 'Sound zu Favoriten hinzugefügt!',
      position: 'top'
    });
  } catch (error) {
    $q.notify({
      type: 'negative',
      message: 'Fehler beim Hinzufügen des Sounds.',
      position: 'top'
    });
  }
};

// Entferne einen Sound aus den Favoriten
const removeFromFavorites = async () => {
  try {
    const favorite = favorites.value.find(fav => fav.sound_name === selectedSound.value.label);
    await api.delete(`/favorite-sounds/${favorite.id}`);
    $q.notify({
      type: 'positive',
      message: 'Sound aus Favoriten entfernt!',
      position: 'top'
    });
  } catch (error) {
    $q.notify({
      type: 'negative',
      message: 'Fehler beim Entfernen des Sounds.',
      position: 'top'
    });
  }
};

const dropContainer = ref(null);
let dropInterval = null;

// 🌊 Tropfen erzeugen und animieren
const createDrop = () => {
  const drop = document.createElement('div');
  drop.classList.add('drop');
  dropContainer.value.appendChild(drop);

  const startX = Math.random() * window.innerWidth; // Zufällige Position
  const startSize = Math.random() * 15 + 10; // Zufällige Größe
  const duration = Math.random() * 2 + 3; // Geschwindigkeit

  gsap.fromTo(
    drop,
    { x: startX, y: -50, width: startSize, height: startSize, opacity: 1 },
    { y: window.innerHeight, opacity: 0, duration, ease: 'power2.out', onComplete: () => drop.remove() }
  );
};

// 🌊 Animation starten, wenn Musik abgespielt wird
const startAnimation = () => {
  dropInterval = setInterval(createDrop, 500);
};

// 🌊 Animation stoppen, wenn Musik pausiert wird
const stopAnimation = () => {
  clearInterval(dropInterval);
};

watch(audioPlayer, (newAudio) => {
  if (newAudio) {
    newAudio.addEventListener('play', startAnimation);
    newAudio.addEventListener('pause', stopAnimation);
    newAudio.addEventListener('ended', stopAnimation);
  }
});

onMounted(()=>{
  dropContainer.value.innerHTML = '';
})
onUnmounted(() => {
  stopAnimation();
});
</script>

<style scoped>
.relax-music-background {
  /* background: linear-gradient(135deg, #6a82fb, #fc5c7d); */
  background: rgb(238,174,202);
  background: linear-gradient(90deg, rgba(238,174,202,1) 0%, rgba(148,187,233,1) 100%);
  min-height: 100vh;
}

audio {
  border-radius: 10px;
  background: rgba(255, 255, 255, 0.2);
  backdrop-filter: blur(10px);
  padding: 10px;
}

.animation-container {
  position: fixed;
  top: 0;
  left: 0;
  width: 100vw;
  height: 100vh;
  pointer-events: none; /* Keine Blockierung von Klicks */
  overflow: hidden;
  z-index: 0;
}

.drop {
  position: absolute;
  background: radial-gradient(circle, rgba(0, 174, 255, 1) 0%, rgba(255, 255, 255, 0) 70%);
  border-radius: 50%;
  pointer-events: none;
  filter: blur(4px);
}
</style>
