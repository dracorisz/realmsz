<script setup>
import { ref, onMounted } from "vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import AIImageGenerator from "@/Components/AIImageGenerator.vue";
// import process from 'process';
// import { OrbitControls, Stars } from "@tresjs/cientos";
// import { TresCanvas, useRenderLoop } from "@tresjs/core";
// import { BasicShadowMap, NoToneMapping, SRGBColorSpace, Color, AdditiveBlending, BufferAttribute, Scene, MathUtils, Box3, Vector3 } from "three";
// import Model from "./Partials/Model.vue";
// import vertexShader from "./Partials/shaders/vertex.glsl?raw";
// import fragmentShader from "./Partials/shaders/fragment.glsl?raw";

// const gl = {
//   clearColor: "#030303",
//   shadows: true,
//   alpha: true,
//   shadowMapType: BasicShadowMap,
//   outputColorSpace: SRGBColorSpace,
//   toneMapping: NoToneMapping,
//   // windowSize: false,
// };

// const parameters = {
//   count: 90000,
//   size: 36,
//   radius: 2.3,
//   branches: 10,
//   spin: 0,
//   randomness: 0.13,
//   randomnessPower: 5.8,
//   insideColor: "#22efff",
//   outsideColor: "#0090d4",
// };

// const colorInside = new Color(parameters.insideColor);
// const colorOutside = new Color(parameters.outsideColor);

// const positions = new Float32Array(parameters.count * 3);
// const colors = new Float32Array(parameters.count * 3);
// const scales = new Float32Array(parameters.count);
// const randomnessArray = new Float32Array(parameters.count * 3);

// for (let i = 0; i < parameters.count; i++) {
//   const i3 = i * 3;

//   const radius = Math.random() * parameters.radius;
//   const spinAngle = radius * parameters.spin;
//   // const spinAngle = 0;
//   const branchAngle = ((i % parameters.branches) * Math.PI * 2) / parameters.branches;

//   positions[i3] = Math.cos(branchAngle) * radius; // x
//   positions[i3 + 1] = 0; // y
//   positions[i3 + 2] = Math.sin(branchAngle) * radius; // z

//   const randomX = Math.random() ** parameters.randomnessPower * (Math.random() < 0.5 ? -1 : 1);
//   const randomY = Math.random() ** parameters.randomnessPower * (Math.random() < 0.5 ? -1 : 1);
//   const randomZ = Math.random() ** parameters.randomnessPower * (Math.random() < 0.5 ? -1 : 1);

//   randomnessArray[i3] = randomX;
//   randomnessArray[i3 + 1] = randomY;
//   randomnessArray[i3 + 2] = randomZ;

//   const mixedColor = colorInside.clone();
//   mixedColor.lerp(colorOutside, radius / parameters.radius);

//   colors[i3 + 0] = mixedColor.r; // R
//   colors[i3 + 1] = mixedColor.g; // G
//   colors[i3 + 2] = mixedColor.b; // B

//   scales[i] = Math.random();
// }

// const shader = {
//   transparent: true,
//   depthWrite: false,
//   blending: AdditiveBlending,
//   vertexColors: true,
//   vertexShader,
//   fragmentShader,
//   uniforms: {
//     uTime: { value: 0 },
//     uSize: {
//       value: parameters.size,
//     },
//   },
// };

// function updateGalaxy() {
//   if (bufferRef.value) {
//     const colorInside = new Color(parameters.insideColor);
//     const colorOutside = new Color(parameters.outsideColor);

//     const positions = new Float32Array(parameters.count * 3);
//     const colors = new Float32Array(parameters.count * 3);
//     const scales = new Float32Array(parameters.count);
//     const randomness = new Float32Array(parameters.count * 3);
//     for (let i = 0; i < parameters.count; i++) {
//       const i3 = i * 3;

//       const radius = Math.random() * parameters.radius;
//       const spinAngle = radius * parameters.spin;
//       // const spinAngle = 0;
//       const branchAngle = ((i % parameters.branches) * Math.PI * 2) / parameters.branches;

//       positions[i3] = Math.cos(branchAngle) * radius; // x
//       positions[i3 + 1] = 0; // y
//       positions[i3 + 2] = Math.sin(branchAngle) * radius; // z

//       const randomX = Math.random() ** parameters.randomnessPower * (Math.random() < 0.5 ? -1 : 1);
//       const randomY = Math.random() ** parameters.randomnessPower * (Math.random() < 0.5 ? -1 : 1);
//       const randomZ = Math.random() ** parameters.randomnessPower * (Math.random() < 0.5 ? -1 : 1);

//       randomness[i3] = randomX;
//       randomness[i3 + 1] = randomY;
//       randomness[i3 + 2] = randomZ;

//       const mixedColor = colorInside.clone();
//       mixedColor.lerp(colorOutside, radius / parameters.radius);

//       colors[i3 + 0] = mixedColor.r; // R
//       colors[i3 + 1] = mixedColor.g; // G
//       colors[i3 + 2] = mixedColor.b; // B

//       scales[i] = Math.random();
//     }
//     bufferRef.value.geometry.setAttribute("position", new BufferAttribute(positions, 3));
//     bufferRef.value.geometry.setAttribute("aRandomness", new BufferAttribute(randomness, 3));
//     bufferRef.value.geometry.setAttribute("color", new BufferAttribute(colors, 3));
//     bufferRef.value.geometry.setAttribute("aScale", new BufferAttribute(scales, 1));
//   }
// }

// const bufferRef = ref(null);

// const { onLoop } = useRenderLoop();

// onLoop(({ elapsed }) => {
//   if (bufferRef.value) {
//     bufferRef.value.material.uniforms.uTime.value = elapsed;
//   }
// });

// const positionX = ref(0);
// const positionY = ref(0);
// const positionZ = ref(0);
// const rotationX = ref(0);
// const rotationY = ref(0.5);
// const rotationZ = ref(0);

// const camPosX = ref(0);
// const camPosY = ref(10);
// const camPosZ = ref(20);

// const zoom = ref(1);

// const camera = ref(null);
// const scene = ref(null);

// onMounted(() => {
//   scene.value = new Scene();
//   const model = scene.value.getObjectByName("modelIdle");

//   if (model && camera.value) {
//     model.position.set(positionX.value, positionY.value, positionZ.value);

//     const box = new Box3().setFromObject(model);
//     const modelHeight = box.getSize(new Vector3()).y;

//     const fovRadians = MathUtils.degToRad(camera.value.fov);
//     const cameraDistance = camera.value.position.distanceTo(controls.value.target);
//     const visibleHeightAtDistance = 2 * Math.tan(fovRadians / 2) * cameraDistance;
//     zoom.value = visibleHeightAtDistance / modelHeight;

//     const center = box.getCenter(new Vector3());
//     positionX.value = -center.x;
//     positionY.value = -center.y;
//     positionZ.value = -center.z;
//     model.position.set(positionX.value, positionY.value, positionZ.value);
//   }

//   updateGalaxy();
// });

// const animatedModel = ref("modelIdle");

// const changeModel = (modelName) => {
//   animatedModel.value = modelName;
// };

// const currentCard = ref("start");
// const assetUrl = import.meta.env.VITE_ASSET_URL;
</script>

<template>
  <AppLayout title="Studio">
    <div class="flex h-full w-full items-center justify-center p-5">
      <AIImageGenerator />
    </div>
    <!-- <template v-if="currentCard == 'start'">
      <div class="flex h-full w-full items-center justify-center p-5">
        <div class="flex h-full w-full gap-5 text-xs">
          <div class="crypto-gradient w-1/6 " @click="currentCard = 'design'">
            <span class="text-sm text-white/70 group-hover:text-white">Design</span>
          </div>
          <div class="crypto-gradient w-1/3" @click="currentCard = 'improve'">
            <span class="text-sm text-white/70 group-hover:text-white">Improve</span>
          </div>
          <div class="crypto-gradient w-1/2 relative" @click="currentCard = '3d'">
            <img :src="`${assetUrl}/images/backgrounds/johnwick.jpg`" class="absolute inset-0 z-0 h-full w-full object-cover opacity-50" />
          </div>
        </div>
      </div>
    </template>
    <template v-if="currentCard == 'design'">
      <div class="flex flex-col items-start gap-5 p-5 text-xs text-white">
        <PrimaryButton @click="currentCard = 'start'">Back</PrimaryButton>
        <div class="h-full flex items-center justify-center w-full gap-2 text-white/70">
          <span>Module under development</span>
          <span class="animate-pulse">_</span>
        </div>
      </div>
    </template>
    <template v-if="currentCard == 'improve'">
      <div class="flex flex-col items-start gap-5 p-5 text-xs text-white">
        <PrimaryButton @click="currentCard = 'start'">Back</PrimaryButton>
        <div class="h-full flex items-center justify-center w-full gap-2 text-white/70">
      <span>Module under development</span>
      <span class="animate-pulse">_</span>
    </div>
      </div>
    </template>
    <template v-if="currentCard == '3d'">
      <div class="absolute z-50 flex flex-col gap-5 p-5">
        <PrimaryButton @click="currentCard = 'start'" class="mr-auto">Back</PrimaryButton>
        <div class="flex items-center justify-start gap-5">
          <div class="flex flex-col">
            <div class="flex w-full flex-col items-start gap-3">
              <PrimaryButton @click="changeModel('modelIdle')">Idle</PrimaryButton>
              <PrimaryButton @click="changeModel('modelWarmup')">Warmup</PrimaryButton>
              <PrimaryButton @click="changeModel('modelDance')">Dance</PrimaryButton>
              <PrimaryButton @click="changeModel('modelThink')">Think</PrimaryButton>
            </div>
          </div>
        </div>
      </div> -->
      <!-- <TresCanvas v-bind="gl">
        
        <TresPerspectiveCamera :position="[camPosX, camPosY, camPosZ]" :zoom="5.8" ref="camera" />
        <TresPoints ref="bufferRef">
          <TresBufferGeometry :position="[positions, 3]" :a-scale="[scales, 1]" :color="[colors, 3]" :a-randomness="[randomnessArray, 3]" />
          <TresShaderMaterial v-bind="shader" />
        </TresPoints>
        <OrbitControls :enablePan="false" :target="[positionX, positionY + 0.9, positionZ]" ref="controls" :minDistance="8" :maxDistance="13" :minPolarAngle="Math.PI / 4" :maxPolarAngle="(3 * Math.PI) / 4" :autoRotate="true" :autoRotateSpeed="0.5" />
        <Stars :radius="1" />
        <Suspense>
          <Model v-if="animatedModel == 'modelIdle'" modelName="modelIdle" :positionX="positionX" :positionY="positionY" :positionZ="positionZ" :rotationX="rotationX" :rotationY="rotationY" :rotationZ="rotationZ" />
          <Model v-else-if="animatedModel == 'modelWarmup'" modelName="modelWarmup" :positionX="positionX" :positionY="positionY" :positionZ="positionZ" :rotationX="rotationX" :rotationY="rotationY" :rotationZ="rotationZ" />
          <Model v-else-if="animatedModel == 'modelDance'" modelName="modelDance" :positionX="positionX" :positionY="positionY" :positionZ="positionZ" :rotationX="rotationX" :rotationY="rotationY" :rotationZ="rotationZ" />
          <Model v-else-if="animatedModel == 'modelThink'" modelName="modelThink" :positionX="positionX" :positionY="positionY" :positionZ="positionZ" :rotationX="rotationX" :rotationY="rotationY" :rotationZ="rotationZ" />
          <Model v-else-if="animatedModel == 'drako'" modelName="drako" :positionX="positionX" :positionY="positionY" :positionZ="positionZ" :rotationX="rotationX" :rotationY="rotationY" :rotationZ="rotationZ" />
          <Model v-else-if="animatedModel == 'drago'" modelName="drago" :positionX="positionX" :positionY="positionY" :positionZ="positionZ" :rotationX="rotationX" :rotationY="rotationY" :rotationZ="rotationZ" />
          <Model v-else-if="animatedModel == 'draco'" modelName="draco" iteration="3" :positionX="positionX" :positionY="positionY" :positionZ="positionZ" :rotationX="rotationX" :rotationY="rotationY" :rotationZ="rotationZ" />
          <Model v-else-if="animatedModel == 'dracorisz'" modelName="dracorisz" :positionX="positionX" :positionY="positionY" :positionZ="positionZ" :rotationX="rotationX" :rotationY="rotationY" :rotationZ="rotationZ" />
        </Suspense>
        <Edges color="#ffffff" />
        <TresDirectionalLight :intensity="3" :position="[10, 5, 0]" />
        <TresAmbientLight :intensity="2" />
        <TresGridHelper />
      </TresCanvas> -->
    <!-- </template> -->
  </AppLayout>
</template>

<style scoped lang="pcss">
.crypto-gradient {
  @apply relative z-[1] flex cursor-pointer flex-col items-center justify-center gap-5 rounded-2xl border-white/5 p-10;
  background-image: linear-gradient(34deg, hsla(0, 0%, 55%, 0.13), hsla(0, 0%, 21%, 0.21));
}

.crypto-gradient::before {
  @apply absolute inset-0 z-[-1] rounded-2xl opacity-0 transition-opacity duration-300 ease-linear content-[""];
  background-image: linear-gradient(to right, hsl(211, 100%, 10%), hsl(179, 100%, 10%));
}

.crypto-gradient:hover::before {
  opacity: 1;
}
</style>
