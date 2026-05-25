import { createApp } from "vue";
import App from "./App.vue";
import router from "./router";
import { store } from "./store";
import { readAuthSession } from "./utils/authSession.js";
import AuthBadge from "./components/AuthBadge.vue";

import "bootstrap/dist/css/bootstrap.min.css";
import "bootstrap/dist/js/bootstrap.bundle.min.js";
import "./style.css";

// Restore the logged-in user from the configured auth storage.
const savedSession = readAuthSession();
if (savedSession.user) {
  store.commit("setUser", savedSession.user);
  store.commit("setRememberMe", savedSession.rememberMe);
  store.commit("setExpiresAt", savedSession.expiresAt);
  // Pull the server-side cart so the Navbar count is correct after refresh.
  store.dispatch("fetchCart");
}

const app = createApp(App);
app.component("AuthBadge", AuthBadge);
app.use(router);
app.use(store);
app.mount("#app");
