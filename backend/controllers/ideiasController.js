const { pool } = require("../db/pool");

// Cadastrar uma nova ideia
const cadastrarIdeia = async (req, res) => {
  const { topico_id, titulo, user_id } = req.body;

  if (!titulo || !topico_id || !user_id) {
    return res
      .status(400)
      .json({ message: "Tópico ID e título da ideia são obrigatórios" });
  }

  try {
    const result = await pool.query(
      "INSERT INTO ideias (topico_id, titulo, user_id ) VALUES ($1, $2, $3) RETURNING *",
      [topico_id, titulo, user_id]
    );
    res.status(201).json(result.rows[0]);
  } catch (error) {
    console.error("Erro ao cadastrar ideia:", error);
    res.status(500).json({ message: "Erro interno no servidor" });
  }
};

const listarIdeias = async (req, res) => {
  try {
    const { topico_id } = req.params;

    const result = await pool.query(
      "SELECT * FROM ideias WHERE topico_id = $1",
      [topico_id]
    );
    res.status(200).json(result.rows);
  } catch (error) {
    console.error("Erro ao buscar ideias:", error);
    res.status(500).json({ message: "Erro interno no servidor" });
  }
};

const listarIdeiasCurtidas = async (req, res) => {
  try {
    const { topico_id, user_id } = req.query;

    const result = await pool.query(
      "SELECT * FROM ideias LEFT JOIN likes ON ideias.id = likes.ideia_id WHERE topico_id = $1 AND likes.user_id = $2",
      [topico_id, user_id]
    );
    res.status(200).json(result.rows);
  } catch (error) {
    console.error("Erro ao buscar ideias CURTIDAS:", error);
    res.status(500).json({ message: "Erro interno no servidor" });
  }
};

const likeIdeia = async (req, res) => {
  const ideia_id = req.params.id;
  const { user_id } = req.body;

  try {
    // Verifica se o usuário já deu like nessa ideia
    const existingLike = await pool.query(
      "SELECT * FROM likes WHERE user_id = $1 AND ideia_id = $2",
      [user_id, ideia_id]
    );

    if (existingLike.rows.length > 0) {
      // Remove o like
      await pool.query(
        "DELETE FROM likes WHERE user_id = $1 AND ideia_id = $2",
        [user_id, ideia_id]
      );

      // Atualiza o número de likes da ideia
      await pool.query("UPDATE ideias SET likes = likes - 1 WHERE id = $1", [
        ideia_id,
      ]);
      return res.json({ message: "Ideia descurtida" });
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
};

module.exports = {
  cadastrarIdeia,
  listarIdeias,
  likeIdeia,
  listarIdeiasCurtidas,
};
