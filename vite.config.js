import { defineConfig, loadEnv } from "vite";
import laravel from "laravel-vite-plugin";
import preload from "vite-plugin-preload";
import viteCompression from "vite-plugin-compression";
import vue from "@vitejs/plugin-vue";
import path from "path";

export default defineConfig(({ mode }) => {
  // Load env file based on `mode` (development or production)
  // const env = loadEnv(mode, process.cwd(), "");
  return {
    base: 'https://websitedesignsnyc.com/realmsz',
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
    ],
    resolve: {
      alias: {
        "@": path.resolve(__dirname, "./resources/js"),
        "~": path.resolve(__dirname, "./resources"),
      },
    },
    build: {
      outDir: 'public/build',
      manifest: true,
      rollupOptions: {
        input: [
          'resources/css/app.css',
          'resources/js/app.js',
        ],
        output: {
          entryFileNames: '[name].js',
          chunkFileNames: '[name].js',
          assetFileNames: '[name].[ext]',
        },
      },
    },
  };
});

