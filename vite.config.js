import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import preload from "vite-plugin-preload";
import viteCompression from "vite-plugin-compression";
import glsl from "vite-plugin-glsl";
import vue from "@vitejs/plugin-vue";
import path from "path";

export default defineConfig({
  plugins: [
    laravel({
      input: ["resources/css/app.css", "resources/js/app.js"],
      refresh: true,
      ssr: "resources/js/ssr.js",
    }),
    vue({
      template: {
        transformAssetUrls: {
          base: null,
          includeAbsolute: false,
        },
      },
    }),
    preload(),
    viteCompression(),
    glsl(),
  ],
  resolve: {
    alias: {
      "@": path.resolve(__dirname, "./resources/js"),
      "~": path.resolve(__dirname, "./resources"),
      // ziggy: path.resolve("vendor/tightenco/ziggy/dist/vue.m"),
    },
  },
  // optimizeDeps: {
  //   include: ["vue", "@inertiajs/vue3", "@heroicons/vue", "gsap"],
  //   exclude: ["@inertiajs/server", "ziggy"],
  // },
  // css: {
  //   preprocessorOptions: {
  //     scss: {
  //       additionalData: `@import "resources/css/variables.scss";`,
  //     },
  //   },
  // },
  // ssr: {
  //   noExternal: ["@inertiajs/server"],
  // },
  // build: {
  //   rollupOptions: {
  //     output: {
  //       manualChunks: {
  //         vendor: ["vue", "@inertiajs/vue3"],
  //         gsap: ["gsap"],
  //         fontawesome: ["@fortawesome/fontawesome-svg-core", "@fortawesome/free-brands-svg-icons", "@fortawesome/free-solid-svg-icons", "@fortawesome/free-regular-svg-icons"],
  //         heroicons: ["@heroicons/vue"],
  //       },
  //     },
  //   },
  // },
});

