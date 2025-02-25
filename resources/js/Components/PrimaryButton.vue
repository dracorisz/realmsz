<script setup>
import { Link } from "@inertiajs/vue3";
import { computed, ref } from "vue";

const props = defineProps({
  type: {
    type: String,
    default: "submit",
  },
  href: {
    type: String,
    default: "#",
  },
  disabled: {
    type: Boolean,
    default: false,
  },
  toggled: {
    type: Boolean,
    default: true,
  },
  color: {
    type: String,
    default: "#00449f",
  },
  opacity: {
    type: String,
    default: "100",
  },
  hoverOpacity: {
    type: String,
    default: "100",
  },
  onlyIcon: {
    type: Boolean,
    default: false,
  },
});

// const colorClasses = computed(() => {
//   let background = "bg-" + props.color + "/" + props.opacity;
//   let backgroundHover = "hover:bg-" + props.color + "/" + props.hoverOpacity;
//   let border = "border-" + props.color + "/" + props.opacity;
//   let borderHover = "hover:border-" + props.color + "/" + props.hoverOpacity;
//   let toggleBorderHover = !props.toggled ? borderHover : "hover:" + border;
//   let textHover = "hover:text-" + props.color + "/" + props.hoverOpacity;
//   let toggleBackgroundHover = !props.toggled ? backgroundHover : "hover:" + background;
//   let fullString = background + " " + toggleBackgroundHover + " " + border + " " + toggleBorderHover + " text-white/70 hover:text-white";
//   let toggledFullString = "bg-transparent text-white/50 border-white/20 " + textHover + " " + borderHover;
//   return !props.toggled ? toggledFullString : fullString;
// });

const hoverState = ref(false);

const hexToRgb = (hex) => {
  let r = 0, g = 0, b = 0;

  if (hex.length === 4) {
    r = parseInt(hex[1] + hex[1], 16);
    g = parseInt(hex[2] + hex[2], 16);
    b = parseInt(hex[3] + hex[3], 16);
  } else if (hex.length === 7) {
    r = parseInt(hex[1] + hex[2], 16);
    g = parseInt(hex[3] + hex[4], 16);
    b = parseInt(hex[5] + hex[6], 16);
  }

  return { r, g, b };
};

const rgbaFromHex = (hex, opacity) => {
  const { r, g, b } = hexToRgb(hex);
  const alpha = opacity / 100;
  return `rgba(${r}, ${g}, ${b}, ${alpha})`;
};

const inlineStyles = computed(() => {
  const backgroundColor = rgbaFromHex(props.color, props.opacity / 3);
  const hoverBackgroundColor = rgbaFromHex(props.color, props.hoverOpacity / 2);
  const borderColor = rgbaFromHex(props.color, props.opacity / 3);
  const hoverBorderColor = rgbaFromHex(props.color, props.hoverOpacity / 2);
  const textColor = "rgba(255, 255, 255, .5)"; // Default white text with 70% opacity
  const hoverTextColor = "rgba(255, 255, 255, 1)"; // White text on hover

  return {
    backgroundColor: props.toggled ? hoverBackgroundColor : hoverState.value ? hoverBackgroundColor : backgroundColor,
    borderColor: props.toggled ? hoverBorderColor : hoverState.value ? hoverBorderColor : borderColor,
    color: props.toggled ? hoverTextColor : hoverState.value ? hoverTextColor : textColor,
  };
});
</script>

<template>
  <Link v-if="type === 'link'" @mouseenter="hoverState = true" @mouseleave="hoverState = false" :href="route(href)" :class="['button group', disabled && 'disabled', onlyIcon ? 'justify-center rounded-full px-px as-[36px]' : 'rounded-2xl px-3']" :style="inlineStyles">
    <span v-if="$slots.icon" :class="['group-hover:animate-wave will-change-transform flex items-center justify-center as-[24px]', !onlyIcon && 'mr-2']">
      <slot name="icon" />
    </span>
    <slot v-if="!onlyIcon" />
  </Link>
  <button v-else :type="type" @mouseenter="hoverState = true" @mouseleave="hoverState = false" :class="['button group', disabled && 'disabled', onlyIcon ? 'justify-center rounded-full px-px as-[36px]' : 'rounded-2xl px-3']" :style="inlineStyles">
    <span v-if="$slots.icon" :class="['group-hover:animate-wave will-change-transform flex items-center justify-center as-[24px]', !onlyIcon && 'mr-2']">
      <slot name="icon" />
    </span>
    <slot v-if="!onlyIcon" />
  </button>
</template>

<style scoped lang="pcss">
.button {
  @apply flex items-center border text-xs backdrop-blur-3xl transition-colors duration-300 ease-in-out ah-[36px];
}
</style>
