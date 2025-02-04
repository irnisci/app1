<template>
  <q-page padding class="course-detail-background">
    <div class="back-button">
      <q-btn flat dense icon="arrow_back" @click="goBack" />
    </div>

    <div class="image-container">
      <img v-if="!isLoading" :src="getCourseImage(course?.title)" class="course-image" alt="Kurs Illustration" />
    </div>

    <q-card class="course-card">
      <q-card-section>
        <div class="text-h5">
          <q-skeleton v-if="isLoading" type="text" width="60%" />
          <span v-else>{{ course?.title }}</span>
        </div>
        <div class="text-subtitle2">
          <q-skeleton v-if="isLoading" type="text" width="80%" />
          <span v-else>{{ course?.description }}</span>
        </div>
      </q-card-section>

      <!-- Module & Lektionen -->
      <q-card-section>
        <div class="text-bold">Module & Lektionen</div>
        <q-list separator>
          <q-expansion-item v-for="module in course?.modules" :key="module.id" :label="module.title" expand-separator>
            <q-list>
              <q-item v-for="lesson in module.lessons" :key="lesson.id">
                <q-item-section>
                  <q-item-label>{{ lesson.title }}</q-item-label>
                </q-item-section>
              </q-item>
            </q-list>
          </q-expansion-item>
          <q-skeleton v-if="isLoading" type="rect" width="100%" height="100px" class="skeleton-list" />
        </q-list>
      </q-card-section>

      <q-card-section class="button-section">
        <q-btn rounded outline v-if="!isLoading" label="Jetzt starten" color="primary" @click="startCourse" />
        <q-skeleton v-else type="rect" width="100px" height="40px" />
      </q-card-section>
    </q-card>
  </q-page>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { api } from 'boot/axios';

const route = useRoute();
const router = useRouter();
const course = ref(null);
const isLoading = ref(true);

const fetchCourse = async () => {
  try {
    const response = await api.get(`/courses/${route.params.id}`);
    course.value = response.data;
  } catch (error) {
    console.error('Fehler beim Abrufen des Kurses:', error);
  } finally {
    isLoading.value = false;
  }
};

const goBack = () => {
  router.push('/courses');
};


const startCourse = () => {
  router.push(`/courses/${course.value.id}/modules`);
};

const getCourseImage = (title) => {
  const images = {
    "Stressbewältigung": "/stressbewaeltigung.avif",
  };
  return images[title] || "/images/courses/default.png";
};

onMounted(() => {
  fetchCourse();
});
</script>

<style scoped>
.course-detail-background {
  background-color: var(--primary);
  min-height: 100vh;
  padding: 20px;
}

.back-button {
  position: absolute;
  top: 15px;
  left: 15px;
}

.image-container {
  width: 100%;
  height: 200px;
  overflow: hidden;
}

.course-image-skeleton {
  width: 100%;
  height: 200px;
  border-radius: 10px;
}

.course-image {
  width: 100%;
  height: 100%;
  object-fit: cover;
  border-radius: 10px;
}

.course-card {
  margin-top: -50px;
  background: white;
  border-radius: 20px;
  padding: 20px;
  box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
}

.skeleton-list {
  margin-top: 10px;
}

.button-section {
  text-align: center;
  margin-top: 20px;
}
</style>
