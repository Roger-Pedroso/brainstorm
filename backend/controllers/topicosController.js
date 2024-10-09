const { pool } = require("../db/pool");

// Cadastrar um novo tópico
const cadastrarTopico = async (req, res) => {
  const { titulo, user_id } = req.body;

  if (!titulo || !user_id) {
    return res.status(400).json({ message: "Título é User ID obrigatório" });
  }

  try {
    const result = await pool.query(
      "INSERT INTO topicos (titulo, user_id) VALUES ($1, $2) RETURNING *",
      [titulo, user_id]
    );
    res.status(201).json(result.rows[0]);
  } catch (error) {
    console.error("Erro ao cadastrar tópico:", error);
    res.status(500).json({ message: "Erro interno no servidor" });
  }
};

const listarTopicos = async (req, res) => {
  try {
    const result = await pool.query("SELECT * FROM topicos");
    res.status(200).json(result.rows);
  } catch (error) {
    console.error("Erro ao buscar tópicos:", error);
    res.status(500).json({ message: "Erro interno no servidor" });
  }
};

module.exports = { cadastrarTopico, listarTopicos };
