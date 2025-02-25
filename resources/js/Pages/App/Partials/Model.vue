<script setup>
import { onMounted, ref, onUnmounted } from "vue";
import { useGLTF, useAnimations } from "@tresjs/cientos";

onUnmounted(() => {
  console.log("Model component unmounted");
});

const props = defineProps({
  modelName: {
    type: String,
    required: true,
  },
  positionX: { type: Number, default: 0 },
  positionY: { type: Number, default: 0 },
  positionZ: { type: Number, default: 0 },
  rotationX: { type: Number, default: 0 },
  rotationY: { type: Number, default: 0 },
  rotationZ: { type: Number, default: 0 },
  iteration: { type: String, default: "" },
});

const { scene, animations } = await useGLTF("./models/" + props.modelName + props.iteration + ".glb", { draco: true });
const { actions, mixer } = useAnimations(animations, scene);

if (actions || animations) {
  // console.log(actions);
  // console.log(animations);
  if (actions.avaturn_animation) actions.avaturn_animation.play();
  if (actions["rig|rig|walk"]) actions["rig|rig|walk"].play();
}

const positionYLocal = ref(props.positionY);

onMounted(() => {
  // console.log(scene);
  if (props.modelName == "drago") positionYLocal.value = 0.47;
  if (props.modelName == "drako") positionYLocal.value = 0.43;

  if (props.modelName == "draco") {
    positionYLocal.value = 0.34;
    scene.children[0].scale.set(0.5, 0.5, 0.5);
  }

  if (props.modelName == "dracorisz") {
    positionYLocal.value = 0.48;
    scene.children[0].scale.set(0.5, 0.5, 0.5);
  }
});
</script>

<template>
  <primitive :object="scene" :position="[positionX, positionYLocal, positionZ]" :rotation="[rotationX, rotationY, rotationZ]" :castShadow="true" />
</template>
