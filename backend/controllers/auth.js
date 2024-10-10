const GoogleStrategy = require("passport-google-oauth20").Strategy;
const express = require("express");
const router = express.Router();
const { pool } = require("../db/pool");
require("dotenv").config();
const passport = require("passport");

passport.use(
  new GoogleStrategy(
    {
      clientID: process.env.GOOGLE_CLIENT_ID,
      clientSecret: process.env.GOOGLE_CLIENT_SECRET,
      callbackURL: "/auth/google/callback",
    },
    async (accessToken, refreshToken, profile, done) => {
      try {
        // Verifica se o usuário já existe no banco de dados
        const existingUser = await pool.query(
          "SELECT * FROM users WHERE google_id = $1",
          [profile.id]
        );

        if (existingUser.rows.length > 0) {
          // Se o usuário já existe, retorna o usuário
          return done(null, existingUser.rows[0]);
        }

        // Se o usuário não existe, cria um novo usuário
        const newUser = await pool.query(
          "INSERT INTO users (google_id, name, email, profile_picture) VALUES ($1, $2, $3, $4) RETURNING *",
          [
            profile.id,
            profile.displayName,
            profile.emails[0].value,
            profile.photos[0].value,
          ]
        );

        // Retorna o novo usuário
        return done(null, newUser.rows[0]);
      } catch (error) {
        console.error("Erro ao autenticar com Google:", error);
        return done(error, null);
      }
    }
  )
);

// Serialização do usuário
passport.serializeUser((user, done) => {
  done(null, user.id);
});

// Desserialização do usuário
passport.deserializeUser(async (id, done) => {
  try {
    const user = await pool.query("SELECT * FROM users WHERE id = $1", [id]);
    done(null, user.rows[0]);
  } catch (error) {
    done(error, null);
  }
});

// Rota de login com Google
router.get(
  "/auth/google",
  passport.authenticate("google", { scope: ["profile", "email"] })
);

// Rota de callback do Google
router.get(
  "/auth/google/callback",
  passport.authenticate("google", { failureRedirect: "/" }),
  (req, res) => {
    if (req.isAuthenticated()) {
      console.log("Autenticação bem-sucedida");
      console.log("Usuário autenticado:", req.user); // Verificar os dados do usuário

      const user = req.user; // Os dados do usuário autenticado
      // Redirecionar para o frontend com os dados do usuário
      res.redirect(
        `http://localhost:8080/login/set_session.php?id=${
          user.id
        }&name=${encodeURIComponent(user.name)}&email=${encodeURIComponent(
          user.email
        )}&profile_picture=${encodeURIComponent(user.profile_picture)}`
      );
    } else {
      console.log("Falha na autenticação");
      res.redirect("/"); // Redirecionar para login se falhar
    }
  }
);

module.exports = router;
