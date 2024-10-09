const express = require("express");
const router = express.Router();
const pool = require("../db");

// Rota para curtir uma ideia
router.post("/ideias/:id/like", async (req, res) => {
  const ideia_id = req.params.id;
  const { user_id } = req.body;

  try {
    // Verifica se o usuário já deu like nessa ideia
    const existingLike = await pool.query(
      "SELECT * FROM likes WHERE user_id = $1 AND ideia_id = $2",
      [user_id, ideia_id]
    );

    if (existingLike.rows.length > 0) {
      return res.status(400).json({ message: "Você já curtiu essa ideia" });
    }

    // Adiciona o like
    await pool.query("INSERT INTO likes (user_id, ideia_id) VALUES ($1, $2)", [
      user_id,
      ideia_id,
    ]);

    // Atualiza o número de likes da ideia
    await pool.query("UPDATE ideias SET likes = likes + 1 WHERE id = $1", [
      ideia_id,
    ]);

    res.json({ message: "Ideia curtida com sucesso" });
  } catch (error) {
    console.error("Erro ao curtir ideia:", error);
    res.status(500).json({ message: "Erro ao curtir ideia" });
  }
});

module.exports = router;
