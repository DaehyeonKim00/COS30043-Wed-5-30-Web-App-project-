import { defineConfig } from "vite";
import vue from "@vitejs/plugin-vue";
import jwtVueTools from "./vite-plugin-jwt-vue.js";

// https://vite.dev/config/
export default defineConfig({
  base: "/cos30043/s104838522/test/",
  plugins: [
    vue(),
    // Register the JWT dev helper only when a token is provided via env.
    jwtVueTools({ token: process.env.VITE_DEV_JWT || "", injectBadge: false }),
  ],
});
