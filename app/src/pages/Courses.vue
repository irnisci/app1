<template>
  <q-page padding class="startpage-background">
    <div class="text-h4 text-center q-mb-md text-white">Kurse</div>

    <div class="row q-col-gutter-md">
      <div v-for="course in courses" :key="course.id" class="col-12 col-md-6">
        <q-card class="cursor-pointer modern-card" @click="goToCourse(course.id)">
          <div class="image-container">
            <img :src="getCourseImage(course.title)" class="course-image" alt="Kurs Illustration" />
            <q-icon
              :name="favoriteCourses.includes(course.id) ? 'favorite' : 'favorite_border'"
              class="favorite-icon"
              size="24px"
              @click.stop="toggleFavorite(course)"
            />
          </div>

          <q-card-section class="card-content">
            <div class="text-h6">{{ course.title }}</div>
            <div class="text-subtitle2">{{ course.description }}</div>

            <q-linear-progress v-if="course.progress" :value="course.progress" color="purple" class="q-mt-md" />
          </q-card-section>
        </q-card>
      </div>
    </div>
  </q-page>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import { api } from 'boot/axios';

const router = useRouter();
const courses = ref([]);
const favoriteCourses = ref([]);

const fetchCourses = async () => {
  try {
    const response = await api.get('/courses');
    courses.value = response.data.map(course => ({
      ...course,
      progress: Math.random() // Simulierter Fortschritt (später mit API ersetzen)
    }));
  } catch (error) {
    console.error('Fehler beim Abrufen der Kurse:', error);
  }
};

const goToCourse = (id) => {
  router.push(`/courses/${id}`);
};

const getCourseImage = (title) => {
  const images = {
    "Stressbewältigung": "/stressbewaeltigung.avif",
  };
  return images[title] || "/images/courses/default.png";
};

// Favoriten-Status toggeln
const toggleFavorite = (course) => {
  if (favoriteCourses.value.includes(course.id)) {
    favoriteCourses.value = favoriteCourses.value.filter(id => id !== course.id);
  } else {
    favoriteCourses.value.push(course.id);
  }
};

onMounted(() => {
  fetchCourses();
});
</script>

<style scoped>
.startpage-background {
  background-color: var(--primary);
  min-height: 100vh;
  padding: 20px;
}

.modern-card {
  border-radius: 20px;
  overflow: hidden;
  background: white;
  box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
  transition: transform 0.3s, box-shadow 0.3s;
}

.modern-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 15px 25px rgba(0, 0, 0, 0.2);
}

.image-container {
  position: relative;
  height: 120px;
  overflow: hidden;
}

.course-image {
  width: 100%;
  height: 100%;
  object-fit: cover;
  border-radius: 15px 15px 0 0;
}

.favorite-icon {
  position: absolute;
  top: 10px;
  right: 10px;
  color: white;
  background: rgba(0, 0, 0, 0.5);
  padding: 6px;
  border-radius: 50%;
  cursor: pointer;
  transition: color 0.3s, background 0.3s;
}

.favorite-icon:hover {
  color: red;
  background: rgba(255, 255, 255, 0.7);
}

.card-content {
  padding: 15px;
  text-align: center;
}

.text-h6 {
  font-weight: bold;
}

.text-subtitle2 {
  color: gray;
}
</style>
