const express = require("express");
const router = express.Router();
const {
  cadastrarIdeia,
  listarIdeias,
  likeIdeia,
} = require("../controllers/ideiasController");

// Rota para cadastrar uma ideia
router.post("/", cadastrarIdeia);
router.get("/:topico_id", listarIdeias);
router.post("/:id/like", likeIdeia);

module.exports = router;
