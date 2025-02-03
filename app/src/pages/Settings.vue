<template>
  <q-page class="q-pa-md">
    <div class="text-h5 text-center q-mb-md">Einstellungen</div>

    <q-card class="q-pa-md">
      <q-card-section>
        <div class="text-h6">Farbschema auswählen</div>
      </q-card-section>

      <q-list bordered separator>
        <q-item
          v-for="(theme, key) in themes"
          :key="key"
          clickable
          v-ripple
          @click="changeTheme(key)"
        >
          <q-item-section>
            <q-item-label>{{ key }}</q-item-label>
            <div class="color-preview">
              <span v-for="color in theme" :key="color" :style="{ background: color }"></span>
            </div>
          </q-item-section>

          <q-item-section side>
            <q-icon name="check_circle" v-if="selectedTheme === key" color="primary" />
          </q-item-section>
        </q-item>
      </q-list>
    </q-card>
  </q-page>
</template>

<script setup>
import { ref } from "vue";
import { themes, applyTheme } from "boot/theme";

const selectedTheme = ref(localStorage.getItem("userTheme") || "lightBlue");

const changeTheme = (themeName) => {
  selectedTheme.value = themeName;
  applyTheme(themeName);
};
</script>

<style scoped>
.color-preview {
  display: flex;
  gap: 5px;
  margin-top: 5px;
}

.color-preview span {
  width: 20px;
  height: 20px;
  border-radius: 50%;
  display: inline-block;
}
</style>
