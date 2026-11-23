async function seedDB() {
  try {
    console.log("Berhasil melakukan penyemaian database.");
  } catch (error) {
    console.error("Gagal melakukan penyemaian database: ", error);
  } finally {
    process.exit(0);
  }
}

(async () => {
  await seedDB();
})();
