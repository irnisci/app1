<template>
  <q-layout view="lHh Lpr lFf">
    <q-header >
      <q-toolbar>
        <q-btn flat dense round icon="menu" aria-label="Menu" @click="toggleLeftDrawer" />

        <q-toolbar-title>
          Ki-Pocket Psyhologe
        </q-toolbar-title>


      </q-toolbar>
    </q-header>

    <q-drawer v-model="leftDrawerOpen" show-if-above bordered>
      <!-- <q-list>
        <q-item-label
          header
        >
          Essential Links
        </q-item-label>

        <EssentialLink
          v-for="link in essentialLinks"
          :key="link.title"
          v-bind="link"
        />
      </q-list> -->
      <!-- Konto-Tab -->
      <q-item clickable v-ripple @click="router.push('/account')">
        <q-item-section avatar>
          <q-icon name="account_circle" />
        </q-item-section>
        <q-item-section>
          <q-item-label>Konto</q-item-label>
        </q-item-section>
      </q-item>
      <!--settings-->
      <q-item clickable v-ripple @click="router.push('/settings')">
        <q-item-section avatar>
          <q-icon name="palette" />
        </q-item-section>
        <q-item-section>
          <q-item-label>Einstellungen</q-item-label>
        </q-item-section>
      </q-item>
<!--gespeicherte tipüs-->
      <q-item clickable v-ripple to="/saved-tips">
    <q-item-section avatar>
      <q-icon name="bookmark" />
    </q-item-section>
    <q-item-section>Gespeicherte Tipps</q-item-section>
  </q-item>

    </q-drawer>

    <q-page-container>
      <router-view />
    </q-page-container>

    <!-- Stimmungs-Check-in Pop-up -->
    <q-dialog v-model="showMoodPopup">
      <q-card>
        <q-card-section>
          <div class="text-h6">Wie fühlst du dich heute?</div>
        </q-card-section>

        <q-card-section class="q-gutter-sm">
          <q-btn v-for="mood in moods" :key="mood.label" :label="mood.label" :icon="mood.icon" color="primary"
            @click="submitMood(mood.label)" />
        </q-card-section>
      </q-card>
    </q-dialog>
  </q-layout>
</template>

<script setup>
import { defineComponent, ref, onMounted } from 'vue'
import { api } from 'boot/axios'
import { useRouter } from 'vue-router'

const router = useRouter()
const leftDrawerOpen = ref(false)
const showMoodPopup = ref(false);
const toggleLeftDrawer = () => {
  leftDrawerOpen.value = !leftDrawerOpen.value
}

const moods = ref([
  { label: "Glücklich", icon: "sentiment_very_satisfied" },
  { label: "Neutral", icon: "sentiment_neutral" },
  { label: "Traurig", icon: "sentiment_dissatisfied" }
]);

// const checkLastMood = async () => {
//   try {
//     const response = await api.get('/mood-checkin/last');
//     if (!response.data || new Date() - new Date(response.data.checked_at) > 86400000) {
//       showMoodPopup.value = true;
//     }
//   } catch (error) {
//     showMoodPopup.value = true; // Falls kein Eintrag vorhanden ist
//   }
// };

const checkLastMood = async () => {
  try {
    const response = await api.get('/mood-checkin/last');

    if (response.data && response.data.checked_at) {
      const lastCheckinTime = new Date(response.data.checked_at);
      const now = new Date();
      const hoursSinceLastCheckin = (now - lastCheckinTime) / (1000 * 60 * 60);

      if (hoursSinceLastCheckin >= 24) {
        showMoodPopup.value = true;
      }
    } else {
      showMoodPopup.value = true;
    }
  } catch (error) {
    console.error("Fehler beim Abrufen des letzten Check-ins:", error);
    showMoodPopup.value = true; // Falls kein Eintrag vorhanden ist
  }
};

const submitMood = async (mood) => {
  await api.post('/mood-checkin', { mood });
  showMoodPopup.value = false;
};


onMounted(() => {
  checkLastMood();
});
</script>
