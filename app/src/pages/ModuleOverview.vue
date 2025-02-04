<template>
  <q-page padding class="module-overview-background">
    <div class="back-button">
      <q-btn flat dense icon="arrow_back" @click="goBack" class="back-btn" />
    </div>

    <div class="header">
      <div class="text-h4 text-center text-white">Module im Kurs</div>
    </div>

    <div class="row q-col-gutter-md">
      <div v-for="module in modules" :key="module.id" class="col-12 col-md-6">
        <q-card class="module-card cursor-pointer" @click="goToModule(module.id)">
          <!-- Dynamisches Hintergrundbild -->
          <div class="module-image" :style="{ backgroundImage: `url(${getModuleImage(module.title)})` }">
            <div class="overlay">
            </div>
          </div>

          <q-card-section class="card-content">
            <div class="text-h6">{{ module.title }}</div>
            <div class="text-subtitle2">{{ module.description }}</div>
          </q-card-section>
        </q-card>
      </div>
    </div>
  </q-page>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { api } from 'boot/axios';

const route = useRoute();
const router = useRouter();
const modules = ref([]);

const fetchModules = async () => {
  try {
    const response = await api.get(`/courses/${route.params.id}/modules`);
    modules.value = response.data.modules;
  } catch (error) {
    console.error('Fehler beim Abrufen der Module:', error);
  }
};

const goBack = () => {
  router.push(`/courses/${route.params.id}`);
};

const goToModule = (moduleId) => {
  router.push(`/modules/${moduleId}`);
};

// Dynamisches Modulbild
// const getModuleImage = (title) => {
//   const images = {
//     "Einführung in Stress": "stress.avif",
//     "Praktische Stressbewältigung": "/images/modules/stress_management.png"
//   };
//   return images[title] || "default.jpg";
// };
const getModuleImage = (title) => {
  if (!title) return "/default.jpg"; // Falls title undefined ist, Standardbild verwenden

  const images = {
    "Einführung in Stress": "/stress.avif",
    "Praktische Stressbewältigung" :"/stress_managment.jpg"
  };

  // "Modul X: ..." entfernen, falls vorhanden
  const cleanTitle = title.replace(/Modul \d+: /, "").trim();

  return images[cleanTitle] || "/default.jpg";
};

onMounted(() => {
  fetchModules();
});
</script>

<style scoped>
.module-overview-background {
  background-color: var(--primary);
  min-height: 100vh;
  padding: 20px;
}

.back-btn {
  position: absolute;
  top: 15px;
  left: 15px;
  color: white;
}

.header {
  margin-bottom: 20px;
}

.module-card {
  border-radius: 15px;
  overflow: hidden;
  transition: transform 0.3s, box-shadow 0.3s;
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
}

.module-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
}

.module-image {
  height: 180px;
  background-size: cover;
  background-position: center;
  position: relative;
}

.overlay {
  position: absolute;
  inset: 0;
  background: rgba(0, 0, 0, 0.3);
  display: flex;
  align-items: center;
  justify-content: center;
}

.play-icon {
  font-size: 50px;
  color: white;
}

.card-content {
  padding: 15px;
  text-align: center;
}
</style>
