<script setup>
import Modal from "./Modal.vue";

const emit = defineEmits(["close"]);

defineProps({
  show: {
    type: Boolean,
    default: false,
  },
  maxWidth: {
    type: String,
    default: "2xl",
  },
  closeable: {
    type: Boolean,
    default: true,
  },
});

const close = () => {
  emit("close");
};
</script>

<template>
  <Modal :show="show" :max-width="maxWidth" :closeable="closeable" @close="close">
    <template v-if="$slots.header">
      <div class="text-white text-xs bg-white/5 px-10 pt-10 pb-5 rounded-t-lg">
        <slot name="header" />
      </div>
    </template>
		<template v-if="$slots.content">
			<div class="text-xs text-white px-10 bg-white/5">
				<slot name="content" />
			</div>
		</template>
		<template v-if="$slots.footer">
      <div class="flex flex-row justify-end px-10 pt-5 pb-10 bg-gradient-to-bl from-white/5 to-70% to-white/5 text-right h-max max-h-max rounded-b-lg relative z-30">
        <slot name="footer" />
      </div>
		</template>
  </Modal>
</template>
