
   INFO  Preparing database.  

  Creating migration table ............................................................................. 1.77ms DONE

   INFO  Running migrations.  

  0001_01_01_000000_create_users_table .............................................................................  
  ⇂ create table "users" ("id" integer primary key autoincrement not null, "name" varchar not null, "email" varchar not null, "email_verified_at" datetime, "password" varchar not null, "remember_token" varchar, "created_at" datetime, "updated_at" datetime)  
  ⇂ create unique index "users_email_unique" on "users" ("email")  
  ⇂ create table "password_reset_tokens" ("email" varchar not null, "token" varchar not null, "created_at" datetime, primary key ("email"))  
  ⇂ create table "sessions" ("id" varchar not null, "user_id" integer, "ip_address" varchar, "user_agent" text, "payload" text not null, "last_activity" integer not null, primary key ("id"))  
  ⇂ create index "sessions_user_id_index" on "sessions" ("user_id")  
  ⇂ create index "sessions_last_activity_index" on "sessions" ("last_activity")  
  0001_01_01_000001_create_cache_table .............................................................................  
  ⇂ create table "cache" ("key" varchar not null, "value" text not null, "expiration" integer not null, primary key ("key"))  
  ⇂ create table "cache_locks" ("key" varchar not null, "owner" varchar not null, "expiration" integer not null, primary key ("key"))  
  0001_01_01_000002_create_jobs_table ..............................................................................  
  ⇂ create table "jobs" ("id" integer primary key autoincrement not null, "queue" varchar not null, "payload" text not null, "attempts" integer not null, "reserved_at" integer, "available_at" integer not null, "created_at" integer not null)  
  ⇂ create index "jobs_queue_index" on "jobs" ("queue")  
  ⇂ create table "job_batches" ("id" varchar not null, "name" varchar not null, "total_jobs" integer not null, "pending_jobs" integer not null, "failed_jobs" integer not null, "failed_job_ids" text not null, "options" text, "cancelled_at" integer, "created_at" integer not null, "finished_at" integer, primary key ("id"))  
  ⇂ create table "failed_jobs" ("id" integer primary key autoincrement not null, "uuid" varchar not null, "connection" text not null, "queue" text not null, "payload" text not null, "exception" text not null, "failed_at" datetime not null default CURRENT_TIMESTAMP)  
  ⇂ create unique index "failed_jobs_uuid_unique" on "failed_jobs" ("uuid")  
  2026_06_10_164620_create_page_histories_table ....................................................................  
  ⇂ create table "page_histories" ("id" integer primary key autoincrement not null, "page_id" integer not null, "title" varchar not null, "slug" varchar not null, "content" text, "published" tinyint(1) not null default '0', "created_at" datetime, "updated_at" datetime, foreign key("page_id") references "pages"("id") on delete cascade)  
  2026_06_10_174108_create_image_managers_table ....................................................................  
  ⇂ create table "image_managers" ("id" integer primary key autoincrement not null, "title" varchar, "perex" text, "group" varchar, "created_at" datetime, "updated_at" datetime)  
  2026_06_10_174108_create_menus_table .............................................................................  
  ⇂ create table "menus" ("id" integer primary key autoincrement not null, "label" varchar not null, "type" varchar not null default 'custom', "parent_id" integer, "url" varchar, "published" tinyint(1) not null default '1', "order" integer not null default '0', "created_at" datetime, "updated_at" datetime)  
  2026_06_10_174108_create_pages_table .............................................................................  
  ⇂ create table "pages" ("id" integer primary key autoincrement not null, "title" varchar not null, "slug" varchar, "content" text, "published" tinyint(1) not null default '0', "created_at" datetime, "updated_at" datetime)  
  ⇂ create unique index "pages_slug_unique" on "pages" ("slug")  
  2026_06_10_174109_create_layout_overrides_table ..................................................................  
  ⇂ create table "layout_overrides" ("id" integer primary key autoincrement not null, "path_pattern" varchar not null, "layout" varchar not null, "created_at" datetime, "updated_at" datetime)  
  2026_06_10_174646_create_articles_table ..........................................................................  
  ⇂ create table "articles" ("id" integer primary key autoincrement not null, "title" varchar not null, "slug" varchar not null, "content" text, "published" tinyint(1) not null default '0', "category" varchar, "perex" text, "image" varchar, "created_at" datetime, "updated_at" datetime)  
  ⇂ create unique index "articles_slug_unique" on "articles" ("slug")  

