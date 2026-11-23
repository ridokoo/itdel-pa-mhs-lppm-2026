#!/usr/bin/env node

require("dotenv").config();
const { execSync } = require("child_process");
const fs = require("fs");
const path = require("path");

class PgBackup {
  constructor() {
    this.config = {
      host: process.env.DB_HOST,
      port: process.env.DB_PORT,
      user: process.env.DB_USERNAME,
      password: process.env.DB_PASSWORD,
      database: process.env.DB_DATABASE,
    };
    if (this.config.password) process.env.PGPASSWORD = this.config.password;

    this.backupDir = path.join(__dirname, "../backups");
    if (!fs.existsSync(this.backupDir))
      fs.mkdirSync(this.backupDir, { recursive: true });
  }

  backup() {
    const timestamp = new Date()
      .toISOString()
      .replace(/[:.]/g, "-")
      .slice(0, 19);
    const output = path.join(
      this.backupDir,
      `backup_${this.config.database}_${timestamp}.dump`
    );

    console.log(`📦 Backup: ${path.basename(output)}`);
    execSync(
      `pg_dump -h ${this.config.host} -p ${this.config.port} -U ${this.config.user} -Fc -Z 9 ${this.config.database} -f "${output}"`
    );

    const size = fs.statSync(output).size;
    console.log(`✅ Done: ${output} (${this.formatBytes(size)})`);
    return output;
  }

  restore(clean = true) {
    const latest = this.getLatestBackup();
    if (!latest) throw new Error("No backup found");

    console.log(`🔄 Restore: ${path.basename(latest)}`);
    const cleanFlag = clean ? "-c" : "";
    execSync(
      `pg_restore -h ${this.config.host} -p ${this.config.port} -U ${this.config.user} ${cleanFlag} -d ${this.config.database} "${latest}"`
    );

    console.log(`✅ Restored`);
  }

  getLatestBackup() {
    const files = fs
      .readdirSync(this.backupDir)
      .filter(
        (f) =>
          f.startsWith(`backup_${this.config.database}_`) && f.endsWith(".dump")
      )
      .map((f) => ({
        name: f,
        time: fs.statSync(path.join(this.backupDir, f)).mtime,
      }))
      // @ts-ignore
      .sort((a, b) => b.time - a.time);

    return files.length ? path.join(this.backupDir, files[0].name) : null;
  }

  list() {
    const backups = this.getLatestBackup()
      ? fs
          .readdirSync(this.backupDir)
          .filter(
            (f) =>
              f.startsWith(`backup_${this.config.database}_`) &&
              f.endsWith(".dump")
          )
          .map((f) => {
            const stats = fs.statSync(path.join(this.backupDir, f));
            return `${f} (${this.formatBytes(stats.size)})`;
          })
      : [];

    console.log(backups.length ? backups.join("\n") : "No backups");
  }

  formatBytes(bytes) {
    return bytes === 0
      ? "0 B"
      : (bytes / 1024 ** Math.floor(Math.log(bytes) / Math.log(1024))).toFixed(
          2
        ) +
          " " +
          ["B", "KB", "MB", "GB"][Math.floor(Math.log(bytes) / Math.log(1024))];
  }
}

// Execute
const args = process.argv.slice(2);
const tool = new PgBackup();

if (!args.length || args.includes("-h")) {
  console.log(`Usage: node ${path.basename(__filename)} [backup|restore|list]`);
} else if (args[0] === "backup") {
  tool.backup();
} else if (args[0] === "restore") {
  tool.restore(args[1] !== "--no-clean");
} else if (args[0] === "list") {
  tool.list();
}
