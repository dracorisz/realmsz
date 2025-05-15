<template>
  <div class="focus-timer">
    <div v-if="currentSession" class="active-session">
      <div class="timer-display">
        <span class="time">{{ formattedTime }}</span>
        <span class="item-name">{{ currentSession.item.name }}</span>
      </div>
      <div class="controls">
        <button @click="endSession" class="btn btn-danger">End Session</button>
      </div>
    </div>
    <div v-else class="start-session">
      <div class="stats" v-if="stats">
        <div class="stat-item">
          <span class="label">Today's Focus</span>
          <span class="value">{{ stats.today.totalMinutes }} min</span>
        </div>
        <div class="stat-item">
          <span class="label">This Week</span>
          <span class="value">{{ stats.week.totalMinutes }} min</span>
        </div>
        <div class="stat-item">
          <span class="label">Best Streak</span>
          <span class="value">{{ stats.bestStreak }} days</span>
        </div>
      </div>
      <div class="item-select">
        <select v-model="selectedItemId" class="form-select">
          <option value="">Select an item to focus on</option>
          <option v-for="item in availableItems" :key="item.id" :value="item.id">
            {{ item.name }}
          </option>
        </select>
      </div>
      <div class="duration-select">
        <select v-model="selectedDuration" class="form-select">
          <option value="25">25 minutes</option>
          <option value="45">45 minutes</option>
          <option value="60">60 minutes</option>
          <option value="90">90 minutes</option>
        </select>
      </div>
      <button 
        @click="startSession" 
        class="btn btn-primary"
        :disabled="!selectedItemId"
      >
        Start Focus Session
      </button>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'FocusTimer',
  data() {
    return {
      currentSession: null,
      availableItems: [],
      selectedItemId: '',
      selectedDuration: 25,
      stats: null,
      timer: null,
      elapsedTime: 0
    };
  },
  computed: {
    formattedTime() {
      const minutes = Math.floor(this.elapsedTime / 60);
      const seconds = this.elapsedTime % 60;
      return `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
    }
  },
  async created() {
    await this.loadData();
    if (this.currentSession) {
      this.startTimer();
    }
  },
  beforeUnmount() {
    this.stopTimer();
  },
  methods: {
    async loadData() {
      try {
        const [sessionResponse, itemsResponse, statsResponse] = await Promise.all([
          axios.get('/api/focus'),
          axios.get('/api/items'),
          axios.get('/api/focus/stats')
        ]);
        
        this.currentSession = sessionResponse.data.session;
        this.availableItems = itemsResponse.data;
        this.stats = statsResponse.data;
        
        if (this.currentSession) {
          const startTime = new Date(this.currentSession.started_at);
          const now = new Date();
          this.elapsedTime = Math.floor((now - startTime) / 1000);
        }
      } catch (error) {
        console.error('Error loading focus data:', error);
      }
    },
    async startSession() {
      try {
        const response = await axios.post('/api/focus/start', {
          item_id: this.selectedItemId,
          duration: this.selectedDuration
        });
        
        this.currentSession = response.data;
        this.selectedItemId = '';
        this.elapsedTime = 0;
        this.startTimer();
      } catch (error) {
        console.error('Error starting focus session:', error);
      }
    },
    async endSession() {
      try {
        await axios.post('/api/focus/end');
        this.currentSession = null;
        this.stopTimer();
        await this.loadData();
      } catch (error) {
        console.error('Error ending focus session:', error);
      }
    },
    startTimer() {
      this.timer = setInterval(() => {
        this.elapsedTime++;
        if (this.elapsedTime >= this.currentSession.duration * 60) {
          this.endSession();
        }
      }, 1000);
    },
    stopTimer() {
      if (this.timer) {
        clearInterval(this.timer);
        this.timer = null;
      }
    }
  }
};
</script>

<style scoped>
.focus-timer {
  max-width: 600px;
  margin: 0 auto;
  padding: 20px;
  background: #fff;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.active-session {
  text-align: center;
}

.timer-display {
  margin-bottom: 20px;
}

.time {
  font-size: 48px;
  font-weight: bold;
  color: #2c3e50;
}

.item-name {
  display: block;
  font-size: 18px;
  color: #666;
  margin-top: 10px;
}

.controls {
  margin-top: 20px;
}

.stats {
  display: flex;
  justify-content: space-around;
  margin-bottom: 20px;
}

.stat-item {
  text-align: center;
}

.stat-item .label {
  display: block;
  font-size: 14px;
  color: #666;
}

.stat-item .value {
  display: block;
  font-size: 24px;
  font-weight: bold;
  color: #2c3e50;
}

.item-select,
.duration-select {
  margin-bottom: 15px;
}

.btn {
  padding: 10px 20px;
  border-radius: 4px;
  cursor: pointer;
  font-weight: 500;
  transition: all 0.3s ease;
}

.btn-primary {
  background-color: #3498db;
  color: white;
  border: none;
}

.btn-primary:hover {
  background-color: #2980b9;
}

.btn-danger {
  background-color: #e74c3c;
  color: white;
  border: none;
}

.btn-danger:hover {
  background-color: #c0392b;
}

.btn:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}
</style> 