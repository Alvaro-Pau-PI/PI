<template>
  <div class="banner-carousel">
    <div 
      class="carousel-track" 
      :style="{ transform: `translateX(-${currentIndex * 100}%)` }"
    >
      <div 
        v-for="(slide, index) in slides" 
        :key="index" 
        class="carousel-slide"
      >
        <img :src="slide.image" :alt="slide.alt" loading="lazy" />
        <div class="overlay">
            <!-- Optional: Add text or buttons here -->
        </div>
      </div>
    </div>

    <!-- Navigation Controls -->
    <button @click="prevSlide" class="nav-btn prev" aria-label="Anterior banner">
      ❮
    </button>
    <button @click="nextSlide" class="nav-btn next" aria-label="Siguiente banner">
      ❯
    </button>

    <!-- Indicators -->
    <div class="indicators">
      <span 
        v-for="(slide, index) in slides" 
        :key="index" 
        :class="['dot', { active: index === currentIndex }]"
        @click="goToSlide(index)"
      ></span>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';

const props = defineProps({
  slides: {
    type: Array,
    required: true,
    // Formato esperado: [{ image: '/ruta/a/img.png', alt: 'Descripción' }]
  },
  interval: {
    type: Number,
    default: 5000
  }
});

const currentIndex = ref(0);
let intervalId = null;

const nextSlide = () => {
  currentIndex.value = (currentIndex.value + 1) % props.slides.length;
};

const prevSlide = () => {
  currentIndex.value = (currentIndex.value - 1 + props.slides.length) % props.slides.length;
};

const goToSlide = (index) => {
  currentIndex.value = index;
};

const startAutoPlay = () => {
  stopAutoPlay(); // Prevent multiple intervals
  intervalId = setInterval(nextSlide, props.interval);
};

const stopAutoPlay = () => {
  if (intervalId) {
    clearInterval(intervalId);
    intervalId = null;
  }
};

onMounted(() => {
  startAutoPlay();
});

onUnmounted(() => {
  stopAutoPlay();
});
</script>

<style scoped>
.banner-carousel {
  position: relative;
  width: 100%;
  height: 300px; /* Reducido drásticamente como pidió el usuario */
  overflow: hidden;
  border-bottom: 2px solid #00A1FF;
}

.carousel-track {
  display: flex;
  height: 100%;
  transition: transform 0.6s cubic-bezier(0.25, 1, 0.5, 1);
}

.carousel-slide {
  min-width: 100%;
  height: 100%;
  position: relative;
}

.carousel-slide img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  object-position: center;
}

/* Navigation Buttons */
.nav-btn {
  position: absolute;
  top: 50%;
  transform: translateY(-50%);
  background: rgba(0, 0, 0, 0.5);
  color: white;
  border: none;
  font-size: 2rem;
  padding: 1rem;
  cursor: pointer;
  transition: background 0.3s;
  z-index: 10;
  border-radius: 50%;
  width: 50px;
  height: 50px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.nav-btn:hover {
  background: rgba(0, 161, 255, 0.8);
}

.prev {
  left: 20px;
}

.next {
  right: 20px;
}

/* Indicators */
.indicators {
  position: absolute;
  bottom: 20px;
  left: 50%;
  transform: translateX(-50%);
  display: flex;
  gap: 10px;
  z-index: 10;
}

.dot {
  width: 12px;
  height: 12px;
  background-color: rgba(255, 255, 255, 0.5);
  border-radius: 50%;
  cursor: pointer;
  transition: all 0.3s;
}

.dot.active {
  background-color: #00A1FF;
  transform: scale(1.2);
}

/* Responsive */
@media (max-width: 768px) {
  .banner-carousel {
    height: 200px;
  }
  
  .nav-btn {
    padding: 0.5rem;
    font-size: 1.2rem;
    width: 35px;
    height: 35px;
  }
}
</style>
