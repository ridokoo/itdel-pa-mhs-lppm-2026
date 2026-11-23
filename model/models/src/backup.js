require("dotenv").config();
const path = require("path");
// const db = require("./db");
const {
  ensureDirectoryExistence,
  getTimestamp,
} = require("../utils/toolsUtil");
const models = require("../entities/_models");
const fs = require("fs");

// Fungsi untuk melakukan backup database
async function backupDatabase() {
  try {
    // Ambil data dari setiap tabel
    const backupData = {};
    for (const model of models) {
      const records = await model.findAll();
      backupData[model.name] = records.map((record) => record.toJSON());
    }

    const timestamp = getTimestamp();
    const dbName = process.env.DB_NAME;
    const fileName = path.resolve(
      __dirname,
      `../backups/${dbName}_backup_${timestamp}.json`
    );

    // Pastikan direktori ada
    await ensureDirectoryExistence(fileName);

    // Simpan data ke file .json dengan nama timestamp
    fs.writeFileSync(fileName, JSON.stringify(backupData, null, 2), "utf-8");
    console.log("Backup selesai.");
    console.log(`File disimpan sebagai ${fileName}`);
  } catch (error) {
    console.error("Gagal melakukan backup:", error);
  } finally {
    // process exit
  }
}

// Jalankan fungsi backup
backupDatabase();
