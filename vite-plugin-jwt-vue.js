export default function jwtVueTools({ token = "", injectBadge = false } = {}) {
  let decoded = null;
  try {
    if (token) {
      const parts = token.split(".");
      if (parts.length >= 2) {
        decoded = JSON.parse(Buffer.from(parts[1], "base64").toString("utf8"));
      }
    }
  } catch (e) {
    decoded = null;
  }

  return {
    name: "vite-plugin-jwt-vue-tools",

    configureServer(server) {
      // Dev endpoint for Vue route guards
      server.middlewares.use("/__jwt/status", (req, res) => {
        const now = Math.floor(Date.now() / 1000);
        const expired = decoded?.exp && decoded.exp < now;

        res.setHeader("Content-Type", "application/json");
        res.end(
          JSON.stringify({
            valid: !expired,
            expired,
            payload: decoded,
          }),
        );
      });

      // HMR event for token refresh (send initial state)
      try {
        server.ws.send({
          type: "custom",
          event: "jwt:update",
          data: { token },
        });
      } catch (e) {
        // ignore if ws not available yet
      }
    },

    // Inject a visible badge into the page if requested
    transformIndexHtml(html) {
      if (!injectBadge) return { html, tags: [] };
      return {
        html,
        tags: [
          {
            tag: "script",
            injectTo: "body",
            children: `
              fetch('/__jwt/status')
                .then(r => r.json())
                .then(info => {
                  const el = document.createElement('div');
                  el.style = "position:fixed;bottom:10px;right:10px;background:#333;color:#fff;padding:6px 10px;border-radius:4px;font-size:12px;";
                  el.innerText = info.valid ? "JWT Active" : "JWT Expired";
                  document.body.appendChild(el);
                });
            `,
          },
        ],
      };
    },
  };
}
