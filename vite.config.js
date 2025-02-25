import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import preload from "vite-plugin-preload";
import viteCompression from "vite-plugin-compression";
import glsl from "vite-plugin-glsl";
import vue from "@vitejs/plugin-vue";
import { templateCompilerOptions } from "@tresjs/core";

export default defineConfig({
  plugins: [
    laravel({
      input: ["resources/js/app.js", "resources/css/app.css"],
      refresh: true,
    }),
    vue({
      template: {
        transformAssetUrls: {
          base: null,
          includeAbsolute: false,
        },
      },
      ...templateCompilerOptions,
    }),
    glsl(),
    preload(),
    viteCompression(),
  ],
});

