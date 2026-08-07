#!/usr/bin/env node
/**
 * Creates every table in db/schema.sql against the configured MySQL database.
 * Safe to re-run: every statement is CREATE TABLE IF NOT EXISTS.
 *
 *   npm run db:init
 *
 * Reads MYSQL_* from .env / .env.local, or from the real environment when run
 * on Hostinger over SSH.
 */
import { readFile } from "node:fs/promises";
import path from "node:path";
import process from "node:process";
import mysql from "mysql2/promise";
import { loadEnv } from "./load-env.mjs";

loadEnv();

const required = ["MYSQL_HOST", "MYSQL_USER", "MYSQL_DATABASE"];
const missing = required.filter((key) => !process.env[key]);

if (missing.length > 0) {
  console.error(`Missing environment variables: ${missing.join(", ")}`);
  console.error("Copy .env.example to .env and fill in your Hostinger MySQL details.");
  process.exit(1);
}

const schema = await readFile(
  path.join(process.cwd(), "db", "schema.sql"),
  "utf8",
);

const connection = await mysql.createConnection({
  host: process.env.MYSQL_HOST,
  port: Number(process.env.MYSQL_PORT ?? 3306),
  user: process.env.MYSQL_USER,
  password: process.env.MYSQL_PASSWORD,
  database: process.env.MYSQL_DATABASE,
  ssl: process.env.MYSQL_SSL === "true" ? { rejectUnauthorized: true } : undefined,
  // The schema is one file of several statements; without this each would need
  // its own round trip.
  multipleStatements: true,
});

try {
  await connection.query(schema);
  const [tables] = await connection.query("SHOW TABLES");
  console.log(`Schema applied to "${process.env.MYSQL_DATABASE}".`);
  console.log(
    "Tables:",
    tables.map((row) => Object.values(row)[0]).join(", "),
  );
} finally {
  await connection.end();
}
