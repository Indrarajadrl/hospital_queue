/*
PostgreSQL Backup
Database: antrian/public
Backup Time: 2020-11-05 16:42:11
*/

DROP SEQUENCE IF EXISTS "public"."dokter\_id_dokter_seq";
DROP SEQUENCE IF EXISTS "public"."id_antrian_pasien_seq";
DROP SEQUENCE IF EXISTS "public"."id_antrian_seq";
DROP SEQUENCE IF EXISTS "public"."id_dokter_seq";
DROP SEQUENCE IF EXISTS "public"."id_register_antrian_seq";
DROP SEQUENCE IF EXISTS "public"."id_register_dokter_seq";
DROP SEQUENCE IF EXISTS "public"."id_ruang_seq";
DROP SEQUENCE IF EXISTS "public"."no_rekam_medis";
DROP SEQUENCE IF EXISTS "public"."pasien_id_pasien_seq";
DROP SEQUENCE IF EXISTS "public"."poli_id_poli_seq";
DROP SEQUENCE IF EXISTS "public"."user_data_header_iduser_seq1";
DROP SEQUENCE IF EXISTS "public"."user_data_header_iduser_seq";
DROP TABLE IF EXISTS "public"."antrian";
DROP TABLE IF EXISTS "public"."antrian_pasien";
DROP TABLE IF EXISTS "public"."condition";
DROP TABLE IF EXISTS "public"."counter_status";
DROP TABLE IF EXISTS "public"."divisi_witel";
DROP TABLE IF EXISTS "public"."dokter";
DROP TABLE IF EXISTS "public"."dokter_to_poli";
DROP TABLE IF EXISTS "public"."master_parameter";
DROP TABLE IF EXISTS "public"."pasien";
DROP TABLE IF EXISTS "public"."poli";
DROP TABLE IF EXISTS "public"."register_antrian";
DROP TABLE IF EXISTS "public"."register_dokter";
DROP TABLE IF EXISTS "public"."ruang";
DROP TABLE IF EXISTS "public"."user_data_access";
DROP TABLE IF EXISTS "public"."user_data_header";
DROP TABLE IF EXISTS "public"."user_data_map";
DROP TABLE IF EXISTS "public"."user_data_role";
DROP TABLE IF EXISTS "public"."user_data_session";
DROP FUNCTION IF EXISTS "public"."get_antrian_self()";
DROP FUNCTION IF EXISTS "public"."get_antriann(kode_antrian int4)";
DROP FUNCTION IF EXISTS "public"."get_film_count(len_from int4, len_to int4)";
DROP FUNCTION IF EXISTS "public"."get_no_mobile()";
DROP FUNCTION IF EXISTS "public"."get_no_web()";
DROP FUNCTION IF EXISTS "public"."insert_data(input_id_pasien int4, input_id_poli int4)";
CREATE SEQUENCE "dokter\_id_dokter_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 32767
START 1
CACHE 1;
CREATE SEQUENCE "id_antrian_pasien_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 9223372036854775807
START 1
CACHE 1;
CREATE SEQUENCE "id_antrian_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 999999999999999999
START 1
CACHE 1;
CREATE SEQUENCE "id_dokter_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 9223372036854775807
START 1
CACHE 1
CYCLE ;
CREATE SEQUENCE "id_register_antrian_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 9999999999999999
START 1
CACHE 1;
CREATE SEQUENCE "id_register_dokter_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 9999999999999999
START 1
CACHE 1;
CREATE SEQUENCE "id_ruang_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 9223372036854775807
START 1
CACHE 1;
CREATE SEQUENCE "no_rekam_medis" 
INCREMENT 1
MINVALUE  1
MAXVALUE 9223372036854775807
START 1
CACHE 1;
CREATE SEQUENCE "pasien_id_pasien_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 9223372036854775807
START 1
CACHE 1;
CREATE SEQUENCE "poli_id_poli_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 32767
START 1
CACHE 1;
CREATE SEQUENCE "user_data_header_iduser_seq1" 
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 1
CACHE 1;
CREATE SEQUENCE "user_data_header_iduser_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 1
CACHE 1;
CREATE TABLE "antrian" (
  "id_antrian" int2 NOT NULL DEFAULT nextval('id_antrian_seq'::regclass),
  "no_antrian" int2,
  "create_date" date
)
;
ALTER TABLE "antrian" OWNER TO "postgres";
CREATE TABLE "antrian_pasien" (
  "id_pasien" int2,
  "no_antrian" int2,
  "create_date" date,
  "nama" varchar(150) COLLATE "pg_catalog"."default" DEFAULT NULL::character varying,
  "tempat_lahir" varchar(100) COLLATE "pg_catalog"."default" DEFAULT NULL::character varying,
  "tanggal_lahir" varchar COLLATE "pg_catalog"."default",
  "alamat" varchar(150) COLLATE "pg_catalog"."default" DEFAULT NULL::character varying,
  "no_hp" varchar(50) COLLATE "pg_catalog"."default" DEFAULT NULL::character varying,
  "id_poli" int2,
  "id_dokter" int2,
  "ktp" varchar(16) COLLATE "pg_catalog"."default" DEFAULT NULL::character varying,
  "id_register" int2,
  "id_antrian" int2 NOT NULL DEFAULT nextval('id_antrian_pasien_seq'::regclass),
  "status_code" int2,
  "id_ruang" int2,
  "waktu" time(6),
  "no_rekam_medis" varchar(20) COLLATE "pg_catalog"."default",
  "sisa_antrian" varchar(50) COLLATE "pg_catalog"."default"
)
;
ALTER TABLE "antrian_pasien" OWNER TO "postgres";
CREATE TABLE "condition" (
  "id_condition" int2 NOT NULL,
  "condition" varchar(255) COLLATE "pg_catalog"."default"
)
;
ALTER TABLE "condition" OWNER TO "postgres";
CREATE TABLE "counter_status" (
  "id_status" int2 NOT NULL,
  "status_code" int2,
  "status_name" varchar(30) COLLATE "pg_catalog"."default"
)
;
ALTER TABLE "counter_status" OWNER TO "postgres";
CREATE TABLE "divisi_witel" (
  "divisi_witel_id" varchar(3) COLLATE "pg_catalog"."default" NOT NULL,
  "divisi_witel_name" varchar(32) COLLATE "pg_catalog"."default",
  "id_dmaco" int4,
  "witel_imon" varchar(100) COLLATE "pg_catalog"."default"
)
;
ALTER TABLE "divisi_witel" OWNER TO "postgres";
CREATE TABLE "dokter" (
  "id_dokter" int2 NOT NULL DEFAULT nextval('id_dokter_seq'::regclass),
  "nama_dokter" varchar(30) COLLATE "pg_catalog"."default",
  "kode_dokter" varchar(20) COLLATE "pg_catalog"."default",
  "image_dokter" varchar(50) COLLATE "pg_catalog"."default"
)
;
ALTER TABLE "dokter" OWNER TO "postgres";
CREATE TABLE "dokter_to_poli" (
  "id_dokter" int2 NOT NULL DEFAULT nextval('"dokter\_id_dokter_seq"'::regclass),
  "nama_dokter" varchar(30) COLLATE "pg_catalog"."default",
  "id_poli" int2,
  "id_condition" int2,
  "kode_dokter" varchar(30) COLLATE "pg_catalog"."default",
  "create_date" date,
  "image_dokter" varchar(30) COLLATE "pg_catalog"."default"
)
;
ALTER TABLE "dokter_to_poli" OWNER TO "postgres";
CREATE TABLE "master_parameter" (
  "idm_parameter" int4 NOT NULL,
  "param_type" varchar(100) COLLATE "pg_catalog"."default" NOT NULL,
  "param_val1" varchar(500) COLLATE "pg_catalog"."default",
  "param_val2" varchar(500) COLLATE "pg_catalog"."default",
  "param_val3" varchar(500) COLLATE "pg_catalog"."default",
  "param_desc" varchar(1000) COLLATE "pg_catalog"."default",
  "param_parent" int4
)
;
ALTER TABLE "master_parameter" OWNER TO "postgres";
COMMENT ON TABLE "master_parameter" IS 'master parameter';
CREATE TABLE "pasien" (
  "id_pasien" int2 NOT NULL DEFAULT nextval('pasien_id_pasien_seq'::regclass),
  "nama" varchar(255) COLLATE "pg_catalog"."default",
  "tempat_lahir" varchar(255) COLLATE "pg_catalog"."default",
  "tanggal_lahir" varchar(255) COLLATE "pg_catalog"."default",
  "alamat" varchar(255) COLLATE "pg_catalog"."default",
  "no_hp" varchar(255) COLLATE "pg_catalog"."default",
  "ktp" varchar(255) COLLATE "pg_catalog"."default",
  "create_date" date,
  "no_rekam_medis" varchar(20) COLLATE "pg_catalog"."default" NOT NULL
)
;
ALTER TABLE "pasien" OWNER TO "postgres";
CREATE TABLE "poli" (
  "id_poli" int2 NOT NULL DEFAULT nextval('poli_id_poli_seq'::regclass),
  "nama_poli" varchar(30) COLLATE "pg_catalog"."default",
  "kode_poli" varchar(5) COLLATE "pg_catalog"."default",
  "deskripsi_poli" varchar(1000) COLLATE "pg_catalog"."default",
  "image_poli" varchar(500) COLLATE "pg_catalog"."default"
)
;
ALTER TABLE "poli" OWNER TO "postgres";
CREATE TABLE "register_antrian" (
  "id_registerantrian" int2 NOT NULL DEFAULT nextval('id_register_antrian_seq'::regclass),
  "antrian_all" int2,
  "create_date" date,
  "jam_mulai" varchar(6) COLLATE "pg_catalog"."default",
  "waktu_antrian" varchar(6) COLLATE "pg_catalog"."default"
)
;
ALTER TABLE "register_antrian" OWNER TO "postgres";
CREATE TABLE "register_dokter" (
  "id_register" int2 NOT NULL DEFAULT nextval('id_register_dokter_seq'::regclass),
  "id_poli" int2,
  "id_dokter" int2,
  "id_ruang" int2,
  "antrian_all" int2,
  "create_date" date,
  "id_condition" int2,
  "jam_mulai" varchar(6) COLLATE "pg_catalog"."default",
  "waktu_antrian" varchar(6) COLLATE "pg_catalog"."default"
)
;
ALTER TABLE "register_dokter" OWNER TO "postgres";
CREATE TABLE "ruang" (
  "id_ruang" int2 NOT NULL DEFAULT nextval('id_ruang_seq'::regclass),
  "nama_ruang" varchar(50) COLLATE "pg_catalog"."default",
  "id_poli" int2,
  "id_condition" int2,
  "lantai" varchar(50) COLLATE "pg_catalog"."default"
)
;
ALTER TABLE "ruang" OWNER TO "postgres";
CREATE TABLE "user_data_access" (
  "access_id" int4 NOT NULL,
  "access_name" varchar(50) COLLATE "pg_catalog"."default",
  "access_type" varchar(255) COLLATE "pg_catalog"."default",
  "access_code" numeric(10),
  "access_desc" varchar(100) COLLATE "pg_catalog"."default",
  "access_action" varchar(100) COLLATE "pg_catalog"."default",
  "access_controller" varchar(100) COLLATE "pg_catalog"."default"
)
;
ALTER TABLE "user_data_access" OWNER TO "postgres";
COMMENT ON COLUMN "user_data_access"."access_action" IS 'NAMA FUNGSI PHP';
COMMENT ON COLUMN "user_data_access"."access_controller" IS 'NAMA CONTROLLERNYA';
CREATE TABLE "user_data_header" (
  "iduser" int4 NOT NULL GENERATED BY DEFAULT AS IDENTITY (
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 1
),
  "username" varchar(100) COLLATE "pg_catalog"."default",
  "password" varchar(100) COLLATE "pg_catalog"."default",
  "name" varchar(100) COLLATE "pg_catalog"."default",
  "role" int4,
  "status" int4,
  "deviceid" varchar(255) COLLATE "pg_catalog"."default",
  "accessToken" varchar(255) COLLATE "pg_catalog"."default",
  "create_dtm" timestamp(6),
  "retries" varchar(10) COLLATE "pg_catalog"."default",
  "update_date" timestamp(6),
  "email" varchar(50) COLLATE "pg_catalog"."default",
  "authKey" varchar(255) COLLATE "pg_catalog"."default",
  "nik" varchar(100) COLLATE "pg_catalog"."default",
  "lokasi_p" varchar(100) COLLATE "pg_catalog"."default",
  "domain_p" varchar(100) COLLATE "pg_catalog"."default",
  "regional_id" int2,
  "witel_id" varchar(50) COLLATE "pg_catalog"."default",
  "last_update" timestamp(6)
)
;
ALTER TABLE "user_data_header" OWNER TO "postgres";
COMMENT ON COLUMN "user_data_header"."role" IS 'RELATION WITH TB: USER_DATA_ROLE';
COMMENT ON COLUMN "user_data_header"."status" IS 'SET STATUS USER: ';
CREATE TABLE "user_data_map" (
  "map_id" int4 NOT NULL,
  "access_code" int4,
  "role_code" int4,
  "access_status" varchar(255) COLLATE "pg_catalog"."default",
  "last_update" timestamp(6)
)
;
ALTER TABLE "user_data_map" OWNER TO "postgres";
CREATE TABLE "user_data_role" (
  "access_role_id" int4 NOT NULL,
  "name" varchar(50) COLLATE "pg_catalog"."default",
  "access_role_code" int4,
  "description" varchar(50) COLLATE "pg_catalog"."default"
)
;
ALTER TABLE "user_data_role" OWNER TO "postgres";
CREATE TABLE "user_data_session" (
  "session_id" varchar(500) COLLATE "pg_catalog"."default" NOT NULL,
  "name" varchar(200) COLLATE "pg_catalog"."default",
  "modified" numeric(20),
  "lifetime" numeric(20),
  "data" varchar(4000) COLLATE "pg_catalog"."default",
  "owner" varchar(200) COLLATE "pg_catalog"."default"
)
;
ALTER TABLE "user_data_session" OWNER TO "postgres";
CREATE OR REPLACE FUNCTION "get_antrian_self"()
  RETURNS "pg_catalog"."int4" AS $BODY$
declare
   antrian_count integer;
begin

SELECT antrian.no_antrian
 into antrian_count FROM antrian 
INNER JOIN pasien 
ON antrian.id_pasien = pasien.id_pasien
WHERE pasien.create_date= CURRENT_DATE AND pasien.kode_antrian ='1' ORDER BY no_antrian DESC;

   
   return antrian_count;
end;
$BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100;
ALTER FUNCTION "get_antrian_self"() OWNER TO "postgres";
CREATE OR REPLACE FUNCTION "get_antriann"("kode_antrian" int4)
  RETURNS "pg_catalog"."int4" AS $BODY$
	
declare
   antrian_count integer;
begin

	INSERT INTO antrian(no_antrian,kode_antrian) VALUES ($1, $2);

   select count(*) 
   into antrian_count
   from antrian;
   
   return antrian_count;
end;
$BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100;
ALTER FUNCTION "get_antriann"("kode_antrian" int4) OWNER TO "postgres";
CREATE OR REPLACE FUNCTION "get_film_count"("len_from" int4, "len_to" int4)
  RETURNS "pg_catalog"."int4" AS $BODY$
declare
   film_count integer;
begin
   select count(*) 
   into film_count
   from film
   where length between len_from and len_to;
   
   return film_count;
end;
$BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100;
ALTER FUNCTION "get_film_count"("len_from" int4, "len_to" int4) OWNER TO "postgres";
CREATE OR REPLACE FUNCTION "get_no_mobile"()
  RETURNS "pg_catalog"."int4" AS $BODY$
declare
   antrian_count integer;
begin

SELECT count(antrian.no_antrian)
 into antrian_count FROM antrian 
INNER JOIN pasien 
ON antrian.id_pasien = pasien.id_pasien
WHERE pasien.create_date= CURRENT_DATE AND pasien.kode_antrian ='2' ;

   
   return antrian_count;
end;
$BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100;
ALTER FUNCTION "get_no_mobile"() OWNER TO "postgres";
CREATE OR REPLACE FUNCTION "get_no_web"()
  RETURNS "pg_catalog"."int4" AS $BODY$
declare
   antrian_count integer;
begin

SELECT count(antrian.no_antrian)
 into antrian_count FROM antrian 
INNER JOIN pasien 
ON antrian.id_pasien = pasien.id_pasien
WHERE pasien.create_date= CURRENT_DATE AND pasien.kode_antrian ='1' ;

   
   return antrian_count;
end;
$BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100;
ALTER FUNCTION "get_no_web"() OWNER TO "postgres";
CREATE OR REPLACE FUNCTION "insert_data"("input_id_pasien" int4, "input_id_poli" int4)
  RETURNS "pg_catalog"."int4" AS $BODY$
declare
   antrian_count integer;
	 input_id_pasien INTEGER ;
	 input_id_poli INTEGER;
	 
begin

SELECT no_antrian into antrian_count FROM antrian WHERE "id_poli" = input_id_poli AND "id_pasien" IS NULL LIMIT 1 ;

UPDATE antrian SET id_pasien=input_id_pasien , kode_antrian='1' WHERE no_antrian=antrian_count AND id_poli=input_id_poli ;

   
   return antrian_count;
end;
$BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100;
ALTER FUNCTION "insert_data"("input_id_pasien" int4, "input_id_poli" int4) OWNER TO "postgres";
BEGIN;
LOCK TABLE "public"."antrian" IN SHARE MODE;
DELETE FROM "public"."antrian";
INSERT INTO "public"."antrian" ("id_antrian","no_antrian","create_date") VALUES (236, 1, '2020-11-04'),(237, 2, '2020-11-04'),(238, 3, '2020-11-04'),(239, 4, '2020-11-04'),(240, 5, '2020-11-04'),(241, 6, '2020-11-04'),(242, 7, '2020-11-04'),(243, 8, '2020-11-04'),(244, 9, '2020-11-04'),(245, 10, '2020-11-04'),(246, 11, '2020-11-04'),(247, 12, '2020-11-04'),(258, 1, '2020-11-04'),(259, 2, '2020-11-04'),(225, 1, '2020-11-04'),(226, 2, '2020-11-04'),(227, 3, '2020-11-04'),(228, 4, '2020-11-04'),(229, 5, '2020-11-04'),(230, 6, '2020-11-04'),(231, 7, '2020-11-04'),(232, 8, '2020-11-04'),(233, 9, '2020-11-04'),(234, 10, '2020-11-04'),(235, 11, '2020-11-04'),(260, 3, '2020-11-04'),(261, 4, '2020-11-04'),(262, 5, '2020-11-04'),(263, 6, '2020-11-04'),(264, 7, '2020-11-04'),(265, 8, '2020-11-04'),(266, 9, '2020-11-04'),(267, 10, '2020-11-04'),(268, 11, '2020-11-04'),(269, 12, '2020-11-04'),(203, 1, '2020-11-04'),(204, 2, '2020-11-04'),(205, 3, '2020-11-04'),(206, 4, '2020-11-04'),(207, 5, '2020-11-04'),(208, 6, '2020-11-04'),(209, 7, '2020-11-04'),(210, 8, '2020-11-04'),(211, 9, '2020-11-04'),(212, 10, '2020-11-04'),(213, 11, '2020-11-04'),(214, 12, '2020-11-04'),(215, 1, '2020-11-03'),(216, 2, '2020-11-03'),(217, 3, '2020-11-03'),(218, 4, '2020-11-03'),(219, 5, '2020-11-03'),(220, 6, '2020-11-03'),(221, 7, '2020-11-03'),(222, 8, '2020-11-03'),(223, 9, '2020-11-03'),(224, 10, '2020-11-03'),(248, 1, '2020-11-03'),(249, 2, '2020-11-03'),(250, 3, '2020-11-03'),(251, 4, '2020-11-03'),(252, 5, '2020-11-03'),(253, 6, '2020-11-03'),(254, 7, '2020-11-03'),(255, 8, '2020-11-03'),(256, 9, '2020-11-03'),(257, 10, '2020-11-03'),(270, 1, '2020-11-05'),(271, 2, '2020-11-05'),(272, 3, '2020-11-05'),(273, 4, '2020-11-05'),(274, 5, '2020-11-05'),(275, 6, '2020-11-05'),(276, 7, '2020-11-05'),(277, 8, '2020-11-05'),(278, 9, '2020-11-05'),(279, 10, '2020-11-05'),(280, 11, '2020-11-05'),(281, 12, '2020-11-05');
COMMIT;
BEGIN;
LOCK TABLE "public"."antrian_pasien" IN SHARE MODE;
DELETE FROM "public"."antrian_pasien";
INSERT INTO "public"."antrian_pasien" ("id_pasien","no_antrian","create_date","nama","tempat_lahir","tanggal_lahir","alamat","no_hp","id_poli","id_dokter","ktp","id_register","id_antrian","status_code","id_ruang","waktu","no_rekam_medis","sisa_antrian") VALUES (34, 1, '2020-11-03', 'Ret', 'Bandung', '0012-12-12', 'Bandung', '12121212', 13, 8, '1234567891654321', 24, 248, 10, 2, NULL, 'CU644262', '0'),(14, 7, '2020-11-05', 'KAKA', '', '', '', '', 12, 2, '12', 26, 276, 10, 3, NULL, '00000119', '3'),(26, 7, '2020-11-03', '', '', '', '', '', 13, 8, '', 24, 254, 10, 2, NULL, '00000131', '0'),(26, 3, '2020-11-03', '', '', '', '', '', 13, 8, '', 24, 250, 10, 2, NULL, '00000131', '0'),(3, 10, '2020-11-03', 'AZY', 'Bandung', '0012-12-12', 'Bandung', '123456789098', 12, 2, '1234567890909090', 21, 224, 10, 3, NULL, '00000076', '0'),(16, 9, '2020-11-03', 'Indra', 'Bandung', '', '', '', 12, 2, '1235466', 21, 223, 10, 3, NULL, '00000121', '0'),(13, 5, '2020-11-04', 'de', '', '', '', '', 13, 8, '21321', 25, 262, 10, 2, NULL, '00000086', '0'),(11, 10, '2020-11-04', 'Hehe', '', '', '', '', 13, 8, '1234567891', 25, 267, 10, 2, NULL, '00000084', '1'),(NULL, 5, '2020-11-05', NULL, NULL, NULL, NULL, NULL, 12, 2, NULL, 26, 274, NULL, 3, NULL, NULL, NULL),(NULL, 8, '2020-11-03', 'UJI GBLK', 'Bandung', '0012-12-12', 'Bandung', '12345678', 12, 2, '1234543212345654', 21, 222, 60, 3, NULL, 'AT816784', NULL),(32, 8, '2020-11-04', 'akaka', 'kaka', '0010-12-01', '11', '123456789', 12, 2, '0000000000000002', 22, 232, 60, 3, NULL, 'PN342495', '1'),(NULL, 11, '2020-11-05', NULL, NULL, NULL, NULL, NULL, 12, 2, NULL, 26, 280, NULL, 3, NULL, NULL, NULL),(29, 11, '2020-11-04', 'kkakaka', 'bandung', '0012-12-12', 'sasas', '12345678909', 12, 2, '1234567891123456', 22, 235, 60, 3, NULL, 'FP240253', '2'),(NULL, 12, '2020-11-05', NULL, NULL, NULL, NULL, NULL, 12, 2, NULL, 26, 281, NULL, 3, NULL, NULL, NULL),(36, 10, '2020-11-05', 'UJI GBLK', 'Bandung', '0012-12-12', 'Bandung', '12345678', 12, 2, '1234543212345654', 26, 279, 10, 3, NULL, 'KB724044', '2'),(NULL, 2, '2020-11-03', NULL, NULL, NULL, NULL, NULL, 13, 8, NULL, 24, 249, NULL, 2, NULL, NULL, NULL),(NULL, 7, '2020-11-03', NULL, NULL, NULL, NULL, NULL, 12, 2, NULL, 22, 231, NULL, 3, NULL, 'GF406872', NULL),(NULL, 4, '2020-11-03', NULL, NULL, NULL, NULL, NULL, 13, 8, NULL, 24, 251, NULL, 2, NULL, NULL, NULL),(NULL, 5, '2020-11-03', NULL, NULL, NULL, NULL, NULL, 13, 8, NULL, 24, 252, NULL, 2, NULL, NULL, NULL),(NULL, 6, '2020-11-03', NULL, NULL, NULL, NULL, NULL, 13, 8, NULL, 24, 253, NULL, 2, NULL, NULL, NULL),(NULL, 5, '2020-11-03', NULL, NULL, NULL, NULL, NULL, 12, 2, NULL, 21, 219, NULL, 3, NULL, NULL, NULL),(NULL, 7, '2020-11-03', NULL, NULL, NULL, NULL, NULL, 12, 2, NULL, 21, 221, NULL, 3, NULL, NULL, NULL),(NULL, 8, '2020-11-03', NULL, NULL, NULL, NULL, NULL, 13, 8, NULL, 24, 255, NULL, 2, NULL, NULL, NULL),(NULL, 9, '2020-11-03', NULL, NULL, NULL, NULL, NULL, 13, 8, NULL, 24, 256, NULL, 2, NULL, NULL, NULL),(NULL, 10, '2020-11-03', NULL, NULL, NULL, NULL, NULL, 13, 8, NULL, 24, 257, NULL, 2, NULL, NULL, NULL),(NULL, 1, '2020-11-04', NULL, NULL, NULL, NULL, NULL, 13, 8, NULL, 25, 258, NULL, 2, NULL, NULL, NULL),(NULL, 2, '2020-11-04', NULL, NULL, NULL, NULL, NULL, 12, 2, NULL, 22, 226, NULL, 3, NULL, NULL, NULL),(NULL, 2, '2020-11-04', NULL, NULL, NULL, NULL, NULL, 13, 8, NULL, 25, 259, NULL, 2, NULL, NULL, NULL),(NULL, 3, '2020-11-04', NULL, NULL, NULL, NULL, NULL, 13, 8, NULL, 25, 260, NULL, 2, NULL, NULL, NULL),(NULL, 4, '2020-11-04', NULL, NULL, NULL, NULL, NULL, 13, 8, NULL, 25, 261, NULL, 2, NULL, NULL, NULL),(NULL, 6, '2020-11-04', NULL, NULL, NULL, NULL, NULL, 12, 2, NULL, 22, 230, NULL, 3, NULL, NULL, NULL),(NULL, 6, '2020-11-04', NULL, NULL, NULL, NULL, NULL, 13, 8, NULL, 25, 263, NULL, 2, NULL, NULL, NULL),(NULL, 3, '2020-11-03', NULL, NULL, NULL, NULL, NULL, 12, 2, NULL, 21, 217, 60, 3, NULL, NULL, NULL),(NULL, 7, '2020-11-04', NULL, NULL, NULL, NULL, NULL, 13, 8, NULL, 25, 264, NULL, 2, NULL, NULL, NULL),(NULL, 8, '2020-11-04', NULL, NULL, NULL, NULL, NULL, 13, 8, NULL, 25, 265, NULL, 2, NULL, NULL, NULL),(NULL, 1, '2020-11-03', NULL, NULL, NULL, NULL, NULL, 12, 2, NULL, 21, 215, NULL, 3, NULL, NULL, NULL),(NULL, 1, '2020-11-03', 'UJI GBLK', 'Bandung', '0012-12-12', 'Bandung', '12345678', 12, 2, '1234543212345654', 22, 225, NULL, 3, NULL, 'AT816784', NULL),(NULL, 9, '2020-11-04', NULL, NULL, NULL, NULL, NULL, 13, 8, NULL, 25, 266, NULL, 2, NULL, NULL, NULL),(NULL, 11, '2020-11-04', NULL, NULL, NULL, NULL, NULL, 13, 8, NULL, 25, 268, NULL, 2, NULL, NULL, NULL),(NULL, 12, '2020-11-04', NULL, NULL, NULL, NULL, NULL, 13, 8, NULL, 25, 269, NULL, 2, NULL, NULL, NULL),(NULL, 9, '2020-11-04', NULL, NULL, NULL, NULL, NULL, 12, 2, NULL, 22, 233, NULL, 3, NULL, NULL, NULL),(NULL, 10, '2020-11-04', NULL, NULL, NULL, NULL, NULL, 12, 2, NULL, 22, 234, NULL, 3, NULL, NULL, NULL),(NULL, 3, '2020-11-03', 'UJI GBLK', 'Bandung', '0012-12-12', 'Bandung', '12345678', 12, 2, '1234543212345654', 22, 227, NULL, 3, NULL, 'AT816784', NULL),(NULL, 5, '2020-11-03', 'UJI GBLK', 'Bandung', '0012-12-12', 'Bandung', '12345678', 12, 2, '1234543212345654', 22, 229, NULL, 3, NULL, 'AT816784', NULL),(NULL, 2, '2020-11-03', 'UJI GBLK', 'Bandung', '0012-12-12', 'Bandung', '12345678', 12, 2, '1234543212345654', 21, 216, NULL, 3, NULL, 'AT816784', NULL),(NULL, 6, '2020-11-03', 'akaka', 'kaka', '0010-12-01', '11', '123456789', 12, 2, '0000000000000002', 21, 220, NULL, 3, NULL, 'PN342495', NULL),(NULL, 4, '2020-11-03', 'UJI GBLK', 'Bandung', '0012-12-12', 'Bandung', '12345678', 12, 2, '1234543212345654', 21, 218, 50, 3, NULL, 'AT816784', NULL),(30, 4, '2020-11-04', 'akaka', 'kaka', '0010-12-01', '11', '123456789', 12, 2, '0000000000000000', 22, 228, 50, 3, NULL, 'QA905603', '0'),(33, 4, '2020-11-05', 'asca', 'sdasd', '0123-03-12', 'asd', '123123', 12, 2, '1234567890987655', 26, 273, 50, 3, NULL, 'GF406872', '0'),(27, 9, '2020-11-05', '', '', '', '', '', 12, 2, '', 26, 278, 10, 3, NULL, '00000132', '2'),(14, 3, '2020-11-05', 'KAKA', '', '', '', '', 12, 2, '12', 26, 272, 10, 3, NULL, '00000119', '1'),(14, 6, '2020-11-05', 'KAKA', '', '', '', '', 12, 2, '12', 26, 275, 10, 3, NULL, '00000119', '2'),(14, 2, '2020-11-05', 'KAKA', '', '', '', '', 12, 2, '12', 26, 271, 10, 3, NULL, '00000119', '1'),(14, 8, '2020-11-05', 'KAKA', '', '', '', '', 12, 2, '12', 26, 277, 10, 3, NULL, '00000119', '5'),(NULL, 1, '2020-11-05', '', '', '', '', '', 12, 2, '', 26, 270, 10, 3, NULL, '00000132', '0');
COMMIT;
BEGIN;
LOCK TABLE "public"."condition" IN SHARE MODE;
DELETE FROM "public"."condition";
INSERT INTO "public"."condition" ("id_condition","condition") VALUES (1, 'Aktif'),(2, 'Tidak Aktif');
COMMIT;
BEGIN;
LOCK TABLE "public"."counter_status" IN SHARE MODE;
DELETE FROM "public"."counter_status";
INSERT INTO "public"."counter_status" ("id_status","status_code","status_name") VALUES (1, 10, 'Queue'),(2, 20, 'Next'),(3, 30, 'Call'),(4, 40, 'Served'),(5, 50, 'Done'),(6, 60, 'Missed');
COMMIT;
BEGIN;
LOCK TABLE "public"."divisi_witel" IN SHARE MODE;
DELETE FROM "public"."divisi_witel";
COMMIT;
BEGIN;
LOCK TABLE "public"."dokter" IN SHARE MODE;
DELETE FROM "public"."dokter";
INSERT INTO "public"."dokter" ("id_dokter","nama_dokter","kode_dokter","image_dokter") VALUES (4, 'ReyNaldi', 'RN', 'azy.png'),(9, 'roy', 'RY', 'budi.png'),(8, 'Dokter Kulit & Kelamin', 'DKK', 'azy.jpg'),(2, 'Dokter Gigi', 'DGG', 'rahmat.png'),(3, 'Dokter Mata', 'DM', 'asron.png'),(7, 'Dokter Umum', 'DU', 'azy.png');
COMMIT;
BEGIN;
LOCK TABLE "public"."dokter_to_poli" IN SHARE MODE;
DELETE FROM "public"."dokter_to_poli";
INSERT INTO "public"."dokter_to_poli" ("id_dokter","nama_dokter","id_poli","id_condition","kode_dokter","create_date","image_dokter") VALUES (3, 'Dokter Mata', 11, 1, 'DM', '2020-10-21', 'asron.png'),(7, 'Dokter Umum', 9, 2, 'DU', '2020-10-27', 'azy.png'),(8, 'Dokter Kulit & Kelamin', 13, 2, 'DKK', '2020-10-27', 'budi.png'),(2, 'Dokter Gigi', 12, 2, 'DGG', '2020-10-21', 'rahmat.png');
COMMIT;
BEGIN;
LOCK TABLE "public"."master_parameter" IN SHARE MODE;
DELETE FROM "public"."master_parameter";
INSERT INTO "public"."master_parameter" ("idm_parameter","param_type","param_val1","param_val2","param_val3","param_desc","param_parent") VALUES (1, 'USER_STATUS', 'ACTIVE', '10', NULL, 'USER ACTIVE GANS', NULL),(2, 'USER_STATUS', 'NOT ACTIVE', '20', NULL, 'USER NOT ACTIVE GANS', NULL),(3, 'USER_STATUS', 'BLOCKED', '30', NULL, 'USER NA DIBLOCK', NULL),(4, 'INPUT_TYPE', 'INPUT', '1', NULL, NULL, NULL),(5, 'INPUT_TYPE', 'TEXTAREA', '2', NULL, NULL, NULL),(6, 'INPUT_OPTION', 'SELECT_OPTION', '3', NULL, NULL, NULL),(7, 'INPUT_RADIO', 'RADION_BUTTON', '4', NULL, NULL, NULL),(8, 'TYPE_DATA', 'I_VARCHAR', '1', NULL, 'JENIS INPUTAN VARCHAR', NULL),(9, 'TYPE_DATA', 'I_NUMBER', '2', NULL, 'JENIS INPUTAN NUMBER', NULL);
COMMIT;
BEGIN;
LOCK TABLE "public"."pasien" IN SHARE MODE;
DELETE FROM "public"."pasien";
INSERT INTO "public"."pasien" ("id_pasien","nama","tempat_lahir","tanggal_lahir","alamat","no_hp","ktp","create_date","no_rekam_medis") VALUES (6, 'huha', '', '', '', '', '1234567891', '2020-09-25', '00000079'),(7, 'ass', 'ass', '', '', '', '1234567891', '2020-09-25', '00000080'),(8, 'ass', 'ass', '', '', '', '1234567891', '2020-09-25', '00000081'),(9, 'ass', 'ass', '', '', '', '1234567891', '2020-09-25', '00000082'),(10, 'azy', '', '', '', '', '1234567891', '2020-09-25', '00000083'),(11, 'Hehe', '', '', '', '', '1234567891', '2020-09-25', '00000084'),(12, 'qsw', '', '', '', '', '1234567891', '2020-09-25', '00000085'),(13, 'de', '', '', '', '', '21321', '2020-09-25', '00000086'),(14, 'KAKA', '', '', '', '', '12', '2020-09-27', '00000119'),(16, 'Indra', 'Bandung', '', '', '', '1235466', '2020-09-28', '00000121'),(18, '123', '', '', '', '', '1234567891', '2020-09-28', '00000123'),(20, 'HAHAHA', '', '', '', '', '12345', '2020-09-28', '00000125'),(22, 'Rey', '123123', '123', '12313', '', '3211111111111111', '2020-09-29', '00000127'),(23, 'Azy', '', '', '', '', '1234567890987654', '2020-09-29', '00000128'),(24, 'ikbal', '', '', '', '', '2312312', '2020-10-12', '00000129'),(25, 'azy', '', '', '', '', '11', '2020-10-14', '00000130'),(26, '', '', '', '', '', '', '2020-10-21', '00000131'),(27, '', '', '', '', '', '', '2020-10-21', '00000132'),(28, 'AHEF', 'HIJA', '1998-02-12', 'Bandung', '089898989', '1234567890123456', '2020-10-21', 'QW4424'),(29, 'kkakaka', 'bandung', '0012-12-12', 'sasas', '12345678909', '1234567891123456', '2020-10-21', 'FP240253'),(30, 'akaka', 'kaka', '0010-12-01', '11', '123456789', '0000000000000000', '2020-10-21', 'QA905603'),(31, 'akaka', 'kaka', '0010-12-01', '11', '123456789', '0000000000000001', '2020-10-21', 'TZ711240'),(32, 'akaka', 'kaka', '0010-12-01', '11', '123456789', '0000000000000002', '2020-10-21', 'PN342495'),(33, 'asca', 'sdasd', '0123-03-12', 'asd', '123123', '1234567890987655', '2020-10-23', 'GF406872'),(3, 'AZY', 'Bandung', '0012-12-12', 'Bandung', '123456789098', '1234567890909090', '2020-09-25', '00000076'),(4, 'REY', 'Bandung', '0012-12-12', 'BANDUNG', '123456789', '2121234567890987', '2020-09-25', '00000077'),(34, 'Ret', 'Bandung', '0012-12-12', 'Bandung', '12121212', '1234567891654321', '2020-10-27', 'CU644262'),(35, 'UJI GBLK', 'Bandung', '0012-12-12', 'Bandung', '12345678', '1234543212345654', '2020-11-03', 'AT816784'),(36, 'UJI GBLK', 'Bandung', '0012-12-12', 'Bandung', '12345678', '1234543212345654', '2020-11-03', 'KB724044');
COMMIT;
BEGIN;
LOCK TABLE "public"."poli" IN SHARE MODE;
DELETE FROM "public"."poli";
INSERT INTO "public"."poli" ("id_poli","nama_poli","kode_poli","deskripsi_poli","image_poli") VALUES (9, 'Umum', 'UM', 'Poli umum merupakan salah satu dari jenis layanan yang memberikan pelayanan kedokteran berupa pemeriksaan kesehatan, pengobatan dan penyuluhan kepada pasien atau masyarakat agar tidak terjadi penularan dan komplikasi penyakit, serta meningkatkan pengetahuan dan kesadaran masyarakat dalam bidang kesehatan.', 'umum.png'),(11, 'Mata', 'MT', 'Poli mata merupakan suatu instalasi yang memberikan pelayanan mata secara menyeluruh kepada masyarakat secara nyaman dan terpercaya, yang meliputi aspek preventif, kuratif, promotif dan rehabilitatif bedah maupun non bedah dengan harapan dapat menurunkan angka kebutaan di Indonesia. kebutuhan masyarakat.', 'mata.png'),(13, 'Kulit & Kelamin', 'KK', 'Poliklinik Kulit & Kelamin adalah klinik yang melayani konsultasi kesehatan kulit dan kelamin, perawatan kecantikan untuk pasien dari berbagai usia yang memiliki permasalahan kulit maupun penyakit kelamin, dengan didukung dokter ahli spesialis kulit dan kelamin yang kompeten. Klinik ini ditunjang dengan peralatan dan fasilitas ruang konsultasi, tindakan, perawatan kulit dan kecantikan yang lengkap.', 'kulit.png'),(12, 'GIGI', 'GG', 'Poli Gigi, berupa pelayanan gigi yaitu pemeriksaan, pengobatan, dan konsultasi medis, premedikasi, kegawatdaruratan oro-dental, pencabutan gigi sulung (topical, infiltrasi), pencabutan gigi permanen tanpa penyulit, obat pasca ekstraksi, tumpatan komposit, glass ionomer cement (GIC), scalling (pembersihan karang gigi), serta pelayanan gigi lain yang dapat dilakukan di fasilitas kesehatan tingkat pertama sesuai Panduan Praktik Klinik (PPK) Dokter Gigi.', 'gigi.png');
COMMIT;
BEGIN;
LOCK TABLE "public"."register_antrian" IN SHARE MODE;
DELETE FROM "public"."register_antrian";
INSERT INTO "public"."register_antrian" ("id_registerantrian","antrian_all","create_date","jam_mulai","waktu_antrian") VALUES (22, 11, '2020-11-04', '6:11', '0:11'),(21, 10, '2020-11-03', '6:12', '0:12'),(23, 12, '2020-11-04', '6:21', '0:12'),(24, 10, '2020-11-03', '6:00', '0:06'),(25, 12, '2020-11-04', '6:12', '0:01'),(26, 12, '2020-11-05', '6:21', '0:12');
COMMIT;
BEGIN;
LOCK TABLE "public"."register_dokter" IN SHARE MODE;
DELETE FROM "public"."register_dokter";
INSERT INTO "public"."register_dokter" ("id_register","id_poli","id_dokter","id_ruang","antrian_all","create_date","id_condition","jam_mulai","waktu_antrian") VALUES (22, 12, 2, 3, 11, '2020-11-03', 1, '6:11', '0:11'),(21, 12, 2, 3, 11, '2020-11-04', 1, '6:12', '0:12'),(24, 13, 8, 2, 10, '2020-11-03', 1, '6:00', '0:06'),(25, 13, 8, 2, 12, '2020-11-04', 1, '6:12', '0:01'),(26, 12, 2, 3, 12, '2020-11-05', 1, '6:21', '0:12');
COMMIT;
BEGIN;
LOCK TABLE "public"."ruang" IN SHARE MODE;
DELETE FROM "public"."ruang";
INSERT INTO "public"."ruang" ("id_ruang","nama_ruang","id_poli","id_condition","lantai") VALUES (4, '5', 9, 2, '2'),(2, '1', 13, 2, '1'),(3, '1', 12, 2, '2');
COMMIT;
BEGIN;
LOCK TABLE "public"."user_data_access" IN SHARE MODE;
DELETE FROM "public"."user_data_access";
INSERT INTO "public"."user_data_access" ("access_id","access_name","access_type","access_code","access_desc","access_action","access_controller") VALUES (1, 'DELETE TEST DATA', 'ACTION', 10, 'TEST FUNCTION', NULL, NULL),(2, 'MENU TEST DATA', 'MENU', 20, 'TEST MENU', NULL, NULL),(3, 'USER MANAGEMENT', 'MENU', 30, 'MENU USER MANAMEGENT', 'usermanagement', 'UserController');
COMMIT;
BEGIN;
LOCK TABLE "public"."user_data_header" IN SHARE MODE;
DELETE FROM "public"."user_data_header";
INSERT INTO "public"."user_data_header" ("iduser","username","password","name","role","status","deviceid","accessToken","create_dtm","retries","update_date","email","authKey","nik","lokasi_p","domain_p","regional_id","witel_id","last_update") VALUES (5, '930159', NULL, 'Ratna Wulan Sari', 30, 10, NULL, '$2y$10$e5IlkVkq/7zxhuVgORVH6uY850T5NiY.MZzoVk8ASnEfRr.6ooira', '2020-01-14 01:16:32', NULL, NULL, 'ratna.sari@telkom.co.id', 'a0e3e79e631b49115bd0cb927120f9eb', '930159', 'JAKARTA', 'Telkom', NULL, NULL, NULL),(9, 'witels', '758a27752fa96f0e612e8c75b0d619a9', 'witels', 20, 10, NULL, NULL, '2020-06-21 22:50:03', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, '351', NULL),(6, 'test1', '5a105e8b9d40e1329780d62ea2265d8a', 'test1', 10, 30, NULL, NULL, '2020-06-21 21:57:41', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),(7, 'test1', '5a105e8b9d40e1329780d62ea2265d8a', 'test1', 10, 20, NULL, NULL, '2020-06-21 21:57:44', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),(8, 'test2', 'ad0234829205b9033196ba818f7a872b', 'test2', 20, 10, NULL, NULL, '2020-06-21 21:59:39', 'NULL', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),(1, 'danirusdanx', '5036592afd6e403097c37eef12ad8285', 'danirusdan', 10, 10, NULL, NULL, '2017-07-09 22:30:23', 'NULL', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),(4, 'adminx', '$2y$10$7NnmhGtMZJTbS7gRVl/c0OIrCK29/rurfhBdZAKlgN8y/WcV8nbRa', 'Saya Karyawan', 30, 30, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),(3, 'danirusdan', '$2y$10$7NnmhGtMZJTbS7gRVl/c0OIrCK29/rurfhBdZAKlgN8y/WcV8nbRa', 'danirusdan', 10, 10, NULL, '$2y$10$zDNOrVtdYYGE3yBINaDDb.2506QHLSvGHn/hMxtqAkl21dWKEWsAG', NULL, '2', NULL, NULL, 'c46714daa0d91976579686bcad938849', NULL, NULL, NULL, NULL, NULL, NULL),(2, 'admin', '21232f297a57a5a743894a0e4a801fc3', 'Atang', 10, 10, NULL, NULL, '2017-08-10 10:29:40', 'NULL', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
COMMIT;
BEGIN;
LOCK TABLE "public"."user_data_map" IN SHARE MODE;
DELETE FROM "public"."user_data_map";
INSERT INTO "public"."user_data_map" ("map_id","access_code","role_code","access_status","last_update") VALUES (4, 10, 20, 'TRUE', '2020-06-21 21:12:51.219377'),(5, 20, 20, 'TRUE', '2020-06-21 21:13:06.007902'),(1, 10, 10, 'TRUE', '2020-06-21 21:14:13.680576'),(3, 30, 10, 'TRUE', '2020-06-21 21:14:22.116003'),(2, 20, 10, 'FALSE', '2020-06-21 21:47:09.580874'),(6, 30, 20, 'FALSE', '2020-06-23 17:10:04.467554');
COMMIT;
BEGIN;
LOCK TABLE "public"."user_data_role" IN SHARE MODE;
DELETE FROM "public"."user_data_role";
INSERT INTO "public"."user_data_role" ("access_role_id","name","access_role_code","description") VALUES (1, 'MASTER', 10, 'BISA SAGALA EDANS'),(2, 'ADMIN', 20, 'ADMIN'),(3, 'REGIONAL', 30, 'EDAN1'),(4, 'WITEL', 40, 'UWU');
COMMIT;
BEGIN;
LOCK TABLE "public"."user_data_session" IN SHARE MODE;
DELETE FROM "public"."user_data_session";
INSERT INTO "public"."user_data_session" ("session_id","name","modified","lifetime","data","owner") VALUES ('5v0oot9tprnpatk4ike1uv2nho', 'KHANSIA', 1603425445, 86400, '__ZF|a:5:{s:20:"_REQUEST_ACCESS_TIME";d:1603425445.550543;s:6:"_VALID";a:1:{s:25:"Zend\Session\Validator\Id";s:26:"5v0oot9tprnpatk4ike1uv2nho";}s:3:"_LT";i:86400;s:3:"_LA";i:1603425445;s:6:"_OWNER";s:1:"2";}__KHANSIA|a:14:{s:14:"token_keamanan";s:32:"ecc1e60894d8f514b6a2c5531683484b";s:7:"baseurl";s:14:"//frontend.azy";s:7:"user_id";s:1:"2";s:9:"usernamed";s:5:"admin";s:6:"passwd";s:32:"21232f297a57a5a743894a0e4a801fc3";s:4:"name";s:5:"Atang";s:4:"role";s:2:"10";s:6:"status";s:2:"10";s:8:"deviceid";N;s:5:"token";N;s:7:"retries";s:4:"NULL";s:10:"create_dtm";s:19:"2017-08-10 10:29:40";s:6:"access";a:0:{}s:9:"role_code";s:1:"E";}', '2'),('b5u3q3b0jo0b4bqc1vfmlvom5g', 'KHANSIA', 1603251609, 86400, '__ZF|a:5:{s:20:"_REQUEST_ACCESS_TIME";d:1603251609.267616;s:6:"_VALID";a:1:{s:25:"Zend\Session\Validator\Id";s:26:"b5u3q3b0jo0b4bqc1vfmlvom5g";}s:3:"_LT";i:86400;s:3:"_LA";i:1603251609;s:6:"_OWNER";s:1:"2";}__KHANSIA|a:14:{s:14:"token_keamanan";s:32:"5c9dbb3adb4342f985ca0c23a19ce1a7";s:7:"baseurl";s:14:"//frontend.azy";s:7:"user_id";s:1:"2";s:9:"usernamed";s:5:"admin";s:6:"passwd";s:32:"21232f297a57a5a743894a0e4a801fc3";s:4:"name";s:5:"Atang";s:4:"role";s:2:"10";s:6:"status";s:2:"10";s:8:"deviceid";N;s:5:"token";N;s:7:"retries";s:4:"NULL";s:10:"create_dtm";s:19:"2017-08-10 10:29:40";s:6:"access";a:0:{}s:9:"role_code";s:1:"E";}', '2'),('blsrhft6bkanmbslp44je47e5p', 'KHANSIA', 1603101283, 86400, '__ZF|a:5:{s:20:"_REQUEST_ACCESS_TIME";d:1603101283.652502;s:6:"_VALID";a:1:{s:25:"Zend\Session\Validator\Id";s:26:"blsrhft6bkanmbslp44je47e5p";}s:3:"_LT";i:86400;s:3:"_LA";i:1603101283;s:6:"_OWNER";s:1:"2";}__KHANSIA|a:14:{s:14:"token_keamanan";s:32:"e697671b8432d4a38c84168d4cccbc06";s:7:"baseurl";s:14:"//frontend.azy";s:7:"user_id";s:1:"2";s:9:"usernamed";s:5:"admin";s:6:"passwd";s:32:"21232f297a57a5a743894a0e4a801fc3";s:4:"name";s:5:"Atang";s:4:"role";s:2:"10";s:6:"status";s:2:"10";s:8:"deviceid";N;s:5:"token";N;s:7:"retries";s:4:"NULL";s:10:"create_dtm";s:19:"2017-08-10 10:29:40";s:6:"access";a:0:{}s:9:"role_code";s:1:"E";}', '2'),('f6ie2crc5r0lmc0n6tqjdoa38f', 'KHANSIA', 1603353013, 86400, '__ZF|a:5:{s:20:"_REQUEST_ACCESS_TIME";d:1603353013.217118;s:6:"_VALID";a:1:{s:25:"Zend\Session\Validator\Id";s:26:"f6ie2crc5r0lmc0n6tqjdoa38f";}s:3:"_LT";i:86400;s:3:"_LA";i:1603353013;s:6:"_OWNER";s:1:"2";}__KHANSIA|a:14:{s:14:"token_keamanan";s:32:"682ace445148f99363041f0e0de3c642";s:7:"baseurl";s:14:"//frontend.azy";s:7:"user_id";s:1:"2";s:9:"usernamed";s:5:"admin";s:6:"passwd";s:32:"21232f297a57a5a743894a0e4a801fc3";s:4:"name";s:5:"Atang";s:4:"role";s:2:"10";s:6:"status";s:2:"10";s:8:"deviceid";N;s:5:"token";N;s:7:"retries";s:4:"NULL";s:10:"create_dtm";s:19:"2017-08-10 10:29:40";s:6:"access";a:0:{}s:9:"role_code";s:1:"E";}', '2'),('fl4scadfoa2dlcr4hjn1deiq8k', 'KHANSIA', 1603451128, 86400, '__ZF|a:5:{s:20:"_REQUEST_ACCESS_TIME";d:1603451128.516116;s:6:"_VALID";a:1:{s:25:"Zend\Session\Validator\Id";s:26:"fl4scadfoa2dlcr4hjn1deiq8k";}s:3:"_LT";i:86400;s:3:"_LA";i:1603451128;s:6:"_OWNER";s:1:"2";}__KHANSIA|a:14:{s:14:"token_keamanan";s:32:"6130b49e8af1dec8718db7e3ba1c1979";s:7:"baseurl";s:11:"//queue.azy";s:7:"user_id";s:1:"2";s:9:"usernamed";s:5:"admin";s:6:"passwd";s:32:"21232f297a57a5a743894a0e4a801fc3";s:4:"name";s:5:"Atang";s:4:"role";s:2:"10";s:6:"status";s:2:"10";s:8:"deviceid";N;s:5:"token";N;s:7:"retries";s:4:"NULL";s:10:"create_dtm";s:19:"2017-08-10 10:29:40";s:6:"access";a:0:{}s:9:"role_code";s:1:"E";}', '2'),('b1c5jfjirtkgvm1qmt637tpvm9', 'KHANSIA', 1603697098, 86400, '__ZF|a:5:{s:20:"_REQUEST_ACCESS_TIME";d:1603697098.278806;s:6:"_VALID";a:1:{s:25:"Zend\Session\Validator\Id";s:26:"b1c5jfjirtkgvm1qmt637tpvm9";}s:3:"_LT";i:86400;s:3:"_LA";i:1603697098;s:6:"_OWNER";s:1:"2";}__KHANSIA|a:14:{s:14:"token_keamanan";s:32:"0e8350efd08bba9301e2e1f612fd4c8f";s:7:"baseurl";s:11:"//queue.azy";s:7:"user_id";s:1:"2";s:9:"usernamed";s:5:"admin";s:6:"passwd";s:32:"21232f297a57a5a743894a0e4a801fc3";s:4:"name";s:5:"Atang";s:4:"role";s:2:"10";s:6:"status";s:2:"10";s:8:"deviceid";N;s:5:"token";N;s:7:"retries";s:4:"NULL";s:10:"create_dtm";s:19:"2017-08-10 10:29:40";s:6:"access";a:0:{}s:9:"role_code";s:1:"E";}', '2'),('9rfng5e1conlvfmaaevqnh28d6', 'KHANSIA', 1603813897, 86400, '__ZF|a:5:{s:20:"_REQUEST_ACCESS_TIME";d:1603813897.645092;s:6:"_VALID";a:1:{s:25:"Zend\Session\Validator\Id";s:26:"9rfng5e1conlvfmaaevqnh28d6";}s:3:"_LT";i:86400;s:3:"_LA";i:1603813897;s:6:"_OWNER";s:1:"2";}__KHANSIA|a:14:{s:14:"token_keamanan";s:32:"dc2af1d1a8bc10249d044db168a54259";s:7:"baseurl";s:11:"//queue.azy";s:7:"user_id";s:1:"2";s:9:"usernamed";s:5:"admin";s:6:"passwd";s:32:"21232f297a57a5a743894a0e4a801fc3";s:4:"name";s:5:"Atang";s:4:"role";s:2:"10";s:6:"status";s:2:"10";s:8:"deviceid";N;s:5:"token";N;s:7:"retries";s:4:"NULL";s:10:"create_dtm";s:19:"2017-08-10 10:29:40";s:6:"access";a:0:{}s:9:"role_code";s:1:"E";}', '2'),('aju3k0b41jrc9h6er99lv23cvh', 'KHANSIA', 1603790849, 86400, '__ZF|a:4:{s:20:"_REQUEST_ACCESS_TIME";d:1603790848.49262;s:6:"_VALID";a:1:{s:25:"Zend\Session\Validator\Id";s:26:"aju3k0b41jrc9h6er99lv23cvh";}s:3:"_LT";i:86400;s:3:"_LA";i:1603790848;}__KHANSIA|N;', NULL),('r2amd93mh9eua1k4lchcjm91vc', 'KHANSIA', 1603790851, 86400, '__ZF|a:5:{s:20:"_REQUEST_ACCESS_TIME";d:1603790851.917473;s:6:"_VALID";a:1:{s:25:"Zend\Session\Validator\Id";s:26:"r2amd93mh9eua1k4lchcjm91vc";}s:3:"_LT";i:86400;s:3:"_LA";i:1603790851;s:6:"_OWNER";s:1:"2";}__KHANSIA|a:14:{s:14:"token_keamanan";s:32:"93c072b9b37df7d638e9974d3a79d423";s:7:"baseurl";s:12:"//queue1.azy";s:7:"user_id";s:1:"2";s:9:"usernamed";s:5:"admin";s:6:"passwd";s:32:"21232f297a57a5a743894a0e4a801fc3";s:4:"name";s:5:"Atang";s:4:"role";s:2:"10";s:6:"status";s:2:"10";s:8:"deviceid";N;s:5:"token";N;s:7:"retries";s:4:"NULL";s:10:"create_dtm";s:19:"2017-08-10 10:29:40";s:6:"access";a:0:{}s:9:"role_code";s:1:"E";}', '2'),('jnvk15nre7gnuq634gb5vttc0q', 'KHANSIA', 1603989519, 86400, '__ZF|a:5:{s:20:"_REQUEST_ACCESS_TIME";d:1603989519.139075;s:6:"_VALID";a:1:{s:25:"Zend\Session\Validator\Id";s:26:"jnvk15nre7gnuq634gb5vttc0q";}s:3:"_LT";i:86400;s:3:"_LA";i:1603989519;s:6:"_OWNER";s:1:"2";}__KHANSIA|a:14:{s:14:"token_keamanan";s:32:"1cb2a80d0f7c6f112f5e410e76037087";s:7:"baseurl";s:11:"//queue.azy";s:7:"user_id";s:1:"2";s:9:"usernamed";s:5:"admin";s:6:"passwd";s:32:"21232f297a57a5a743894a0e4a801fc3";s:4:"name";s:5:"Atang";s:4:"role";s:2:"10";s:6:"status";s:2:"10";s:8:"deviceid";N;s:5:"token";N;s:7:"retries";s:4:"NULL";s:10:"create_dtm";s:19:"2017-08-10 10:29:40";s:6:"access";a:0:{}s:9:"role_code";s:1:"E";}', '2'),('kanl93l9t03a9pf2uao5e3r3a1', 'KHANSIA', 1604246219, 86400, '__ZF|a:5:{s:20:"_REQUEST_ACCESS_TIME";d:1604246219.057064;s:6:"_VALID";a:1:{s:25:"Zend\Session\Validator\Id";s:26:"kanl93l9t03a9pf2uao5e3r3a1";}s:3:"_LT";i:86400;s:3:"_LA";i:1604246219;s:6:"_OWNER";s:1:"2";}__KHANSIA|a:14:{s:14:"token_keamanan";s:32:"a75658f9388901137ee5438edd49642a";s:7:"baseurl";s:11:"//queue.azy";s:7:"user_id";s:1:"2";s:9:"usernamed";s:5:"admin";s:6:"passwd";s:32:"21232f297a57a5a743894a0e4a801fc3";s:4:"name";s:5:"Atang";s:4:"role";s:2:"10";s:6:"status";s:2:"10";s:8:"deviceid";N;s:5:"token";N;s:7:"retries";s:4:"NULL";s:10:"create_dtm";s:19:"2017-08-10 10:29:40";s:6:"access";a:0:{}s:9:"role_code";s:1:"E";}', '2'),('ujscjl8pq900rjihnpv12s2gmh', 'KHANSIA', 1604470200, 86400, '__ZF|a:5:{s:20:"_REQUEST_ACCESS_TIME";d:1604470200.188888;s:6:"_VALID";a:1:{s:25:"Zend\Session\Validator\Id";s:26:"ujscjl8pq900rjihnpv12s2gmh";}s:3:"_LT";i:86400;s:3:"_LA";i:1604470200;s:6:"_OWNER";s:1:"2";}__KHANSIA|a:14:{s:14:"token_keamanan";s:32:"972ae1da6c75fed4558f131337a076fa";s:7:"baseurl";s:11:"//queue.azy";s:7:"user_id";s:1:"2";s:9:"usernamed";s:5:"admin";s:6:"passwd";s:32:"21232f297a57a5a743894a0e4a801fc3";s:4:"name";s:5:"Atang";s:4:"role";s:2:"10";s:6:"status";s:2:"10";s:8:"deviceid";N;s:5:"token";N;s:7:"retries";s:4:"NULL";s:10:"create_dtm";s:19:"2017-08-10 10:29:40";s:6:"access";a:0:{}s:9:"role_code";s:1:"E";}', '2'),('i46s59b711anbcr7eg3dlfguer', 'KHANSIA', 1604556818, 86400, '__ZF|a:5:{s:20:"_REQUEST_ACCESS_TIME";d:1604556818.620847;s:6:"_VALID";a:1:{s:25:"Zend\Session\Validator\Id";s:26:"i46s59b711anbcr7eg3dlfguer";}s:3:"_LT";i:86400;s:3:"_LA";i:1604556818;s:6:"_OWNER";s:1:"2";}__KHANSIA|a:14:{s:14:"token_keamanan";s:32:"73522e776b806286ac53c2d5d3793194";s:7:"baseurl";s:11:"//queue.azy";s:7:"user_id";s:1:"2";s:9:"usernamed";s:5:"admin";s:6:"passwd";s:32:"21232f297a57a5a743894a0e4a801fc3";s:4:"name";s:5:"Atang";s:4:"role";s:2:"10";s:6:"status";s:2:"10";s:8:"deviceid";N;s:5:"token";N;s:7:"retries";s:4:"NULL";s:10:"create_dtm";s:19:"2017-08-10 10:29:40";s:6:"access";a:0:{}s:9:"role_code";s:1:"E";}', '2');
COMMIT;
ALTER TABLE "antrian" ADD CONSTRAINT "antrian_pkey" PRIMARY KEY ("id_antrian");
ALTER TABLE "antrian_pasien" ADD CONSTRAINT "antrian_pasien_pkey" PRIMARY KEY ("id_antrian");
ALTER TABLE "condition" ADD CONSTRAINT "condition_pkey" PRIMARY KEY ("id_condition");
ALTER TABLE "counter_status" ADD CONSTRAINT "counter_status_pkey" PRIMARY KEY ("id_status");
ALTER TABLE "divisi_witel" ADD CONSTRAINT "divisi_witel_pkey" PRIMARY KEY ("divisi_witel_id");
CREATE INDEX "idx_divisi_witel" ON "divisi_witel" USING btree (
  "divisi_witel_id" COLLATE "pg_catalog"."default" "pg_catalog"."text_ops" ASC NULLS LAST
);
ALTER TABLE "dokter" ADD CONSTRAINT "dokter_to_poli_copy1_pkey" PRIMARY KEY ("id_dokter");
ALTER TABLE "dokter_to_poli" ADD CONSTRAINT "dokter\_pkey" PRIMARY KEY ("id_dokter");
ALTER TABLE "master_parameter" ADD CONSTRAINT "master_parameter_pkey" PRIMARY KEY ("idm_parameter");
CREATE INDEX "index2" ON "master_parameter" USING btree (
  "idm_parameter" "pg_catalog"."int4_ops" ASC NULLS LAST
);
CREATE INDEX "index3" ON "master_parameter" USING btree (
  "param_type" COLLATE "pg_catalog"."default" "pg_catalog"."text_ops" ASC NULLS LAST
);
CREATE INDEX "index4" ON "master_parameter" USING btree (
  "param_val1" COLLATE "pg_catalog"."default" "pg_catalog"."text_ops" ASC NULLS LAST
);
CREATE INDEX "index5" ON "master_parameter" USING btree (
  "param_val2" COLLATE "pg_catalog"."default" "pg_catalog"."text_ops" ASC NULLS LAST
);
CREATE INDEX "index6" ON "master_parameter" USING btree (
  "param_val3" COLLATE "pg_catalog"."default" "pg_catalog"."text_ops" ASC NULLS LAST
);
CREATE INDEX "index7" ON "master_parameter" USING btree (
  "param_parent" "pg_catalog"."int4_ops" ASC NULLS LAST
);
ALTER TABLE "pasien" ADD CONSTRAINT "pasien_pkey" PRIMARY KEY ("id_pasien");
ALTER TABLE "poli" ADD CONSTRAINT "poli_pkey" PRIMARY KEY ("id_poli");
ALTER TABLE "register_antrian" ADD CONSTRAINT "register_antrian_pkey" PRIMARY KEY ("id_registerantrian");
ALTER TABLE "register_dokter" ADD CONSTRAINT "register_dokter_pkey" PRIMARY KEY ("id_register");
ALTER TABLE "ruang" ADD CONSTRAINT "ruang_pkey" PRIMARY KEY ("id_ruang");
ALTER TABLE "user_data_access" ADD CONSTRAINT "user_data_access_pkey" PRIMARY KEY ("access_id");
ALTER TABLE "user_data_header" ADD CONSTRAINT "user_data_header_pkey" PRIMARY KEY ("iduser");
CREATE INDEX "xiduser" ON "user_data_header" USING btree (
  "iduser" "pg_catalog"."int4_ops" ASC NULLS LAST
);
CREATE INDEX "xrole" ON "user_data_header" USING btree (
  "role" "pg_catalog"."int4_ops" ASC NULLS LAST
);
ALTER TABLE "user_data_map" ADD CONSTRAINT "user_data_map_pkey" PRIMARY KEY ("map_id");
ALTER TABLE "user_data_role" ADD CONSTRAINT "user_data_role_pkey" PRIMARY KEY ("access_role_id");
ALTER TABLE "user_data_session" ADD CONSTRAINT "user_data_session_pkey" PRIMARY KEY ("session_id");
ALTER TABLE "antrian_pasien" ADD CONSTRAINT "Poli" FOREIGN KEY ("id_poli") REFERENCES "public"."poli" ("id_poli") ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER TABLE "antrian_pasien" ADD CONSTRAINT "antrian and register" FOREIGN KEY ("id_register") REFERENCES "public"."register_dokter" ("id_register") ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER TABLE "antrian_pasien" ADD CONSTRAINT "antrian_pasien_id_ruang_fkey" FOREIGN KEY ("id_ruang") REFERENCES "public"."ruang" ("id_ruang") ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER TABLE "antrian_pasien" ADD CONSTRAINT "dokter" FOREIGN KEY ("id_dokter") REFERENCES "public"."dokter" ("id_dokter") ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER TABLE "antrian_pasien" ADD CONSTRAINT "dokter_to_poli" FOREIGN KEY ("id_dokter") REFERENCES "public"."dokter_to_poli" ("id_dokter") ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER TABLE "antrian_pasien" ADD CONSTRAINT "no_rekam_medis" FOREIGN KEY ("no_rekam_medis") REFERENCES "public"."pasien" ("no_rekam_medis") ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER TABLE "dokter_to_poli" ADD CONSTRAINT "Dokter" FOREIGN KEY ("id_dokter") REFERENCES "public"."dokter" ("id_dokter") ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER TABLE "dokter_to_poli" ADD CONSTRAINT "Poli" FOREIGN KEY ("id_poli") REFERENCES "public"."poli" ("id_poli") ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER TABLE "dokter_to_poli" ADD CONSTRAINT "condition" FOREIGN KEY ("id_condition") REFERENCES "public"."condition" ("id_condition") ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER TABLE "master_parameter" ADD CONSTRAINT "master_parameter_ibfk_1" FOREIGN KEY ("param_parent") REFERENCES "public"."master_parameter" ("idm_parameter") ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER TABLE "pasien" ADD CONSTRAINT "id_pasien" UNIQUE ("no_rekam_medis");
ALTER TABLE "ruang" ADD CONSTRAINT "condition" FOREIGN KEY ("id_condition") REFERENCES "public"."condition" ("id_condition") ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER TABLE "ruang" ADD CONSTRAINT "id_poli" FOREIGN KEY ("id_poli") REFERENCES "public"."poli" ("id_poli") ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER SEQUENCE "dokter\_id_dokter_seq"
OWNED BY "dokter_to_poli"."id_dokter";
SELECT setval('"dokter\_id_dokter_seq"', 9, true);
ALTER SEQUENCE "dokter\_id_dokter_seq" OWNER TO "postgres";
ALTER SEQUENCE "id_antrian_pasien_seq"
OWNED BY "antrian_pasien"."id_antrian";
SELECT setval('"id_antrian_pasien_seq"', 282, true);
ALTER SEQUENCE "id_antrian_pasien_seq" OWNER TO "postgres";
ALTER SEQUENCE "id_antrian_seq"
OWNED BY "antrian"."id_antrian";
SELECT setval('"id_antrian_seq"', 282, true);
ALTER SEQUENCE "id_antrian_seq" OWNER TO "postgres";
ALTER SEQUENCE "id_dokter_seq"
OWNED BY "dokter"."id_dokter";
SELECT setval('"id_dokter_seq"', 10, true);
ALTER SEQUENCE "id_dokter_seq" OWNER TO "postgres";
ALTER SEQUENCE "id_register_antrian_seq"
OWNED BY "register_antrian"."id_registerantrian";
SELECT setval('"id_register_antrian_seq"', 27, true);
ALTER SEQUENCE "id_register_antrian_seq" OWNER TO "postgres";
ALTER SEQUENCE "id_register_dokter_seq"
OWNED BY "register_dokter"."id_register";
SELECT setval('"id_register_dokter_seq"', 27, true);
ALTER SEQUENCE "id_register_dokter_seq" OWNER TO "postgres";
ALTER SEQUENCE "id_ruang_seq"
OWNED BY "ruang"."id_ruang";
SELECT setval('"id_ruang_seq"', 5, true);
ALTER SEQUENCE "id_ruang_seq" OWNER TO "postgres";
SELECT setval('"no_rekam_medis"', 133, true);
ALTER SEQUENCE "no_rekam_medis" OWNER TO "postgres";
ALTER SEQUENCE "pasien_id_pasien_seq"
OWNED BY "pasien"."id_pasien";
SELECT setval('"pasien_id_pasien_seq"', 34, true);
ALTER SEQUENCE "pasien_id_pasien_seq" OWNER TO "postgres";
ALTER SEQUENCE "poli_id_poli_seq"
OWNED BY "poli"."id_poli";
SELECT setval('"poli_id_poli_seq"', 249, true);
ALTER SEQUENCE "poli_id_poli_seq" OWNER TO "postgres";
ALTER SEQUENCE "user_data_header_iduser_seq1"
OWNED BY "user_data_header"."iduser";
SELECT setval('"user_data_header_iduser_seq1"', 2, false);
ALTER SEQUENCE "user_data_header_iduser_seq1" OWNER TO "postgres";
ALTER SEQUENCE "user_data_header_iduser_seq"
OWNED BY "user_data_header"."iduser";
SELECT setval('"user_data_header_iduser_seq"', 3, false);
ALTER SEQUENCE "user_data_header_iduser_seq" OWNER TO "postgres";
