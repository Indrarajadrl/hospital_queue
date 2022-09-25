/*
PostgreSQL Backup
Database: antrian/public
Backup Time: 2020-09-28 16:59:05
*/

DROP SEQUENCE IF EXISTS "public"."antrian_id_antrian";
DROP SEQUENCE IF EXISTS "public"."dokter\_id_dokter_seq";
DROP SEQUENCE IF EXISTS "public"."id_antrian_seq2";
DROP SEQUENCE IF EXISTS "public"."id_antrian_seq";
DROP SEQUENCE IF EXISTS "public"."id_dokter_seq";
DROP SEQUENCE IF EXISTS "public"."id_ruang_seq";
DROP SEQUENCE IF EXISTS "public"."no_rekam_medis";
DROP SEQUENCE IF EXISTS "public"."pasien_id_pasien_seq";
DROP SEQUENCE IF EXISTS "public"."poli_id_poli_seq";
DROP SEQUENCE IF EXISTS "public"."register_antrian_id_register";
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
DROP TABLE IF EXISTS "public"."regional";
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
CREATE SEQUENCE "antrian_id_antrian" 
INCREMENT 1
MINVALUE  1
MAXVALUE 9223372036854775807
START 1
CACHE 1;
CREATE SEQUENCE "dokter\_id_dokter_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 32767
START 1
CACHE 1;
CREATE SEQUENCE "id_antrian_seq2" 
INCREMENT 1
MINVALUE  1
MAXVALUE 9223372036854775807
START 1
CACHE 1;
CREATE SEQUENCE "id_antrian_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 9223372036854775807
START 1
CACHE 1;
CREATE SEQUENCE "id_dokter_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 9223372036854775807
START 1
CACHE 1
CYCLE ;
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
CREATE SEQUENCE "register_antrian_id_register" 
INCREMENT 1
MINVALUE  1
MAXVALUE 9223372036854775807
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
  "id_antrian" int2 DEFAULT nextval('antrian_id_antrian'::regclass),
  "no_antrian" int2,
  "kode_antrian" int2,
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
  "kode_antrian" varchar(255) COLLATE "pg_catalog"."default",
  "id_register" int2,
  "id_antrian" int2 NOT NULL DEFAULT nextval('id_antrian_seq2'::regclass),
  "status_code" int2,
  "id_ruang" int2,
  "waktu" time(6),
  "no_rekam_medis" varchar(20) COLLATE "pg_catalog"."default"
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
  "status_name" varchar(255) COLLATE "pg_catalog"."default"
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
  "kode_dokter" varchar(255) COLLATE "pg_catalog"."default"
)
;
ALTER TABLE "dokter" OWNER TO "postgres";
CREATE TABLE "dokter_to_poli" (
  "id_dokter" int2 NOT NULL DEFAULT nextval('"dokter\_id_dokter_seq"'::regclass),
  "nama_dokter" varchar(30) COLLATE "pg_catalog"."default",
  "id_poli" int2,
  "id_condition" int2,
  "kode_dokter" varchar(255) COLLATE "pg_catalog"."default",
  "create_date" date
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
  "id_pasien" int2 NOT NULL,
  "nama" varchar(255) COLLATE "pg_catalog"."default",
  "tempat_lahir" varchar(255) COLLATE "pg_catalog"."default",
  "tanggal_lahir" varchar(255) COLLATE "pg_catalog"."default",
  "alamat" varchar(255) COLLATE "pg_catalog"."default",
  "no_hp" varchar(255) COLLATE "pg_catalog"."default",
  "ktp" varchar(255) COLLATE "pg_catalog"."default",
  "create_date" date NOT NULL,
  "no_rekam_medis" varchar(20) COLLATE "pg_catalog"."default" NOT NULL DEFAULT lpad((nextval('no_rekam_medis'::regclass))::text, 8, '0'::text)
)
;
ALTER TABLE "pasien" OWNER TO "postgres";
CREATE TABLE "poli" (
  "id_poli" int2 NOT NULL DEFAULT nextval('poli_id_poli_seq'::regclass),
  "nama_poli" varchar(255) COLLATE "pg_catalog"."default",
  "kode_poli" varchar(5) COLLATE "pg_catalog"."default",
  "deskripsi_poli" varchar(255) COLLATE "pg_catalog"."default"
)
;
ALTER TABLE "poli" OWNER TO "postgres";
CREATE TABLE "regional" (
  "id_regional" int4 NOT NULL,
  "regional_name" varchar(30) COLLATE "pg_catalog"."default"
)
;
ALTER TABLE "regional" OWNER TO "postgres";
CREATE TABLE "register_antrian" (
  "id_register" int2 DEFAULT nextval('register_antrian_id_register'::regclass),
  "antrian_all" int2,
  "antrian_awal" int2,
  "antrian_akhir" int2,
  "create_date" date,
  "jam_mulai" varchar(6) COLLATE "pg_catalog"."default",
  "waktu_antrian" varchar(6) COLLATE "pg_catalog"."default"
)
;
ALTER TABLE "register_antrian" OWNER TO "postgres";
CREATE TABLE "register_dokter" (
  "id_register" int2 NOT NULL DEFAULT nextval('id_antrian_seq'::regclass),
  "id_poli" int2,
  "id_dokter" int2,
  "id_ruang" int2,
  "antrian_all" int2,
  "antrian_awal" int2,
  "antrian_akhir" int2,
  "create_date" date,
  "id_condition" int2,
  "jam_mulai" varchar(6) COLLATE "pg_catalog"."default",
  "waktu_antrian" varchar(6) COLLATE "pg_catalog"."default"
)
;
ALTER TABLE "register_dokter" OWNER TO "postgres";
CREATE TABLE "ruang" (
  "id_ruang" int2 NOT NULL DEFAULT nextval('id_ruang_seq'::regclass),
  "nama_ruang" varchar(255) COLLATE "pg_catalog"."default",
  "id_poli" int2,
  "id_condition" int2
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
INSERT INTO "public"."antrian" ("id_antrian","no_antrian","kode_antrian","create_date") VALUES (2102, 1, 1, '2020-09-24'),(2103, 2, 1, '2020-09-24'),(2104, 3, 1, '2020-09-24'),(2105, 4, 1, '2020-09-24'),(2106, 5, 2, '2020-09-24'),(2107, 6, 2, '2020-09-24'),(2108, 7, 2, '2020-09-24'),(2109, 8, 2, '2020-09-24'),(2110, 9, 2, '2020-09-24'),(2111, 10, 2, '2020-09-24'),(2113, 1, 1, '2020-09-25'),(2114, 2, 1, '2020-09-25'),(2115, 3, 1, '2020-09-25'),(2116, 4, 1, '2020-09-25'),(2117, 5, 2, '2020-09-25'),(2118, 6, 2, '2020-09-25'),(2119, 7, 1, '2020-09-25'),(2120, 8, 1, '2020-09-25'),(2121, 9, 1, '2020-09-25'),(2122, 10, 1, '2020-09-25'),(2123, 11, 1, '2020-09-25'),(2124, 12, 1, '2020-09-25'),(2125, 1, 1, '2020-09-25'),(2126, 2, 1, '2020-09-25'),(2127, 3, 1, '2020-09-25'),(2128, 4, 1, '2020-09-25'),(2129, 5, 2, '2020-09-25'),(2130, 6, 2, '2020-09-25'),(2131, 7, 1, '2020-09-25'),(2132, 8, 1, '2020-09-25'),(2133, 9, 1, '2020-09-25'),(2134, 10, 1, '2020-09-25'),(2135, 1, 1, '2020-09-25'),(2136, 2, 1, '2020-09-25'),(2137, 3, 1, '2020-09-25'),(2138, 4, 1, '2020-09-25'),(2139, 5, 2, '2020-09-25'),(2140, 6, 2, '2020-09-25'),(2141, 7, 1, '2020-09-25'),(2142, 8, 1, '2020-09-25'),(2143, 9, 1, '2020-09-25'),(2144, 10, 1, '2020-09-25'),(2145, 1, 1, '2020-09-25'),(2146, 2, 1, '2020-09-25'),(2147, 3, 1, '2020-09-25'),(2148, 4, 1, '2020-09-25'),(2149, 5, 2, '2020-09-25'),(2150, 6, 2, '2020-09-25'),(2151, 7, 1, '2020-09-25'),(2152, 8, 1, '2020-09-25'),(2153, 9, 1, '2020-09-25'),(2154, 10, 1, '2020-09-25'),(2155, 1, 1, '2020-09-25'),(2156, 2, 1, '2020-09-25'),(2157, 3, 1, '2020-09-25'),(2158, 4, 1, '2020-09-25'),(2159, 5, 1, '2020-09-25'),(2160, 6, 1, '2020-09-25'),(2161, 7, 2, '2020-09-25'),(2162, 8, 2, '2020-09-25'),(2163, 9, 1, '2020-09-25'),(2164, 10, 1, '2020-09-25'),(2165, 1, 1, '2020-09-25'),(2166, 2, 1, '2020-09-25'),(2167, 3, 1, '2020-09-25'),(2168, 4, 1, '2020-09-25'),(2169, 5, 1, '2020-09-25'),(2170, 6, 2, '2020-09-25'),(2171, 7, 2, '2020-09-25'),(2172, 8, 1, '2020-09-25'),(2173, 9, 1, '2020-09-25'),(2174, 10, 1, '2020-09-25');
COMMIT;
BEGIN;
LOCK TABLE "public"."antrian_pasien" IN SHARE MODE;
DELETE FROM "public"."antrian_pasien";
INSERT INTO "public"."antrian_pasien" ("id_pasien","no_antrian","create_date","nama","tempat_lahir","tanggal_lahir","alamat","no_hp","id_poli","id_dokter","ktp","kode_antrian","id_register","id_antrian","status_code","id_ruang","waktu","no_rekam_medis") VALUES (10, 1, '2020-09-28', 'azy', '', '', '', '', 9, 17, '1234567891', '1', 249, 10, 10, 16, NULL, '00000083'),(NULL, 5, '2020-09-25', NULL, NULL, NULL, NULL, NULL, 12, 23, NULL, '1', 255, 21, NULL, 22, NULL, NULL),(NULL, 5, '2020-09-27', NULL, NULL, '', '', '', 11, 20, NULL, '1', 252, 1, 10, 19, NULL, NULL),(14, 1, '2020-09-28', 'KAKA', '', '', '', '', 11, 20, '12', '1', 252, 11, 10, 19, NULL, '00000119'),(NULL, 3, '2020-09-27', NULL, NULL, '', '', '', 13, 25, NULL, '1', 256, 2, 10, 23, NULL, NULL),(21, 2, '2020-09-28', 'INDRA', '', '', '', '', 11, 20, '', '2', 252, 12, 10, 19, NULL, '00000126'),(16, 3, '2020-09-28', 'Indra', 'Bandung', '', '', '', 11, 20, '1235466', '2', 252, 13, 10, 19, NULL, '00000121'),(NULL, 2, '2020-09-27', NULL, NULL, '', '', '', 9, 19, NULL, '1', 251, 3, 10, 18, NULL, NULL),(NULL, 7, '2020-09-28', NULL, NULL, '', '', '', 11, 20, NULL, '1', 252, 4, 10, 19, NULL, NULL),(NULL, 6, '2020-09-28', NULL, NULL, '', '', '', 11, 20, NULL, '1', 252, 5, 10, 19, NULL, NULL),(NULL, 3, '2020-09-27', NULL, NULL, '', '', '', 9, 17, NULL, '1', 249, 6, 10, 16, NULL, NULL),(NULL, 4, '2020-09-27', NULL, NULL, '', '', '', 9, 19, NULL, '1', 251, 7, 10, 18, NULL, NULL),(NULL, 5, '2020-09-27', NULL, NULL, '', '', '', 9, 19, NULL, '2', 251, 8, 10, 18, NULL, NULL),(NULL, 8, '2020-09-27', NULL, NULL, '', '', '', 11, 20, NULL, '1', 252, 9, 10, 19, NULL, NULL),(NULL, 1, '2020-09-27', NULL, NULL, '', '', '', 13, 25, NULL, '1', 256, 14, 10, 23, NULL, NULL),(NULL, 4, '2020-09-27', NULL, NULL, '', '', '', 11, 20, NULL, '1', 252, 15, 10, 19, NULL, NULL),(NULL, 2, '2020-09-27', NULL, NULL, '', '', '', 13, 25, NULL, '1', 256, 16, 10, 23, NULL, NULL),(NULL, 1, '2020-09-25', NULL, NULL, NULL, NULL, NULL, 12, 23, NULL, '1', 255, 17, NULL, 22, NULL, NULL),(NULL, 2, '2020-09-25', NULL, NULL, NULL, NULL, NULL, 12, 23, NULL, '1', 255, 18, NULL, 22, NULL, NULL),(NULL, 3, '2020-09-25', NULL, NULL, NULL, NULL, NULL, 12, 23, NULL, '1', 255, 19, NULL, 22, NULL, NULL),(NULL, 4, '2020-09-25', NULL, NULL, NULL, NULL, NULL, 12, 23, NULL, '1', 255, 20, NULL, 22, NULL, NULL);
COMMIT;
BEGIN;
LOCK TABLE "public"."condition" IN SHARE MODE;
DELETE FROM "public"."condition";
INSERT INTO "public"."condition" ("id_condition","condition") VALUES (1, 'enable'),(2, 'disable');
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
INSERT INTO "public"."dokter" ("id_dokter","nama_dokter","kode_dokter") VALUES (17, 'Azy', 'AZ'),(19, 'Rey', 'RY'),(20, 'Dr. usep A', 'UA'),(21, 'LALA', 'LL'),(22, 'PAPA', 'PP'),(23, 'yaya', 'YY'),(25, 'WAWN', 'WW'),(26, 'YAYA', 'YY');
COMMIT;
BEGIN;
LOCK TABLE "public"."dokter_to_poli" IN SHARE MODE;
DELETE FROM "public"."dokter_to_poli";
INSERT INTO "public"."dokter_to_poli" ("id_dokter","nama_dokter","id_poli","id_condition","kode_dokter","create_date") VALUES (17, 'Azy', 9, 2, 'AZ', '2020-09-24'),(19, 'Rey', 9, 2, 'RY', '2020-09-25'),(26, 'YAYA', 13, 1, 'YY', '2020-09-25'),(20, 'Dr. usep A', 11, 2, 'UA', '2020-09-25'),(21, 'LALA', 11, 2, 'LL', '2020-09-25'),(25, 'WAWN', 13, 2, 'WW', '2020-09-25');
COMMIT;
BEGIN;
LOCK TABLE "public"."master_parameter" IN SHARE MODE;
DELETE FROM "public"."master_parameter";
INSERT INTO "public"."master_parameter" ("idm_parameter","param_type","param_val1","param_val2","param_val3","param_desc","param_parent") VALUES (1, 'USER_STATUS', 'ACTIVE', '10', NULL, 'USER ACTIVE GANS', NULL),(2, 'USER_STATUS', 'NOT ACTIVE', '20', NULL, 'USER NOT ACTIVE GANS', NULL),(3, 'USER_STATUS', 'BLOCKED', '30', NULL, 'USER NA DIBLOCK', NULL),(4, 'INPUT_TYPE', 'INPUT', '1', NULL, NULL, NULL),(5, 'INPUT_TYPE', 'TEXTAREA', '2', NULL, NULL, NULL),(6, 'INPUT_OPTION', 'SELECT_OPTION', '3', NULL, NULL, NULL),(7, 'INPUT_RADIO', 'RADION_BUTTON', '4', NULL, NULL, NULL),(8, 'TYPE_DATA', 'I_VARCHAR', '1', NULL, 'JENIS INPUTAN VARCHAR', NULL),(9, 'TYPE_DATA', 'I_NUMBER', '2', NULL, 'JENIS INPUTAN NUMBER', NULL);
COMMIT;
BEGIN;
LOCK TABLE "public"."pasien" IN SHARE MODE;
DELETE FROM "public"."pasien";
INSERT INTO "public"."pasien" ("id_pasien","nama","tempat_lahir","tanggal_lahir","alamat","no_hp","ktp","create_date","no_rekam_medis") VALUES (1, 'qwqw', '', '', '', '', '1234567891', '2020-09-25', '00000074'),(2, 'huha', '', '', '', '', '3224', '2020-09-25', '00000075'),(3, 'qqq', '', '', '', '', '2131', '2020-09-25', '00000076'),(4, 'fewf', '', '', '', '', '212', '2020-09-25', '00000077'),(5, 'qweq', '', '', '', '', '1234567891', '2020-09-25', '00000078'),(6, 'huha', '', '', '', '', '1234567891', '2020-09-25', '00000079'),(7, 'ass', 'ass', '', '', '', '1234567891', '2020-09-25', '00000080'),(8, 'ass', 'ass', '', '', '', '1234567891', '2020-09-25', '00000081'),(9, 'ass', 'ass', '', '', '', '1234567891', '2020-09-25', '00000082'),(10, 'azy', '', '', '', '', '1234567891', '2020-09-25', '00000083'),(11, 'Hehe', '', '', '', '', '1234567891', '2020-09-25', '00000084'),(12, 'qsw', '', '', '', '', '1234567891', '2020-09-25', '00000085'),(13, 'de', '', '', '', '', '21321', '2020-09-25', '00000086'),(14, 'KAKA', '', '', '', '', '12', '2020-09-27', '00000119'),(15, '', '', '', '', '', '', '2020-09-27', '00000120'),(16, 'Indra', 'Bandung', '', '', '', '1235466', '2020-09-28', '00000121'),(17, 'ANJING', '', '', '', '', '1234567891', '2020-09-28', '00000122'),(18, '123', '', '', '', '', '1234567891', '2020-09-28', '00000123'),(19, 'ANJING', '', '', '', '', '123', '2020-09-28', '00000124'),(20, 'HAHAHA', '', '', '', '', '12345', '2020-09-28', '00000125'),(21, 'INDRA', '', '', '', '', '', '2020-09-28', '00000126');
COMMIT;
BEGIN;
LOCK TABLE "public"."poli" IN SHARE MODE;
DELETE FROM "public"."poli";
INSERT INTO "public"."poli" ("id_poli","nama_poli","kode_poli","deskripsi_poli") VALUES (9, 'Umum', 'UM', ''),(11, 'Mata', 'MT', '1111'),(13, 'Kulit', 'KT', 'asdasd'),(12, 'GIGI', 'GG', 'ASDASDASD');
COMMIT;
BEGIN;
LOCK TABLE "public"."regional" IN SHARE MODE;
DELETE FROM "public"."regional";
COMMIT;
BEGIN;
LOCK TABLE "public"."register_antrian" IN SHARE MODE;
DELETE FROM "public"."register_antrian";
INSERT INTO "public"."register_antrian" ("id_register","antrian_all","antrian_awal","antrian_akhir","create_date","jam_mulai","waktu_antrian") VALUES (244, 10, 5, 10, '2020-09-24', '6:57', '0:20'),(246, 12, 5, 6, '2020-09-25', '6:15', '0:15'),(247, 10, 5, 6, '2020-09-25', '6:15', '0:15'),(248, 10, 5, 6, '2020-09-25', '6:15', '0:15'),(249, 10, 5, 6, '2020-09-25', '6:16', '0:15'),(250, 10, 7, 8, '2020-09-25', '6:14', '0:16'),(251, 10, 6, 7, '2020-09-25', '6:15', '0:15');
COMMIT;
BEGIN;
LOCK TABLE "public"."register_dokter" IN SHARE MODE;
DELETE FROM "public"."register_dokter";
INSERT INTO "public"."register_dokter" ("id_register","id_poli","id_dokter","id_ruang","antrian_all","antrian_awal","antrian_akhir","create_date","id_condition","jam_mulai","waktu_antrian") VALUES (249, 9, 17, 16, 10, 5, 10, '2020-09-24', 1, '6:57', '0:20'),(251, 9, 19, 18, 12, 5, 6, '2020-09-25', 1, '6:15', '0:15'),(252, 11, 20, 19, 10, 5, 6, '2020-09-25', 1, '6:15', '0:15'),(253, 11, 21, 20, 10, 5, 6, '2020-09-25', 1, '6:15', '0:15'),(254, 12, 22, 21, 10, 5, 6, '2020-09-25', 1, '6:16', '0:15'),(255, 12, 23, 22, 10, 7, 8, '2020-09-25', 1, '6:14', '0:16'),(256, 13, 25, 23, 10, 6, 7, '2020-09-25', 1, '6:15', '0:15');
COMMIT;
BEGIN;
LOCK TABLE "public"."ruang" IN SHARE MODE;
DELETE FROM "public"."ruang";
INSERT INTO "public"."ruang" ("id_ruang","nama_ruang","id_poli","id_condition") VALUES (16, 'R 1.1', 9, 2),(18, 'R 12', 9, 2),(19, 'R 15', 11, 2),(20, 'R 16', 11, 2),(21, 'R 16', 12, 2),(22, 'R 21', 12, 2),(23, 'R 22', 13, 2),(24, 'R 21', 13, 1),(25, 'R 21', 13, 1);
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
INSERT INTO "public"."user_data_session" ("session_id","name","modified","lifetime","data","owner") VALUES ('plbu72bdqvueahipj23g07lqd1', 'KHANSIA', 1601032657, 86400, '__ZF|a:5:{s:20:"_REQUEST_ACCESS_TIME";d:1601032657.764406;s:6:"_VALID";a:1:{s:25:"Zend\Session\Validator\Id";s:26:"plbu72bdqvueahipj23g07lqd1";}s:3:"_LT";i:86400;s:3:"_LA";i:1601032657;s:6:"_OWNER";s:1:"2";}__KHANSIA|a:14:{s:14:"token_keamanan";s:32:"be494965e8a3ba5ada5e71bb98173d44";s:7:"baseurl";s:14:"//frontend.azy";s:7:"user_id";s:1:"2";s:9:"usernamed";s:5:"admin";s:6:"passwd";s:32:"21232f297a57a5a743894a0e4a801fc3";s:4:"name";s:5:"Atang";s:4:"role";s:2:"10";s:6:"status";s:2:"10";s:8:"deviceid";N;s:5:"token";N;s:7:"retries";s:4:"NULL";s:10:"create_dtm";s:19:"2017-08-10 10:29:40";s:6:"access";a:0:{}s:9:"role_code";s:1:"E";}', '2'),('1nljcdjb435aripmcoase49tm6', 'KHANSIA', 1601216386, 86400, '__ZF|a:5:{s:20:"_REQUEST_ACCESS_TIME";d:1601216386.807401;s:6:"_VALID";a:1:{s:25:"Zend\Session\Validator\Id";s:26:"1nljcdjb435aripmcoase49tm6";}s:3:"_LT";i:86400;s:3:"_LA";i:1601216386;s:6:"_OWNER";s:1:"2";}__KHANSIA|a:14:{s:14:"token_keamanan";s:32:"6677a8384b855fa20dc069b7fdd989d3";s:7:"baseurl";s:14:"//frontend.azy";s:7:"user_id";s:1:"2";s:9:"usernamed";s:5:"admin";s:6:"passwd";s:32:"21232f297a57a5a743894a0e4a801fc3";s:4:"name";s:5:"Atang";s:4:"role";s:2:"10";s:6:"status";s:2:"10";s:8:"deviceid";N;s:5:"token";N;s:7:"retries";s:4:"NULL";s:10:"create_dtm";s:19:"2017-08-10 10:29:40";s:6:"access";a:0:{}s:9:"role_code";s:1:"E";}', '2'),('mmg78bkbgip9qgtu8mbo0cfmc8', 'KHANSIA', 1601284788, 86400, '__ZF|a:5:{s:20:"_REQUEST_ACCESS_TIME";d:1601284788.800771;s:6:"_VALID";a:1:{s:25:"Zend\Session\Validator\Id";s:26:"mmg78bkbgip9qgtu8mbo0cfmc8";}s:3:"_LT";i:86400;s:3:"_LA";i:1601284788;s:6:"_OWNER";s:1:"2";}__KHANSIA|a:14:{s:14:"token_keamanan";s:32:"7d896d538aa63eca003cca740ee7aa0d";s:7:"baseurl";s:14:"//frontend.azy";s:7:"user_id";s:1:"2";s:9:"usernamed";s:5:"admin";s:6:"passwd";s:32:"21232f297a57a5a743894a0e4a801fc3";s:4:"name";s:5:"Atang";s:4:"role";s:2:"10";s:6:"status";s:2:"10";s:8:"deviceid";N;s:5:"token";N;s:7:"retries";s:4:"NULL";s:10:"create_dtm";s:19:"2017-08-10 10:29:40";s:6:"access";a:0:{}s:9:"role_code";s:1:"E";}', '2');
COMMIT;
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
ALTER TABLE "pasien" ADD CONSTRAINT "pasien_pkey" PRIMARY KEY ("id_pasien", "no_rekam_medis");
ALTER TABLE "poli" ADD CONSTRAINT "poli_pkey" PRIMARY KEY ("id_poli");
ALTER TABLE "regional" ADD CONSTRAINT "regional_pkey" PRIMARY KEY ("id_regional");
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
ALTER TABLE "antrian_pasien" ADD CONSTRAINT "antrian_pasien_id_register_fkey" FOREIGN KEY ("id_register") REFERENCES "public"."register_dokter" ("id_register") ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER TABLE "antrian_pasien" ADD CONSTRAINT "antrian_pasien_id_ruang_fkey" FOREIGN KEY ("id_ruang") REFERENCES "public"."ruang" ("id_ruang") ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER TABLE "antrian_pasien" ADD CONSTRAINT "dokter" FOREIGN KEY ("id_dokter") REFERENCES "public"."dokter" ("id_dokter") ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER TABLE "antrian_pasien" ADD CONSTRAINT "no_rekam_medis" FOREIGN KEY ("no_rekam_medis") REFERENCES "public"."pasien" ("no_rekam_medis") ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER TABLE "dokter_to_poli" ADD CONSTRAINT "Dokter" FOREIGN KEY ("id_dokter") REFERENCES "public"."dokter" ("id_dokter") ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER TABLE "dokter_to_poli" ADD CONSTRAINT "Poli" FOREIGN KEY ("id_poli") REFERENCES "public"."poli" ("id_poli") ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER TABLE "dokter_to_poli" ADD CONSTRAINT "condition" FOREIGN KEY ("id_condition") REFERENCES "public"."condition" ("id_condition") ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER TABLE "master_parameter" ADD CONSTRAINT "master_parameter_ibfk_1" FOREIGN KEY ("param_parent") REFERENCES "public"."master_parameter" ("idm_parameter") ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER TABLE "pasien" ADD CONSTRAINT "id_pasien" UNIQUE ("no_rekam_medis");
ALTER TABLE "ruang" ADD CONSTRAINT "condition" FOREIGN KEY ("id_condition") REFERENCES "public"."condition" ("id_condition") ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER TABLE "ruang" ADD CONSTRAINT "id_poli" FOREIGN KEY ("id_poli") REFERENCES "public"."poli" ("id_poli") ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER SEQUENCE "antrian_id_antrian"
OWNED BY "antrian"."id_antrian";
SELECT setval('"antrian_id_antrian"', 2175, true);
ALTER SEQUENCE "antrian_id_antrian" OWNER TO "postgres";
ALTER SEQUENCE "dokter\_id_dokter_seq"
OWNED BY "dokter_to_poli"."id_dokter";
SELECT setval('"dokter\_id_dokter_seq"', 27, true);
ALTER SEQUENCE "dokter\_id_dokter_seq" OWNER TO "postgres";
ALTER SEQUENCE "id_antrian_seq2"
OWNED BY "antrian_pasien"."id_antrian";
SELECT setval('"id_antrian_seq2"', 2175, true);
ALTER SEQUENCE "id_antrian_seq2" OWNER TO "postgres";
ALTER SEQUENCE "id_antrian_seq"
OWNED BY "register_dokter"."id_register";
SELECT setval('"id_antrian_seq"', 257, true);
ALTER SEQUENCE "id_antrian_seq" OWNER TO "postgres";
ALTER SEQUENCE "id_dokter_seq"
OWNED BY "dokter"."id_dokter";
SELECT setval('"id_dokter_seq"', 27, true);
ALTER SEQUENCE "id_dokter_seq" OWNER TO "postgres";
ALTER SEQUENCE "id_ruang_seq"
OWNED BY "ruang"."id_ruang";
SELECT setval('"id_ruang_seq"', 26, true);
ALTER SEQUENCE "id_ruang_seq" OWNER TO "postgres";
SELECT setval('"no_rekam_medis"', 127, true);
ALTER SEQUENCE "no_rekam_medis" OWNER TO "postgres";
ALTER SEQUENCE "pasien_id_pasien_seq"
OWNED BY "antrian_pasien"."id_pasien";
SELECT setval('"pasien_id_pasien_seq"', 35, true);
ALTER SEQUENCE "pasien_id_pasien_seq" OWNER TO "postgres";
ALTER SEQUENCE "poli_id_poli_seq"
OWNED BY "poli"."id_poli";
SELECT setval('"poli_id_poli_seq"', 200, true);
ALTER SEQUENCE "poli_id_poli_seq" OWNER TO "postgres";
ALTER SEQUENCE "register_antrian_id_register"
OWNED BY "register_antrian"."id_register";
SELECT setval('"register_antrian_id_register"', 252, true);
ALTER SEQUENCE "register_antrian_id_register" OWNER TO "postgres";
ALTER SEQUENCE "user_data_header_iduser_seq1"
OWNED BY "user_data_header"."iduser";
SELECT setval('"user_data_header_iduser_seq1"', 2, false);
ALTER SEQUENCE "user_data_header_iduser_seq1" OWNER TO "postgres";
ALTER SEQUENCE "user_data_header_iduser_seq"
OWNED BY "user_data_header"."iduser";
SELECT setval('"user_data_header_iduser_seq"', 3, false);
ALTER SEQUENCE "user_data_header_iduser_seq" OWNER TO "postgres";
