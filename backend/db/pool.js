const { Pool } = require("pg");
require("dotenv").config();

const pool = new Pool({
  user: process.env.DB_USER,
  host: process.env.DB_HOST,
  database: process.env.DB_NAME,
  password: process.env.DB_PASSWORD,
  port: process.env.DB_PORT,
});

const createTables = async () => {
  try {
    await pool.query(`
            CREATE TABLE IF NOT EXISTS users (
                id SERIAL PRIMARY KEY,
                google_id VARCHAR(255) UNIQUE NOT NULL,
                name VARCHAR(255),
                email VARCHAR(255) UNIQUE,
                profile_picture TEXT,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            );
        `);

    await pool.query(`
            CREATE TABLE IF NOT EXISTS topicos (
                id SERIAL PRIMARY KEY,
                titulo VARCHAR(255) NOT NULL,
                user_id INTEGER REFERENCES users(id),
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            );
        `);

    await pool.query(`
            CREATE TABLE IF NOT EXISTS ideias (
                id SERIAL PRIMARY KEY,
                topico_id INT REFERENCES topicos(id),
                titulo VARCHAR(255) NOT NULL,
                likes INT DEFAULT 0,
                user_id INTEGER REFERENCES users(id),
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            );
        `);

    await pool.query(`
            CREATE TABLE IF NOT EXISTS likes (
                id SERIAL PRIMARY KEY,
                user_id INTEGER REFERENCES users(id),
                ideia_id INTEGER REFERENCES ideias(id),
                UNIQUE(user_id, ideia_id)
            );
        `);
    console.log("Tabelas criadas ou já existentes.");
  } catch (error) {
    console.error("Erro ao criar tabelas:", error);
  }
};

module.exports = { pool, createTables };
