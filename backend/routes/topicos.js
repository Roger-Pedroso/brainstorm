const express = require("express");
const router = express.Router();
const {
  cadastrarTopico,
  listarTopicos,
} = require("../controllers/topicosController");

// Rota para cadastrar um tópico
router.post("/", cadastrarTopico);
router.get("/", listarTopicos);

module.exports = router;
