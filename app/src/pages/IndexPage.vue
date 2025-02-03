<template>
  <q-page padding class="startpage-background">
    <div class="text-h4 text-center q-mb-md text-white">Willkommen zurück!</div>

    <!-- Kacheln für Funktionen -->
    <div class="row q-col-gutter-md">
      <!-- Tagebuch -->
      <div class="col-12 col-md-6">
        <q-card class="cursor-pointer custom-card" @click="goToJournal">
          <q-card-section class="card-content">
            <q-icon name="edit" size="lg" class="q-mb-sm" />
            <div class="text-h6">Tagebuch</div>
            <div class="text-subtitle2">Schreibe deine Gedanken auf.</div>
          </q-card-section>
        </q-card>
      </div>
         <!-- Sofort Hilfe -->
         <div class="col-12 col-md-6">
        <q-card class="cursor-pointer custom-card" @click="goToInstantHelp">
          <q-card-section class="card-content">
            <q-icon name="sos" size="lg" class="q-mb-sm" />
            <div class="text-h6">Sofort Hilfe</div>
            <div class="text-subtitle2">Sofort Hilfe</div>
          </q-card-section>
        </q-card>
      </div>

      <!-- Übungen -->
      <div class="col-12 col-md-6">
        <q-card class="cursor-pointer custom-card" @click="goToExercises">
          <q-card-section class="card-content">
            <q-icon name="psychology" size="lg" class="q-mb-sm" />
            <div class="text-h6">Übungen</div>
            <div class="text-subtitle2">Trainiere dein mentales Wohlbefinden</div>
          </q-card-section>
        </q-card>
      </div>


      <!-- Atemübungen -->
      <div class="col-12 col-md-6">
        <q-card class="cursor-pointer custom-card" @click="goToBreathing">
          <q-card-section class="card-content">
            <q-icon name="self_improvement" size="lg" class="q-mb-sm" />
            <div class="text-h6">Atemübungen</div>
            <div class="text-subtitle2">Entspanne dich mit geführten Übungen.</div>
          </q-card-section>
        </q-card>
      </div>

      <!-- Visualisierte Beruhigungs-Techniken -->
      <div class="col-12 col-md-6">
        <q-card class="cursor-pointer custom-card" @click="goToVisual">
          <q-card-section class="card-content">
            <q-icon name="visibility" size="lg" class="q-mb-sm" />
            <div class="text-h6">Visualisierung</div>
            <div class="text-subtitle2">Fokus-Übungen & Farbtherapie</div>
          </q-card-section>
        </q-card>
      </div>

      <!-- Notfallkontakte -->
      <div class="col-12 col-md-6">
        <q-card class="cursor-pointer custom-card" @click="goToContacts">
          <q-card-section class="card-content">
            <q-icon name="contacts" size="lg" class="q-mb-sm" />
            <div class="text-h6">Notfallkontakte</div>
            <div class="text-subtitle2">Wichtige Kontakte in Krisensituationen.</div>
          </q-card-section>
        </q-card>
      </div>

      <!-- Entspannungsmusik -->
      <div class="col-12 col-md-6">
        <q-card class="cursor-pointer custom-card" @click="goToMusic">
          <q-card-section class="card-content">
            <q-icon name="music_note" size="lg" class="q-mb-sm" />
            <div class="text-h6">Entspannungsmusik</div>
            <div class="text-subtitle2">Beruhigende Klänge zum Abschalten.</div>
          </q-card-section>
        </q-card>
      </div>
    </div>

    <!-- Logout-Button -->
    <div class="text-center q-mt-lg">
      <q-btn label="Logout" color="white" flat @click="onLogout" />
    </div>
  </q-page>
</template>

<script setup>
import { useRouter } from 'vue-router';
import { useQuasar } from 'quasar';

const $q = useQuasar();
const router = useRouter();

const goToJournal = () => {
  router.push('/journal');
};

const goToInstantHelp = () => {
  router.push('/instant-help');
};

const goToBreathing = () => {
  router.push('/breathing');
};

const goToContacts = () => {
  router.push('/contacts');
};

const goToMusic = () => {
  router.push('/music');
};

const goToVisual = () => {
  router.push('/visual');
};

const goToExercises = () => {
  router.push('/exercises');
};

const onLogout = () => {
  localStorage.removeItem('auth_token');
  $q.notify({
    type: 'positive',
    message: 'Erfolgreich abgemeldet!',
    position: 'top'
  });
  router.push('/login');
};
</script>

<style scoped>
.startpage-background {
  /* background: linear-gradient(135deg, #5CACC4, #FC9D9A); */
  background-color: var(--primary);
  min-height: 100vh;
  padding: 20px;
}

.custom-card {
  border-radius: 15px;
  background: rgba(255, 255, 255, 0.2);
  backdrop-filter: blur(10px);
  border: 1px solid rgba(255, 255, 255, 0.3);
  transition: transform 0.3s, box-shadow 0.3s;
}

.custom-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
}

.card-content {
  text-align: center;
  color: white;
}

.q-icon {
  color: white;
}

.text-h6 {
  font-weight: 500;
}

.text-subtitle2 {
  opacity: 0.8;
}
</style>
