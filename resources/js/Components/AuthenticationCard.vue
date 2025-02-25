<template>
  <div class="flex flex-col justify-center items-center h-screen w-screen relative bg-black">
    <img :src="'images/backgrounds/background04.jpg'" alt="" class="absolute w-full h-full object-cover opacity-20">
    <div class="p-10 md:rounded-2xl bg-black bg-gradient-to-bl from-primary to-transparent relative z-40 md:w-[500px] h-screen md:h-auto flex flex-col w-full">
			<div class="flex items-center justify-center w-full mb-10 mx-auto">
				<slot name="logo" />
			</div>
      <slot />
    </div>
  </div>
</template>
