/*
 Navicat Premium Data Transfer

 Source Server         : localhost_5432
 Source Server Type    : PostgreSQL
 Source Server Version : 110005
 Source Host           : localhost:5432
 Source Catalog        : Test1
 Source Schema         : public

 Target Server Type    : PostgreSQL
 Target Server Version : 110005
 File Encoding         : 65001

 Date: 22/09/2020 11:11:38
*/


-- ----------------------------
-- Table structure for dokter
-- ----------------------------
DROP TABLE IF EXISTS "public"."dokter";
CREATE TABLE "public"."dokter" (
  "id_dokter" int2 NOT NULL DEFAULT nextval('"dokter\_id_dokter_seq"'::regclass),
  "nama_dokter" varchar(30) COLLATE "pg_catalog"."default",
  "id_poli" int2,
  "id_condition" int2,
  "kode_dokter" varchar(255) COLLATE "pg_catalog"."default"
)
;

-- ----------------------------
-- Primary Key structure for table dokter
-- ----------------------------
ALTER TABLE "public"."dokter" ADD CONSTRAINT "dokter\_pkey" PRIMARY KEY ("id_dokter");

-- ----------------------------
-- Foreign Keys structure for table dokter
-- ----------------------------
ALTER TABLE "public"."dokter" ADD CONSTRAINT "dokter to poli" FOREIGN KEY ("id_poli") REFERENCES "public"."poli" ("id_poli") ON DELETE NO ACTION ON UPDATE NO ACTION;
