const path = require("path");
const { promises: fs } = require("fs");
const dayjs = require("dayjs");

// Memastikan folder dibuat
const ensureDirectoryExistence = async (filePath) => {
  const dirname = path.dirname(filePath);
  try {
    await fs.mkdir(dirname, { recursive: true });
  } catch (error) {
    console.error(
      "Terjadi kesalahan saat memastikan keberadaan direktori:",
      error
    );
  }
};

// Fungsi untuk mendapatkan timestamp dengan dayjs
const getTimestamp = () => {
  return dayjs().format("YYYY-MM-DD_HH-mm-ss");
};

module.exports = {
  ensureDirectoryExistence,
  getTimestamp,
};
