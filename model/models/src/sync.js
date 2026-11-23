require("../entities/_models");
const db = require("./db");

async function syncDB() {
  try {
    console.log("Sedang melakukan sinkronisasi data...");
    await db.sync({ alter: true });
    console.log("Berhasil melakukan sinkronisasi database.");
  } catch (error) {
    console.error("Gagal melakukan sinkronisasi database: ", error);
  } finally {
    db.close();
  }
}

(async () => {
  await syncDB();
})();
