<template>
  <q-page class="q-pa-md">
    <q-btn flat round dense icon="arrow_back" @click="router.go(-1)" />
    <div class="text-h5 text-center q-mb-md">Wähle eine Kategorie</div>
    <div class="text-subtitle1 text-center q-mb-lg">
      Hier kannst du eine Kategorie auswählen, um passende Übungen zu finden.
    </div>
    <!-- Kategorien-Liste -->
    <q-list separator bordered class="bg-white rounded-borders">
      <q-item
        v-for="category in categories"
        :key="category.id"
        clickable
        v-ripple
        @click="goToExercises(category.id)"
      >
        <q-item-section avatar>
          <q-icon :name="category.icon" color="primary" size="lg" />
        </q-item-section>

        <q-item-section>
          <q-item-label>{{ category.name }}</q-item-label>
          <q-item-label caption>{{ category.exercise_count }} Übungen</q-item-label>
        </q-item-section>

        <q-item-section side>
          <q-icon name="chevron_right" />
        </q-item-section>
      </q-item>
    </q-list>
  </q-page>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import { api } from 'boot/axios';
const router = useRouter();
const categories = ref([]);

const fetchCategories = async () => {
  try {
    const response = await api.get('/categories');
    categories.value = response.data;
  } catch (error) {
    console.error('Fehler beim Laden der Kategorien:', error);
  }
};

const goToExercises = (categoryId) => {
  router.push(`/category/${categoryId}/exercises`);
};

onMounted(fetchCategories);
</script>

<style scoped>
.text-h5 {
  font-weight: bold;
}

.q-list {
  max-width: 600px;
  margin: auto;
}
</style>
