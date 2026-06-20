-- ============================================================
-- SD Biosensor Panamá — Capa específica de Supabase/PostgreSQL
-- Aplicar DESPUÉS de `php artisan migrate` apuntando a Supabase
-- (Laravel crea el esquema; aquí añadimos lo propio de Postgres).
--
-- Ejecutar en: Supabase Dashboard → SQL Editor, o `supabase db push`.
-- ============================================================

-- 1) Extensiones -------------------------------------------------------------
create extension if not exists pg_trgm;            -- búsquedas ILIKE %term% indexadas

-- 2) Índices de búsqueda (aceleran el buscador de productos/soporte) ---------
create index if not exists products_name_trgm_idx
    on public.products using gin (name gin_trgm_ops);
create index if not exists products_desc_trgm_idx
    on public.products using gin (description gin_trgm_ops);
create index if not exists downloads_title_trgm_idx
    on public.downloads using gin (title gin_trgm_ops);

-- 3) Row Level Security ------------------------------------------------------
-- Modelo de acceso:
--   * Laravel se conecta con un rol privilegiado (owner) → omite RLS y
--     mantiene TODA la lógica de escritura en la capa de aplicación.
--   * RLS protege la superficie pública de Supabase (PostgREST/Realtime):
--     los roles `anon` y `authenticated` solo pueden LEER filas activas.
--   * Nadie escribe vía API pública: las escrituras van por Laravel o por
--     la `service_role` (que omite RLS por diseño).

alter table public.product_categories enable row level security;
alter table public.products           enable row level security;
alter table public.news_categories    enable row level security;
alter table public.news_items         enable row level security;
alter table public.catalogs           enable row level security;
alter table public.downloads          enable row level security;

-- Lectura pública SOLO de contenido activo --------------------------------
create policy "public read active product_categories"
    on public.product_categories for select
    to anon, authenticated using (is_active = true);

create policy "public read active products"
    on public.products for select
    to anon, authenticated using (is_active = true);

create policy "public read news_categories"
    on public.news_categories for select
    to anon, authenticated using (true);

create policy "public read active news_items"
    on public.news_items for select
    to anon, authenticated using (is_active = true);

create policy "public read catalogs"
    on public.catalogs for select
    to anon, authenticated using (true);

create policy "public read downloads"
    on public.downloads for select
    to anon, authenticated using (true);

-- (Sin policies de INSERT/UPDATE/DELETE para anon/authenticated =>
--  toda escritura queda denegada en la API pública por defecto.)

-- 4) Mantener updated_at en escrituras directas vía API ----------------------
-- (Laravel ya gestiona timestamps; esto cubre escrituras hechas fuera de él.)
create extension if not exists moddatetime schema extensions;

do $$
declare t text;
begin
  foreach t in array array[
    'product_categories','products','news_categories',
    'news_items','catalogs','downloads'
  ] loop
    execute format(
      'create trigger set_updated_at before update on public.%I
         for each row execute procedure extensions.moddatetime(updated_at);', t);
  end loop;
end $$;
