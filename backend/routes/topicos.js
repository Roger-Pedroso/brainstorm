const express = require("express");
const router = express.Router();
const {
  cadastrarTopico,
  listarTopicos,
  listarParticipantes,
  buscarTopico,
} = require("../controllers/topicosController");

// Rota para cadastrar um tópico
router.post("/", cadastrarTopico);
router.get("/", listarTopicos);
router.get("/:id/participantes", listarParticipantes);
router.get("/:id", buscarTopico);

module.exports = router;
