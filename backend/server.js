const express = require("express");
const session = require("express-session");
const authRoutes = require("./controllers/auth");
const app = express();
const PORT = 3000;
const { createTables } = require("./db/pool");
const cors = require("cors");
const passport = require("passport");

// Importando as rotas
const topicosRoutes = require("./routes/topicos");
const ideiasRoutes = require("./routes/ideias");

app.use(
  session({
    secret: "seu_segredo_aqui", // Altere para um segredo real
    resave: false,
    saveUninitialized: false,
    cookie: { secure: false },
  })
);

// Inicializar sessão
app.use(passport.initialize());
app.use(passport.session());

// Middleware para ler JSON
app.use(
  cors({
    origin: "http://localhost:8080", // URL do frontend
    credentials: true, // Permitir envio de cookies
  })
);

app.use(express.json());

app.use(express.urlencoded({ extended: true }));

// Rotas
app.use("/", authRoutes);

app.use("/topicos", topicosRoutes);
app.use("/ideias", ideiasRoutes);

// Iniciando o servidor
app.listen(PORT, async () => {
  await createTables();
  console.log(`Servidor rodando na porta ${PORT}`);
});
