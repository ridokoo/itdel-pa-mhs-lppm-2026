require("dotenv").config();
const path = require("path");
const { promises: fs } = require("fs");
const db = require("./db");
const models = require("../entities/_models");

// Nama file backups
const fileName = process.env.FILE_NAME_FOR_MIGRATE;

async function migrateDatabase() {
  try {
    // Koneksi ke database
    await db.authenticate();
    console.log("Koneksi berhasil.");

    if (!fileName) {
      console.log("Tidak ada file yang perlu di migrasi");
      await db.close();
      return;
    }

    // Membaca data backup dari file JSON
    const backupFilePath = path.join(__dirname, `backups/${fileName}`);

    const backupData = JSON.parse(await fs.readFile(backupFilePath, "utf-8"));

    // Menghapus data lama dan memasukkan data dari backup satu per satu
    for (const [modelName, records] of Object.entries(backupData)) {
      const model = models.find((m) => m.name === modelName);
      if (model) {
        await model.destroy({ where: {} }); // Menghapus data lama
        console.log(`Data lama untuk model ${modelName} telah dihapus.`);

        // Menyimpan data dari backup satu per satu
        for (const record of records) {
          await model.create(record);
          console.log(
            `Data untuk model ${modelName} telah ditambahkan:`,
            record.id
          );
        }
      } else {
        console.warn(`Model ${modelName} tidak ditemukan.`);
      }
    }

    console.log("Migrasi selesai.");
  } catch (error) {
    console.error("Gagal melakukan migrasi:", error);
  } finally {
    await db.close();
  }
}

// Jalankan fungsi migrasi
migrateDatabase();
