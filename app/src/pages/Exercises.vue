<template>
  <q-page class="q-pa-md flex flex-center">
    <q-btn flat icon="arrow_back_ios" no-caps @click="router.go(-1)" />
    <div class="intro-container">
      <q-carousel
        v-model="slide"
        transition-prev="slide-right"
        transition-next="slide-left"
        animated
        swipeable
        control-color="white"
        navigation
        height="400px"
      >
        <!-- Slide 1: Einleitung -->
        <q-carousel-slide name="1" class="column flex-center">
          <img src="/src/assets/bild1.jpg" class="intro-image" alt="Übungsübersicht">
          <div class="text-h5 q-mt-md">Willkommen zu den Übungen</div>
          <div class="text-subtitle1 q-mt-sm text-center">
            Diese Übungen helfen dir, deine Emotionen zu regulieren, Ängste abzubauen und innere Ruhe zu finden.
          </div>
        </q-carousel-slide>

        <!-- Slide 2: Vorteile der Übungen -->
        <q-carousel-slide name="2" class="column flex-center">
          <img src="/src/assets/bild2.jpg" class="intro-image" alt="Achtsamkeitsübungen">
          <div class="text-h5 q-mt-md">Warum Übungen wichtig sind</div>
          <div class="text-subtitle1 q-mt-sm text-center">
            Regelmäßige Achtsamkeitsübungen können dein Wohlbefinden steigern und dir helfen, mit schwierigen Momenten umzugehen.
          </div>
        </q-carousel-slide>

        <!-- Slide 3: Anwendungsweise -->
        <q-carousel-slide name="3" class="column flex-center">
          <img src="/src/assets/bild3.jpg" class="intro-image" alt="Übungsanleitung">
          <div class="text-h5 q-mt-md">So funktioniert es</div>
          <div class="text-subtitle1 q-mt-sm text-center">
            Wähle eine Kategorie, starte eine Übung und folge den Anweisungen. Deine Fortschritte werden gespeichert.
          </div>
        </q-carousel-slide>
      </q-carousel>

      <!-- Start-Button -->
      <q-btn
        label="Loslegen"
        color="primary"
        class="q-mt-lg full-width"
        size="lg"
        @click="startExercises"
      />
    </div>
  </q-page>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';

const router = useRouter();
const slide = ref('1');

const startExercises = () => {
  router.push('/exercise-categories'); // Führe zur Kategorie-Seite
};
</script>

<style scoped>
.intro-container {
  max-width: 600px;
  text-align: center;
}

.intro-image {
  max-width: 250px;
  height: auto;
}

.text-h5 {
  font-weight: bold;
}

.q-btn {
  font-size: 1.2em;
}
</style>
