import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import preload from "vite-plugin-preload";
import viteCompression from "vite-plugin-compression";
import glsl from "vite-plugin-glsl";
import vue from "@vitejs/plugin-vue";

export default defineConfig({
  plugins: [
    laravel({
      input: ["resources/css/app.css", "resources/js/app.js"],
      refresh: true,
    }),
    vue({
      // template: {
      //   transformAssetUrls: {
      //     base: null,
      //     includeAbsolute: false,
      //   },
      // },
    }),
    preload(),
    viteCompression(),
    glsl(),
  ],
});

