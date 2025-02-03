<template>
  <q-page class="q-pa-md">
    <div class="text-h5 text-center q-mb-md">Gespeicherte Sofort-Hilfe</div>

    <div v-if="tips.length === 0" class="text-center q-mt-lg">
      <q-icon name="bookmark_border" size="xl" color="grey-6" />
      <div class="text-grey-6 q-mt-sm">Du hast noch keine Tipps gespeichert.</div>
    </div>

    <div class="row q-col-gutter-md">
      <q-card
        v-for="tip in tips"
        :key="tip.id"
        class="tip-card col-12 col-md-5"
      >
        <q-img :src="getImage(tip.topic)" class="card-image" />

        <q-card-section>
          <div class="text-h6">{{ tip.topic }}</div>
          <q-item-label caption>{{ tip.tip }}</q-item-label>
        </q-card-section>

        <q-card-actions align="between">
          <q-btn flat icon="delete" color="negative" @click="deleteTip(tip.id)" />
          <q-rating v-model="tip.rating" max="5" @update:model-value="rateTip(tip.id, tip.rating)" />
        </q-card-actions>
      </q-card>
    </div>
  </q-page>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { api } from "boot/axios";

const tips = ref([]);

const fetchTips = async () => {
  try {
    const response = await api.get("/saved-tips");
    tips.value = response.data;
  } catch (error) {
    console.error("Fehler beim Laden der Tipps:", error);
  }
};

const deleteTip = async (id) => {
  await api.delete(`/saved-tips/${id}`);
  tips.value = tips.value.filter(t => t.id !== id);
};

const rateTip = async (id, rating) => {
  await api.post(`/saved-tips/${id}/rate`, { rating });
};

// Funktion, um das richtige Bild basierend auf dem Thema auszuwählen
const getImage = (topic) => {
  const images = {
    "Angst": "/angst.jpg",
    "Panik": "/panik.jpg",
    "Stress": "/stress.avif",
    "Einsamkeit": "/einsamkeit.jpg",
    "Selbstzweifel": "/selbstzweifel.jpg"
  };
  return images[topic] || "/default.jpg";
};

onMounted(fetchTips);
</script>

<style scoped>
.tip-card {
  border-radius: 12px;
  overflow: hidden;
  transition: transform 0.2s, box-shadow 0.2s;
  margin-bottom: 16px;
}

.tip-card:hover {
  transform: scale(1.03);
  box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
}

.card-image {
  height: 140px;
  object-fit: cover;
}
</style>
