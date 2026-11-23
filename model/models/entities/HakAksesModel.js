const { DataTypes } = require("sequelize");
const db = require("../src/db");

const TABLE_NAME = "m_hak_akses";
const DataModel = db.define(
  TABLE_NAME,
  {
    id: {
      type: DataTypes.UUID,
      defaultValue: DataTypes.UUIDV4,
      primaryKey: true,
    },
    user_id: {
      type: DataTypes.UUID,
      defaultValue: DataTypes.UUIDV4,
    },
    akses: {
      type: DataTypes.TEXT,
      allowNull: false,
    },
  },
  {
    tableName: TABLE_NAME,
    createdAt: "created_at",
    updatedAt: "updated_at",
    timestamps: true,
  },
);

module.exports = DataModel;
